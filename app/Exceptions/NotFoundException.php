<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends GeneralException
{
    protected $httpErrorCode = Response::HTTP_NOT_FOUND;
}
