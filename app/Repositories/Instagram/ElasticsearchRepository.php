<?php

namespace App\Repositories\Instagram;

use App\Interfaces\InstagramRepository;
use App\Models\Instagram;
use Elasticsearch\Client;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;

class ElasticsearchRepository implements InstagramRepository
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
        $model = new Instagram;

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
                                                'fields' => ['title', 'name', 'username', 'body'],
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

        return Instagram::findMany($ids)
            ->sortBy(function ($instagram) use ($ids) {
                return array_search($instagram->getKey(), $ids);
            });
    }
}
