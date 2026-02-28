<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use RuntimeException;

class UserManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @return list<User>
     */
    public function list(string $search = ''): array
    {
        return $this->userRepository->searchByNameOrEmail($search);
    }

    public function get(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function create(string $name, string $email, string $role, bool $active): User
    {
        $this->assertValid($name, $email, $role);
        $this->assertEmailIsUnique($email);

        $user = (new User())
            ->setName(trim($name))
            ->setEmail($email)
            ->setRole($role)
            ->setActive($active);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function update(int $id, string $name, string $email, string $role, bool $active): User
    {
        $this->assertValid($name, $email, $role);

        $user = $this->userRepository->find($id);
        if (!$user instanceof User) {
            throw new RuntimeException('User not found.');
        }

        $existingWithEmail = $this->userRepository->findOneBy(['email' => mb_strtolower($email)]);
        if ($existingWithEmail instanceof User && $existingWithEmail->getId() !== $user->getId()) {
            throw new InvalidArgumentException('Email is already in use.');
        }

        $user
            ->setName(trim($name))
            ->setEmail($email)
            ->setRole($role)
            ->setActive($active);

        $this->entityManager->flush();

        return $user;
    }

    public function delete(int $id): void
    {
        $user = $this->userRepository->find($id);
        if (!$user instanceof User) {
            return;
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function toggleActive(int $id): ?User
    {
        $user = $this->userRepository->find($id);
        if (!$user instanceof User) {
            return null;
        }

        $user->setActive(!$user->isActive());
        $this->entityManager->flush();

        return $user;
    }

    private function assertValid(string $name, string $email, string $role): void
    {
        if (trim($name) === '') {
            throw new InvalidArgumentException('Name is required.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('A valid email is required.');
        }

        if (!in_array($role, ['ROLE_ADMIN', 'ROLE_USER'], true)) {
            throw new InvalidArgumentException('Role must be ROLE_ADMIN or ROLE_USER.');
        }
    }

    private function assertEmailIsUnique(string $email): void
    {
        $existing = $this->userRepository->findOneBy(['email' => mb_strtolower($email)]);
        if ($existing instanceof User) {
            throw new InvalidArgumentException('Email is already in use.');
        }
    }
}
