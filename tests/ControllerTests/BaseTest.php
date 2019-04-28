<?php


class BaseTest extends TestCase
{
    /**
     * @var array
     */
    public $headers;

    public function setUp(): void
    {
        parent::setUp();
        $this->headers = [
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Test landing page.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
        );
    }

}