<?php

namespace App\Exceptions;

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class GeneralException extends Exception implements GeneralExceptionInterface
{
    protected $params;
    protected $reports = false;
    protected $errorCode;
    protected $httpErrorCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * Service Exception Constructor.
     *
     * @param string         $message   Description of the error
     * @param string         $errorCode 6 character error code
     * @param bool           $reports   Reporting flag
     * @param array          $params    Array of params to log. Only used if reports set to true
     * @param int            $code      Exception Code
     * @param Exception|null $previous  Previous Exception
     */
    public function __construct(
        $message = null,
        $errorCode = null,
        $reports = false,
        $params = [],
        $code = 0,
        Exception $previous = null)
    {
        $this->params = $params;
        $this->reports = $reports;
        $this->errorCode = $errorCode;

        parent::__construct($message, $code, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function reports()
    {
        return $this->reports;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpErrorCode()
    {
        return $this->httpErrorCode;
    }
}
