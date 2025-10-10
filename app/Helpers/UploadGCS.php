<?php

use Google\Cloud\Storage\StorageClient;

if (!function_exists('upload_object')) {
    function upload_object(string $bucketName, string $objectName, string $source, string $keyFilePath): string
    {
        $storage = new StorageClient([
            'keyFilePath' => $keyFilePath
        ]);

        if (!$file = fopen($source, 'r')) {
            throw new \InvalidArgumentException('Unable to open file for reading');
        }

        $bucket = $storage->bucket($bucketName);
        $object = $bucket->upload($file, ['name' => $objectName]);

        return "https://storage.googleapis.com/{$bucketName}/{$objectName}";
    }
}
