<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'api:UpdateGroup',
    description: 'Updates a group\'s details'
)]
class UpdateGroupCommand extends Command
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    protected function configure(): void
    {
        $this->setDescription('Updates group details.')
            ->addArgument('id', InputArgument::REQUIRED, 'Group ID')
            ->addArgument('name', InputArgument::OPTIONAL, 'New Group name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->client->request('PUT', 'http://server:8000/api/groups/' . $input->getArgument('id'), [
            'json' => [
                'name' => $input->getArgument('name')
            ]
        ]);

        $output->writeln($response->getContent());

        return Command::SUCCESS;
    }
}
