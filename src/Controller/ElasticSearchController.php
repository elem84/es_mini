<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Elasticsearch\ClientBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ElasticSearchController extends AbstractController
{
    public function index(
        Request $request,
        EntityManagerInterface $entityManager
    )
    {
        if ($request->isXmlHttpRequest())
        {
            $client = ClientBuilder::create()
                ->setHosts(['127.0.0.1:9200'])
                ->build();

            $keyword = trim($request->get('keyword'));
            $genresSelected = $request->get('genres_selected', []);
            $words = explode(' ', $keyword);
            $filter = [];

            if (false === empty($genresSelected))
            {
                foreach ($genresSelected as $id)
                {
                    $filter[] = [
                        'nested' => [
                            'path' => 'genres',
                            'query' => [
                                'term' => [
                                    'genres.id' => $id,
                                ],
                            ],
                        ],
                    ];
                }
            }

            $result = $client->search([
                'index' => 'movies_v2',
                'body' => [
                    'size' => 100,
                    'query' => [
                        'bool' => [
                            'filter' => $filter,
                            'must' => [
                                'multi_match' => [
                                    'query' => $keyword,
                                    'type' => 'bool_prefix',
                                    'operator' => 'and',
                                    'fields' => [
                                        'title',
                                    ]
                                ],
                            ],
                            'should' => [
                                [
                                    'span_first' => [
                                        'match' => [
                                            'span_term' => [
                                                'title._index_prefix' => strtolower($words[0]),
                                            ]
                                        ],
                                        'end' => 1,
                                    ]
                                ]
                            ]
                        ],
                    ],
                ]
            ]);

            $genresCount = [];

            foreach ($result['hits']['hits'] as $doc)
            {
                if (isset($doc['_source']['genres']))
                {
                    foreach ($doc['_source']['genres'] as $genre)
                    {
                        $genresCount[$genre['id']] = $genre['id'];
                    }
                }
            }

            return $this->render('Index/elasticsearch-result.html.twig', [
                'items' => $result['hits']['hits'],
                'keyword' => $keyword,
                'genres' => $entityManager
                    ->getRepository(Genre::class)
                    ->findBy([], ['name' => 'ASC']),
                'genresCount' => $genresCount,
                'genresSelected' => array_combine(array_values($genresSelected), array_values($genresSelected)),
            ]);
        }

        return $this->render('Index/elasticsearch.html.twig');
    }
}