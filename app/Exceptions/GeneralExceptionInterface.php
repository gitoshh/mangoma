<?php


namespace App\Exceptions;


interface GeneralExceptionInterface
{
    /**
     * Returns reportable status.
     *
     * @return bool
     */
    public function reports();

    /**
     * Get extra parameters
     *
     * @return array
     */
    public function getParams();

    /**
     * Get the error code
     *
     * @return string
     */
    public function getErrorCode();

    /**
     * Get the associated error code
     *
     * @return int
     */
    public function getHttpErrorCode();

}