<?php declare(strict_types=1);

namespace App\Command;

use Elasticsearch\ClientBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateIndexCommand extends Command
{
    protected static $defaultName = 'es:create:index';

    protected function configure()
    {
        $this->setDescription('Tworzy indeks w ElsticSearch');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();

        $paramCreateIndex = [
            'index' => 'movies_v1',
            'body' => [
                'mappings' => [
                    'dynamic' => 'strict',
                    'properties' => [
                        'title' => [
                            'type' => 'text',
                        ],
                        'title_org' => [
                            'type' => 'text',
                        ],
                        'year' => [
                            'type' => 'short',
                        ],
                        'rate' => [
                            'type' => 'float',
                        ],
                        'type' => [
                            'type' => 'keyword',
                        ],
                        'poster' => [
                            'type' => 'keyword',
                        ],
                        'directors' => [
                            'properties' => [
                                'id' => [
                                    'type' => 'integer',
                                ],
                                'name' => [
                                    'type' => 'text',
                                ],
                            ],
                        ],
                        'genres' => [
                            'properties' => [
                                'id' => [
                                    'type' => 'integer',
                                ],
                                'name' => [
                                    'type' => 'text',
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        ];

        $response = $client->indices()->create($paramCreateIndex);

        if ($response['acknowledged'])
        {
            $output->writeln('Indeks utworzono');
            return Command::SUCCESS;
        }

        $output->writeln(print_r($response, true));
        return Command::FAILURE;
    }
}