<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class BadRequestException extends Exception
{
    protected $httpErrorCode = Response::HTTP_BAD_REQUEST;
}
