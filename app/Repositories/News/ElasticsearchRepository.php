<?php

namespace App\Repositories\News;

use App\Interfaces\NewsRepository;
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

    public function search(array $params): Collection
    {
        $items = $this->searchOnElasticsearch($params);

        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(array $params): array
    {
        $model = new News;
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
                                                'fields' => ['title', 'body', 'source'],
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
                        ],
                    ],
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
