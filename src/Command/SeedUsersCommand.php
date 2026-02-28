<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\UserManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-users',
    description: 'Seed sample users for the Flow boilerplate.',
)]
class SeedUsersCommand extends Command
{
    public function __construct(private readonly UserManager $userManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (count($this->userManager->list()) > 0) {
            $io->note('Users table already has data. Skipping seed.');

            return Command::SUCCESS;
        }

        $this->userManager->create('Alice Admin', 'alice@example.com', 'ROLE_ADMIN', true);
        $this->userManager->create('Umar User', 'umar@example.com', 'ROLE_USER', true);
        $this->userManager->create('Dora Disabled', 'dora@example.com', 'ROLE_USER', false);

        $io->success('Sample users inserted.');

        return Command::SUCCESS;
    }
}
