<?php

namespace App\Search;

use Elasticsearch\Client;

class ElasticsearchObserver
{
    /**
     * @var \Elasticsearch\Client
     */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function saved($model)
    {
        $this->elasticsearch->index([
            'index' => $model->getSearchIndex(),
            'type' => '_doc',
            'id' => $model->getKey(),
            'body' => $model->toSearchArray(),
        ]);
    }

    public function deleted($model)
    {
        $this->elasticsearch->delete([
            'index' => $model->getSearchIndex(),
            'type' => '_doc',
            'id' => $model->getKey(),
        ]);
    }
}
