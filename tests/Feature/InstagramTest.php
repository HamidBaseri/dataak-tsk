<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InstagramTest extends TestCase
{
    private $getInstagramRoutes;
    private $createInstagramParameterSets;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getInstagramRoutes = [
            'validRoutes' => [
                '/api/instagram',
                '/api/instagram?q=something&date=23-12-2020',
            ],
            'notValidRoutes' => [
                '/api/instagram?q',
            ],
            'noResponseRoutes' => [
                '/api/instagram?q=something&date=23-01-2024',
            ]
        ];
        $this->createInstagramParameterSets = [
            'validParameters' => [
                [
                    'title' => 'something',
                    'name' => 'hamid baseri',
                    'username' => 'hamid.baseri',
                    'body' => 'This is some text to test create instagram',
                    'filenames' => [UploadedFile::fake()->image('avatar.jpg')],
                ],
            ],
            'notValidParameters' => [
                [
                    'name' => 'hamid baseri',
                    'username' => 'hamid.baseri',
                    'body' => 'This is some text to test create instagram',
                    'filenames' => [UploadedFile::fake()->image('avatar.jpg')],
                ],
                [
                    'title' => 'something',
                    'username' => 'hamid.baseri',
                    'body' => 'This is some text to test create instagram',
                    'filenames' => [UploadedFile::fake()->image('avatar.jpg')],
                ],
                [
                    'title' => 'something',
                    'name' => 'hamid baseri',
                    'body' => 'This is some text to test create instagram',
                    'filenames' => [UploadedFile::fake()->image('avatar.jpg')],
                ],
                [
                    'title' => 'something',
                    'name' => 'hamid baseri',
                    'username' => 'hamid.baseri',
                    'body' => 'This is some text to test create instagram',
                ],
            ],
        ];
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_instagram_example()
    {
        foreach ($this->createInstagramParameterSets['validParameters'] as $createInstagramParameterSet) {

            $response = $this->json('POST', '/api/instagram', $createInstagramParameterSet);
            $response->assertStatus(200);
            $response->assertJsonStructure([
                    "body",
                    "username",
                    "title",
                    "name",
                    "album_id",
                    "updated_at",
                    "created_at",
                    "id"
                ]
            );
        }
        foreach ($this->createInstagramParameterSets['notValidParameters'] as $createInstagramParameterSet) {
            $response = $this->json('POST', 'api/instagram', $createInstagramParameterSet);
            $response->assertStatus(400);
        }

    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_instagram_example()
    {
        foreach ($this->getInstagramRoutes['validRoutes'] as $getInstagramRoute) {

            $response = $this->get($getInstagramRoute);
            $response->assertStatus(200);
            $response->assertJsonStructure([
                '*' => [
                    "id",
                    "title",
                    "album_id",
                    "body",
                    "name",
                    "avatar",
                    "username",
                    "created_at",
                    "updated_at"
                ]
            ]);
        }
        foreach ($this->getInstagramRoutes['noResponseRoutes'] as $getInstagramRoute) {

            $response = $this->get($getInstagramRoute);
            $response->assertStatus(200);
            $response->assertJsonCount(0);
        }
        foreach ($this->getInstagramRoutes['notValidRoutes'] as $getInstagramRoute) {
            $response = $this->get($getInstagramRoute);
            $response->assertStatus(200);
            $response->assertJsonCount(0);
        }

    }
}
