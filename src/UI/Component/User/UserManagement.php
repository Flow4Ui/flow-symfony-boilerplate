<?php

declare(strict_types=1);

namespace App\UI\Component\User;

use App\Entity\User;
use App\Service\UserManager;
use Flow\Attributes\Action;
use Flow\Attributes\Component;
use Flow\Attributes\Property;
use Flow\Component\AbstractComponent;
use Flow\Contract\HasInitState;
use Symfony\Component\HttpFoundation\Request;

#[Component(
    name: 'UserManagement',
    template: <<<'HTML'
<template>
    <div class="grid">
        <div class="card">
            <div class="list-head">
                <input
                    v-model="search"
                    class="input"
                    placeholder="Search name or email"
                    @keyup.enter="applySearch"
                >
                <button class="btn btn-secondary" @click="applySearch">Search</button>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.role }}</td>
                    <td>{{ user.active ? 'Active' : 'Disabled' }}</td>
                    <td>
                        <div class="actions-row">
                            <button class="btn btn-secondary" @click="editUser(user.id)">Edit</button>
                            <button class="btn btn-secondary" @click="toggleActive(user.id)">Toggle</button>
                            <button class="btn btn-danger" @click="deleteUser(user.id)">Delete</button>
                        </div>
                    </td>
                </tr>
                <tr v-if="users.length === 0">
                    <td colspan="5">No users found.</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h3>{{ editingUserId ? 'Edit user' : 'Create user' }}</h3>

            <label class="field">
                <span>Name</span>
                <input v-model="name" class="input" type="text" placeholder="Full name">
            </label>

            <label class="field">
                <span>Email</span>
                <input v-model="email" class="input" type="email" placeholder="name@example.com">
            </label>

            <label class="field">
                <span>Role</span>
                <select v-model="role" class="input">
                    <option value="ROLE_USER">ROLE_USER</option>
                    <option value="ROLE_ADMIN">ROLE_ADMIN</option>
                </select>
            </label>

            <label class="field checkbox-line">
                <input v-model="active" type="checkbox">
                <span>Active</span>
            </label>

            <div class="actions-row">
                <button class="btn" @click="saveUser">{{ editingUserId ? 'Update' : 'Create' }}</button>
                <button class="btn btn-secondary" @click="cancelEdit">Cancel</button>
            </div>

            <p v-if="feedback" class="feedback success">{{ feedback }}</p>
            <p v-if="error" class="feedback error">{{ error }}</p>
        </div>
    </div>
</template>
HTML,
)]
class UserManagement extends AbstractComponent implements HasInitState
{
    #[Property]
    public array $users = [];

    #[Property]
    public ?int $editingUserId = null;

    #[Property]
    public string $search = '';

    #[Property]
    public string $name = '';

    #[Property]
    public string $email = '';

    #[Property]
    public string $role = 'ROLE_USER';

    #[Property]
    public bool $active = true;

    #[Property]
    public string $feedback = '';

    #[Property]
    public string $error = '';

    public function __construct(private readonly UserManager $userManager)
    {
    }

    public function initState(Request $request): void
    {
        $this->refreshUsers();
    }

    #[Action]
    public function applySearch(): void
    {
        $this->refreshUsers();
    }

    #[Action]
    public function editUser(int $id): void
    {
        $user = $this->userManager->get($id);
        if (!$user instanceof User) {
            $this->error = 'User not found.';

            return;
        }

        $this->editingUserId = $id;
        $this->name = $user->getName();
        $this->email = $user->getEmail();
        $this->role = $user->getRole();
        $this->active = $user->isActive();
        $this->feedback = '';
        $this->error = '';
    }

    #[Action]
    public function saveUser(): void
    {
        try {
            if ($this->editingUserId === null) {
                $this->userManager->create($this->name, $this->email, $this->role, $this->active);
                $this->feedback = 'User created.';
            } else {
                $this->userManager->update($this->editingUserId, $this->name, $this->email, $this->role, $this->active);
                $this->feedback = 'User updated.';
            }

            $this->error = '';
            $this->resetForm();
            $this->refreshUsers();
        } catch (\Throwable $exception) {
            $this->error = $exception->getMessage();
            $this->feedback = '';
        }
    }

    #[Action]
    public function deleteUser(int $id): void
    {
        $this->userManager->delete($id);
        if ($this->editingUserId === $id) {
            $this->resetForm();
        }

        $this->feedback = 'User deleted.';
        $this->error = '';
        $this->refreshUsers();
    }

    #[Action]
    public function toggleActive(int $id): void
    {
        $changed = $this->userManager->toggleActive($id);
        if ($changed instanceof User) {
            $this->feedback = sprintf('User %s is now %s.', $changed->getName(), $changed->isActive() ? 'active' : 'disabled');
            $this->error = '';
        }

        $this->refreshUsers();
    }

    #[Action]
    public function cancelEdit(): void
    {
        $this->resetForm();
        $this->error = '';
        $this->feedback = 'Form reset.';
    }

    private function refreshUsers(): void
    {
        $this->users = array_map(
            fn (User $user): array => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'active' => $user->isActive(),
                'createdAt' => $user->getCreatedAt()->format(DATE_ATOM),
                'updatedAt' => $user->getUpdatedAt()->format(DATE_ATOM),
            ],
            $this->userManager->list(trim($this->search)),
        );
    }

    private function resetForm(): void
    {
        $this->editingUserId = null;
        $this->name = '';
        $this->email = '';
        $this->role = 'ROLE_USER';
        $this->active = true;
    }
}
