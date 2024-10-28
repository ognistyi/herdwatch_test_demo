<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'api:DeleteGroup',
    description: 'Deletes a group'
)]
class DeleteGroupCommand extends Command
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
            ->setDescription('Deletes a group.')
            ->addArgument('id', InputArgument::REQUIRED, 'Group ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->client->request('DELETE', 'http://server:8000/api/groups/' . $input->getArgument('id'));
        $output->writeln($response->getContent());

        return Command::SUCCESS;
    }
}
