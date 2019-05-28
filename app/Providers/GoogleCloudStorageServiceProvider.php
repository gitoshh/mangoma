<?php

namespace App\Providers;

use App\Services\GoogleCloudStorage\GoogleStorageAdapter;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class GoogleCloudStorageServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $factory = $this->app->make('filesystem');
        /* @var FilesystemManager $factory */
        $factory->extend('gcs', static function ($app, $config) {
            $storageClient = new StorageClient([
                'projectId'   => $config['project_id'],
                'keyFilePath' => Arr::get($config, 'key_file'),
            ]);
            $bucket = $storageClient->bucket($config['bucket']);
            $pathPrefix = Arr::get($config, 'path_prefix');
            $storageApiUri = Arr::get($config, 'storage_api_uri');
            $adapter = new GoogleStorageAdapter($storageClient, $bucket, $pathPrefix, $storageApiUri);

            return new Filesystem($adapter);
        });
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        //
    }
}
