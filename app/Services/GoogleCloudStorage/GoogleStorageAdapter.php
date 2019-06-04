<?php

namespace App\Services\GoogleCloudStorage;

use Google\Cloud\Storage\Bucket;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Storage\StorageObject;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use League\Flysystem\Util;

abstract class GoogleStorageAdapter extends AbstractAdapter
{
    /**
     * @var StorageClient
     */
    protected $storageClient;

    /**
     * @var Bucket
     */
    protected $bucket;

    /**
     * GoogleStorageAdapter constructor.
     */
    public function __construct()
    {
        $this->storageClient = new StorageClient([
            'projectId'   => getenv('GOOGLE_CLOUD_PROJECT_ID'),
            'keyFilePath' => getenv('GOOGLE_CLOUD_KEY_FILE'),
        ]);
        $this->setPathPrefix(getenv('GOOGLE_CLOUD_STORAGE_PATH_PREFIX'));
        $this->bucket = $this->storageClient->bucket(getenv('GOOGLE_CLOUD_STORAGE_BUCKET'));
    }

    /**
     * {@inheritdoc}
     */
    public function write($path, $contents, Config $config)
    {
        return $this->upload($path, $contents, $config);
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function update($path, $contents, Config $config)
    {
        return $this->upload($path, $contents, $config);
    }

    /**
     * Returns an array of options from the config.
     *
     * @param Config $config
     *
     * @return array
     */
    protected function getOptionsFromConfig(Config $config): array
    {
        $options = [];
        if ($visibility = $config->get('visibility')) {
            $options['predefinedAcl'] = $this->getPredefinedAclForVisibility($visibility);
        } else {
            // if a file is created without an acl, it isn't accessible via the console
            // we therefore default to private
            $options['predefinedAcl'] = $this->getPredefinedAclForVisibility(AdapterInterface::VISIBILITY_PRIVATE);
        }
        if ($metadata = $config->get('metadata')) {
            $options['metadata'] = $metadata;
        }

        return $options;
    }

    /**
     * Uploads a file to the Google Cloud Storage service.
     *
     * @param string          $path
     * @param string|resource $contents
     * @param Config          $config
     *
     * @return array
     */
    protected function upload($path, $contents, Config $config): array
    {
        $path = $this->applyPathPrefix($path);
        $options = $this->getOptionsFromConfig($config);
        $options['name'] = $config->get('name');
        $options['path'] = $path;
        $object = $this->bucket->upload($contents, $options);

        return $this->normaliseObject($object);
    }

    /**
     * Returns a dictionary of object metadata from an object.
     *
     * @param StorageObject $object
     *
     * @return array
     */
    protected function normaliseObject(StorageObject $object): array
    {
        $name = $this->removePathPrefix($object->name());
        $info = $object->info();
        $isDir = substr($name, -1) === '/';
        if ($isDir) {
            $name = rtrim($name, '/');
        }

        return [
            'type'      => $isDir ? 'dir' : 'file',
            'dirname'   => Util::dirname($name),
            'path'      => $name,
            'timestamp' => strtotime($info['updated']),
            'mimetype'  => $info['contentType'] ?? '',
            'size'      => $info['size'],
        ];
    }

    /**
     * Removes an object from google cloud.
     *
     * @param string $path
     *
     * @return bool
     */
    public function delete($path): bool
    {
        $this->getObject($path)->delete();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($path)
    {
        $object = $this->getObject($path);
        $contents = $object->downloadAsString();
        $data = $this->normaliseObject($object);
        $data['contents'] = $contents;

        return $data;
    }

    /**
     * Returns a storage object for the given path.
     *
     * @param string $path
     *
     * @return StorageObject
     */
    protected function getObject($path): StorageObject
    {
        $path = $this->applyPathPrefix($path);

        return $this->bucket->object($path);
    }

    /**
     * @param string $visibility.
     *
     * @return string
     */
    protected function getPredefinedAclForVisibility($visibility): string
    {
        return $visibility === AdapterInterface::VISIBILITY_PUBLIC ? 'publicRead' : 'projectPrivate';
    }
}
