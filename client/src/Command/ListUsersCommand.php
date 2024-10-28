<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'api:ListUsers',
    description: 'Add a short description for your command',
)]
class ListUsersCommand extends Command
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Lists all users or users from a specific group.')
            ->addArgument('group', InputArgument::OPTIONAL, 'Group name to filter users');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //todo: check this action

        $group = $input->getArgument('group');
        $url = 'http://server:8000/api/users';

        if ($group) {
            $url .= '?group=' . urlencode($group);
        }

        $response = $this->client->request('GET', $url);
        $users = $response->toArray();

        if (empty($users)) {
            $output->writeln('No users found' . ($group ? " in group '{$group}'" : '') . '.');
            return Command::SUCCESS;
        }

        //todo: update to ->table
        foreach ($users as $user) {
            $output->writeln("ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}, Group: {$user['group']}");
        }

        return Command::SUCCESS;
    }
}
