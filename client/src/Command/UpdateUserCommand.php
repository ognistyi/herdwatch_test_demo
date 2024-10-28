<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'api:UpdateUser',
    description: 'Updates a user\'s details'
)]
class UpdateUserCommand extends Command
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
            ->setDescription('Updates a user\'s details.')
            ->addArgument('id', InputArgument::REQUIRED, 'User ID')
            ->addArgument('name', InputArgument::OPTIONAL, 'New User name')
            ->addArgument('email', InputArgument::OPTIONAL, 'New User email')
            ->addArgument('group', InputArgument::OPTIONAL, 'New User group');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = array_filter([
            'name' => $input->getArgument('name'),
            'email' => $input->getArgument('email'),
            'group' => $input->getArgument('group'),
        ]);

        $response = $this->client->request('PUT', 'http://server:8000/api/users/' . $input->getArgument('id'), [
            'json' => $data,
        ]);

        $output->writeln($response->getContent());

        return Command::SUCCESS;
    }
}
