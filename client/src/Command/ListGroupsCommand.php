<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'api:ListGroups',
    description: 'Lists all groups'
)]
class ListGroupsCommand extends Command
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    protected function configure(): void
    {
        $this->setDescription('Lists all groups');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $response = $this->client->request('GET', 'http://server:8000/api/groups');
        $groups = $response->toArray();

        if (empty($groups)) {
            $symfonyStyle->warning('No groups found.');
            return Command::SUCCESS;
        }

        $symfonyStyle->title('List of Groups');
        $symfonyStyle->table(
            ['ID', 'Name',],
            array_map(fn($group) => [
                $group['id'] ?? 'N/A',
                $group['name'] ?? 'N/A',
            ], $groups)
        );

        return Command::SUCCESS;
    }
}
