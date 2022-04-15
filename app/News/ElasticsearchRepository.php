<?php

namespace App\News;

use App\Models\News;
use Elasticsearch\Client;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;

class ElasticsearchRepository implements NewsRepository
{
    /**
     * @var Client
     */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function search(string $query = ''): Collection
    {
        $items = $this->searchOnElasticsearch($query);

        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(string $query = ''): array
    {
        $model = new News;

        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'multi_match' => [
                                    'fields' => ['title', 'body','source'],
                                    'query' => $query,
                                ],
                            ],
                            [
                                'range' => [
                                    "created_at" => [
                                        "gte" => '23-01-2023',
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

        return News::findMany($ids)
            ->sortBy(function ($news) use ($ids) {
                return array_search($news->getKey(), $ids);
            });
    }
}
