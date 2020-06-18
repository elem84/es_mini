<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Elasticsearch\ClientBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexMoviesCommand extends Command
{
    protected static $defaultName = 'es:index:movies';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    protected function configure()
    {
        $this->setDescription('Indeksuje filmy w ElsticSearch');
    }

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();

        /** @var Movie[] $movies */
        $movies = $this->entityManager->getRepository(Movie::class)->findAllCustom();

        foreach ($movies as $movie)
        {
            $directors = [];

            foreach ($movie->getDirector() as $director)
            {
                $directors[] = [
                    'id' => $director->getId(),
                    'name' => $director->getName(),
                ];
            }

            $genres = [];

            foreach ($movie->getGenre() as $genre)
            {
                $genres[] = [
                    'id' => $genre->getId(),
                    'name' => $genre->getName(),
                ];
            }

            $response = $client->index([
                'index' => 'movies_v1',
                'id' => $movie->getId(),
                'type' => '_doc',
                'body' => [
                    'title' => $movie->getTitle(),
                    'title_org' => $movie->getTitleOrg(),
                    'year' => $movie->getYear(),
                    'rate' => $movie->getRate(),
                    'type' => $movie->getType(),
                    'poster' => $movie->getPoster(),
                    'directors' => $directors,
                    'genres' => $genres,
                ],
            ]);

            if ('created' === $response['result'])
            {
                $output->writeln(sprintf(
                    'Film "%s" zaindeksowany',
                    $movie->getTitle()
                ));
            }
            elseif ('updated' === $response['result'])
            {
                $output->writeln(sprintf(
                    'Film "%s" zaktualizowany',
                    $movie->getTitle()
                ));
            }
            else
            {
                $output->writeln('ERROR');
                $output->writeln(print_r($response['result'], true));
            }
        }

        $output->writeln('Indeksowanie zakończone');
        return Command::SUCCESS;
    }
}