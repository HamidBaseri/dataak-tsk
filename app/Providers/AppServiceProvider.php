<?php

namespace App\Providers;

use App\Tweets;
use App\Instagrams;
use App\News;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Tweets\TweetsRepository::class, function () {
            // This is an example that We turn-off our search system and use usual eloquent queries
            if (! config('services.search.enabled')) {
                return new Tweets\EloquentRepository();
            }

            return new Tweets\ElasticsearchRepository(
                $this->app->make(Client::class)
            );
        });
        $this->app->bind(Instagrams\InstagramsRepository::class, function () {
            return new Instagrams\ElasticsearchRepository(
                $this->app->make(Client::class)
            );
        });
        $this->app->bind(News\NewsRepository::class, function () {
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
