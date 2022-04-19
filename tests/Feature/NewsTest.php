<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NewsTest extends TestCase
{
    private $getNewsRoutes;
    private $createNewsParameterSets;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getNewsRoutes = [
            'validRoutes' => [
                '/api/news',
                '/api/news?q=something&date=23-12-2020',
            ],
            'notValidRoutes' => [
                '/api/news?q',
            ],
            'noResponseRoutes' => [
                '/api/news?q=something&date=23-01-2024',
            ]
        ];
        $this->createNewsParameterSets = [
            'validParameters' => [
                [
                    'title' => 'something',
                    'source' => 'CNN',
                    'body' => 'This is some text to test create news',
                    'src_url' => 'http://example.com/123',
                ]
            ],
            'notValidParameters' => [
                [
                    'source' => 'CNN',
                    'body' => 'This is some text to test create news',
                    'src_url' => 'http://example.com/123',
                ],
                [
                    'title' => 'test news title',
                    'body' => 'This is some text to test create news',
                    'src_url' => 'http://example.com/123',
                ],
                [
                    'title' => 'test news title',
                    'source' => 'CNN',
                    'src_url' => 'http://example.com/123',
                ],
                [
                    'title' => 'test news title',
                    'source' => 'CNN',
                    'body' => 'This is some text to test create news',
                ],
                [
                    'title' => 'test news title',
                    'source' => 'CNN',
                    'body' => 'This is some text to test create news',
                    'src_url' => 'trftyrt',
                ]
            ],
        ];
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_news_example()
    {
        foreach ($this->createNewsParameterSets['validParameters'] as $createNewsParameterSet) {
            $response = $this->json('POST', '/api/news', $createNewsParameterSet);
            $response->assertStatus(200);
            $response->assertJsonStructure([
                    "body",
                    "title",
                    "source",
                    "src_url",
                    "updated_at",
                    "created_at",
                    "id"
                ]
            );
        }
        foreach ($this->createNewsParameterSets['notValidParameters'] as $createNewsParameterSet) {
            $response = $this->json('POST', 'api/news', $createNewsParameterSet);
            $response->assertStatus(400);
        }

    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_news_example()
    {
        foreach ($this->getNewsRoutes['validRoutes'] as $getNewsRoute) {

            $response = $this->get($getNewsRoute);
            $response->assertStatus(200);
            $response->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'source',
                    'body',
                    'src_url',
                    'avatar',
                    'created_at',
                    'updated_at',
                ]
            ]);
        }
        foreach ($this->getNewsRoutes['noResponseRoutes'] as $getNewsRoute) {

            $response = $this->get($getNewsRoute);
            $response->assertStatus(200);
            $response->assertJsonCount(0);
        }
        foreach ($this->getNewsRoutes['notValidRoutes'] as $getNewsRoute) {
            $response = $this->get($getNewsRoute);
            $response->assertStatus(200);
            $response->assertJsonCount(0);
        }

    }
}
