<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'api:CreateGroup',
    description: 'Creates a new group'
)]
class CreateGroupCommand extends Command
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
            ->setDescription('Creates a new group.')
            ->addArgument('name', InputArgument::REQUIRED, 'Group name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->client->request('POST', 'http://server:8000/api/groups', [
            'json' => [
                'name' => $input->getArgument('name'),
            ]
        ]);

        $output->writeln($response->getContent());

        return Command::SUCCESS;
    }
}
