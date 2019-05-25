<?php

use Illuminate\Support\Facades\Artisan;

class BaseTest extends TestCase
{
    /**
     * @var array
     */
    public $headers;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
        $this->headers = [];
    }

    /**
     * Test landing page.
     *
     * @return void
     */
    public function testExample(): void
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(),
            $this->response->getContent()
        );
    }

    public function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
