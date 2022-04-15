<?php

namespace App\Console\Commands;

use App\Models\Instagram;
use App\Models\News;
use App\Models\Tweet;
use Elasticsearch\Client;

use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all tweets to Elasticsearch';

    /** @var Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
    }

    public function handle()
    {
        $this->info('Indexing all data. This might take a while...');

        // try {
        //     dd('sdfdsfsfsd');
        //     $result = $this->elasticsearch->info();
        // } catch (NoNodesAvailableException $e) {
        //     printf ("NoNodesAvailableException: %s\n", $e->getMessage());
        // }
        // dd($this->elasticsearch->transport->getLastConnection()->getLastRequestInfo());
        // die('hghgh');

//        $hosts = ['elasticsearch:9200'];
//
//        $client = ClientBuilder::create()
//            ->setHosts($hosts)
//            ->build();
//        dd($client->ping());
//        dd('fdfdf');

        foreach (Tweet::cursor() as $tweet) {

            //    try {
            //        $client1 = ClientBuilder::create()
            //            ->setHosts(['elasticsearch:9200'])
            //            ->build();
            //    } catch (AuthenticationException $e) {
            //        dd('auth');
            //    }
            //    dd($client1->ping());
//dd('gfdgdf');
//             try {
//                 $result = $this->elasticsearch->info();
//             } catch (\Exception $e) {
//                 dd($e);
//                 printf ("NoNodesAvailableException: %s\n", $e->getMessage());
//             }
//             dd('eee');
            // dd($tweet->getSearchIndex(),$tweet->getSearchType(),$tweet->getKey(),$tweet->toSearchArray());


            $this->elasticsearch->index([
                'index' => $tweet->getSearchIndex(),
                'type' => '_doc',
                'id' => $tweet->getKey(),
                'body' => $tweet->toSearchArray(),
            ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        foreach (Instagram::cursor() as $instagram) {
            $this->elasticsearch->index([
                'index' => $instagram->getSearchIndex(),
                'type' => '_doc',
                'id' => $instagram->getKey(),
                'body' => $instagram->toSearchArray(),
            ]);

            $this->output->write('*');
        }

        foreach (News::cursor() as $news) {
            $this->elasticsearch->index([
                'index' => $news->getSearchIndex(),
                'type' => '_doc',
                'id' => $news->getKey(),
                'body' => $news->toSearchArray(),
            ]);

            $this->output->write('#');
        }

        $this->info("\nDone!");
    }
}
