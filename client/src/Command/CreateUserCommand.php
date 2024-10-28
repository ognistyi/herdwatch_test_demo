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
    name: 'api:CreateUser',
    description: 'Add a short description for your command'
)]
class CreateUserCommand extends Command
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
            ->setDescription('Creates a new user with an optional group.')
            ->addArgument('name', InputArgument::REQUIRED, 'User name')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('group', InputArgument::OPTIONAL, 'Group name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $group = $input->getArgument('group');

        $response = $this->client->request('POST', 'http://server:8000/api/users', [
            'json' => [
                'name' => $input->getArgument('name'),
                'email' => $input->getArgument('email'),
                'group' => $group,
            ]
        ]);

        $output->writeln($response->getContent());

        return Command::SUCCESS;
    }
}
