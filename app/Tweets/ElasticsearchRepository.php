<?php

namespace App\Tweets;

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

    public function search(string $query = '', string $date = ''): Collection
    {
        $items = $this->searchOnElasticsearch($query, $date);

        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(string $query = '', string $date = ''): array
    {
        $model = new Tweet;

        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'multi_match' => [
                                    'fields' => ['username', 'body'],
                                    'query' => $query,
                                ],
                            ],
                            [
                                'range' => [
                                    "created_at" => [
                                        "gte" => $date,
                                        "format" => "dd-MM-yyyy"
                                    ],
                                ],
                            ]
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
