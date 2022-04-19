<?php

namespace App\Providers;

use App\Interfaces\InstagramRepository;
use App\Interfaces\NewsRepository;
use App\Interfaces\TweetsRepository;
use App\Repositories\Tweets\EloquentRepository;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Tweets;
use App\Repositories\Instagram;
use App\Repositories\News;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TweetsRepository::class, function () {
            // This is an example that We turn-off our search system and use usual eloquent queries
            if (! config('services.search.enabled')) {
                return new EloquentRepository();
            }

            return new Tweets\ElasticsearchRepository(
                $this->app->make(Client::class)
            );
        });
        $this->app->bind(InstagramRepository::class, function () {
            return new Instagram\ElasticsearchRepository(
                $this->app->make(Client::class)
            );
        });
        $this->app->bind(NewsRepository::class, function () {
            return new News\ElasticsearchRepository(
                $this->app->make(Client::class)
            );
        });

        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))
                ->build();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
