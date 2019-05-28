<?php

use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Response;

if (!function_exists('public_path')) {

    /**
     * Helper function to create a public path.
     *
     * @param $path
     *
     * @return string
     */
    function public_path($path): string
    {
        return rtrim(app()->basePath('public/'.$path), '/');
    }
}

if (!function_exists('generate_pdf')) {

    /**
     * Helper function to create a public path.
     *
     * @param $name
     * @param $data
     * @param $downloadName
     *
     * @return Response
     */
    function generate_pdf(string $name, $data, string $downloadName)
    {
        $pdf = PDF::loadFile($name, $data);

        return $pdf->download($downloadName);
    }
}
