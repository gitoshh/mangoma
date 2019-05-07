<?php

use Laravel\Lumen\Application;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->alias(\Illuminate\Http\Request::class, 'request');

        return $app;
    }
}
