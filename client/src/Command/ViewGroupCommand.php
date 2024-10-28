<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'api:ViewGroup',
    description: 'Add a short description for your command'
)]
class ViewGroupCommand extends Command
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    protected function configure(): void
    {
        $this->setDescription('Displays a group\'s details.')
            ->addArgument('id', InputArgument::REQUIRED, 'Group ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('id');
        $response = $this->client->request('GET', "http://server:8000/api/groups/{$id}");
        $output->writeln($response->getContent());

        return Command::SUCCESS;
    }
}
