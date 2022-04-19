<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TweetTest extends TestCase
{
    private $getTweetRoutes;
    private $createTweetParameterSets;

    protected function setUp(): void
    {
        parent::setUp();


        $this->getTweetRoutes = [
            'validRoutes' => [
                '/api/tweets',
                '/api/tweets?q=hamid.baseri&date=23-12-2020',
            ],
            'notValidRoutes' => [
                '/api/tweets?q',
            ],
            'noResponseRoutes' => [
                '/api/tweets?q=hamid.baseri&date=23-01-2024',
            ]
        ];
        $this->createTweetParameterSets = [
            'validParameters' => [
                [
                    'username' => 'hamid.baseri',
                    'body' => 'This is some text to test create tweet',
                    'retweets' => '2',
                    'avatarImage' => UploadedFile::fake()->image('avatar.jpg'),
                ],
            ],
            'notValidParameters' => [
                [
                    'body' => 'This is some text to test create tweet',
                    'retweets' => 2,
                    'avatarImage' => UploadedFile::fake()->image('avatar.jpg'),
                ],
                [
                    'username' => 'hamid.baseri',
                    'retweets' => 2,
                    'avatarImage' => UploadedFile::fake()->image('avatar.jpg'),
                ],
                [
                    'username' => 'hamid.baseri',
                    'body' => 'This is some text to test create tweet',
                    'avatarImage' => UploadedFile::fake()->image('avatar.jpg'),
                ],
                [
                    'username' => 'hamid.baseri',
                    'body' => 'This is some text to test create tweet',
                    'retweets' => 2,
                ],
            ],
        ];
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_tweet_example()
    {
        foreach ($this->createTweetParameterSets['validParameters'] as $createTweetParameterSet) {

            $response = $this->json('POST', '/api/tweets', $createTweetParameterSet);
            $response->assertStatus(200);
            $response->assertJsonStructure([
                    "body",
                    "username",
                    "retweets",
                    "avatar",
                    "updated_at",
                    "created_at",
                    "id"
                ]
            );
        }
        foreach ($this->createTweetParameterSets['notValidParameters'] as $createTweetParameterSet) {
            $response = $this->json('POST', 'api/tweets', $createTweetParameterSet);
            $response->assertStatus(400);
        }

    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_tweet_example()
    {
        foreach ($this->getTweetRoutes['validRoutes'] as $getTweetRoute) {

            $response = $this->get($getTweetRoute);
            $response->assertStatus(200);
            $response->assertJsonStructure([
                '*' => [
                    "id",
                    "body",
                    "username",
                    "retweets",
                    "image",
                    "avatar",
                    "created_at",
                    "updated_at"
                ]
            ]);
        }
        foreach ($this->getTweetRoutes['noResponseRoutes'] as $getTweetRoute) {

            $response = $this->get($getTweetRoute);
            $response->assertStatus(200);
            $response->assertJsonCount(0);
        }
        foreach ($this->getTweetRoutes['notValidRoutes'] as $getTweetRoute) {
            $response = $this->get($getTweetRoute);
            $response->assertStatus(200);
            $response->assertJsonCount(0);
        }

    }
}
