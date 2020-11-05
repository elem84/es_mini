<?php declare(strict_types=1);

namespace App\Controller;

use Elasticsearch\ClientBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ElasticSearchController extends AbstractController
{
    public function index(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $client = ClientBuilder::create()
                ->setHosts(['127.0.0.1:9200'])
                ->build();

            $keyword = trim($request->get('keyword'));
            $words = explode(' ', $keyword);

            $result = $client->search([
                'index' => 'movies_v2',
                'body' => [
                    'size' => 100,
                    'query' => [
                        'bool' => [
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

            return $this->render('Index/elasticsearch-result.html.twig', [
                'items' => $result['hits']['hits'],
                'keyword' => $keyword,
            ]);
        }

        return $this->render('Index/elasticsearch.html.twig');
    }
}