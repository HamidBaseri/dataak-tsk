<?php

namespace App\Repositories\Tweets;

use App\Interfaces\TweetsRepository;
use App\Models\Tweet;
use Elasticsearch\Client;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;

class ElasticsearchRepository implements TweetsRepository
{
    /**
     * @var Client
     */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function search(array $params): Collection
    {
        $items = $this->searchOnElasticsearch($params);

        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(array $params): array
    {
        $model = new Tweet;
        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'bool' => [
                                    'should' => [
                                        [
                                            'multi_match' => [
                                                'fields' => ['username', 'body'],
                                                'query' => $params['q'] ?? '',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'bool' => [
                                    'must' => [
                                        [
                                            'range' => [
                                                "created_at" => [
                                                    "gte" => $params['date'] ?? null,
                                                    "format" => "dd-MM-yyyy"
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    ]
                ],
            ],
        ]);

        return $items;
    }

    private function buildCollection(array $items): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        return Tweet::findMany($ids)
            ->sortBy(function ($tweet) use ($ids) {
                return array_search($tweet->getKey(), $ids);
            });
    }
}
