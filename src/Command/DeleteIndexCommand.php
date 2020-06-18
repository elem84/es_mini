<?php declare(strict_types=1);

namespace App\Command;

use Elasticsearch\ClientBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteIndexCommand extends Command
{
    protected static $defaultName = 'es:delete:index';

    protected function configure()
    {
        $this->setDescription('Usuwa indeks w ElsticSearch');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();

        $paramCreateIndex = [
            'index' => 'movies_v1',
        ];

        $response = $client->indices()->delete($paramCreateIndex);

        if ($response['acknowledged'])
        {
            $output->writeln('Indeks usunięty');
            return Command::SUCCESS;
        }

        $output->writeln(print_r($response, true));
        return Command::FAILURE;
    }
}