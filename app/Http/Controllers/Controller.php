<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @var Request
     */
    public $request;

    /**
     * Controller constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Returns a request payload value given the key.
     *
     * @param $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->request->get($key);
    }
}
