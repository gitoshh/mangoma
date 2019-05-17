<?php

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
