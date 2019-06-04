<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends Exception
{
    protected $httpErrorCode = Response::HTTP_NOT_FOUND;
}
