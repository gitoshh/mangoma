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

if (!function_exists('edit_uploaded_file_location')) {

    /**
     * Replaces filename with unique name on the file path.
     *
     * @param string $path
     * @param string $uniqueName
     *
     * @return string
     */
    function edit_uploaded_file_location(string $path, string $uniqueName): string
    {
        $newPath = explode('/', $path);
        array_pop($newPath);
        $newPath[] = $uniqueName;

        return implode('/', $newPath);
    }
}
