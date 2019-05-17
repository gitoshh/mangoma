<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class NotFoundException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * @param string $msg
     * @return JsonResponse
     */
    public function render(string $msg)
    {
        return response()->json($msg, 404);
    }
}