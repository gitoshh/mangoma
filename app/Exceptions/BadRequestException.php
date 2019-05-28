<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class BadRequestException extends GeneralException
{
    protected $httpErrorCode = Response::HTTP_BAD_REQUEST;
}
