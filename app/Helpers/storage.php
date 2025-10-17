<?php

use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Log;

if (!function_exists('gcs_extract_object_path')) {
    /**
     * Ambil object path relatif bucket dari public URL atau path mentah.
     * Contoh:
     *  https://storage.googleapis.com/<bucket>/uploads/profile_pictures/1/a.jpg
     *    -> uploads/profile_pictures/1/a.jpg
     *  https://<bucket>.storage.googleapis.com/uploads/profile_pictures/1/a.jpg
     *    -> uploads/profile_pictures/1/a.jpg
     *  https://cdn.example.com/uploads/profile_pictures/1/a.jpg
     *    -> uploads/profile_pictures/1/a.jpg
     *  uploads/profile_pictures/1/a.jpg
     *    -> uploads/profile_pictures/1/a.jpg
     */
    function gcs_extract_object_path(string $urlOrPath): ?string
    {
        $v = trim($urlOrPath);

        // Jika sudah berupa path relatif
        if (!preg_match('#^https?://#i', $v)) {
            return ltrim($v, '/');
        }

        // Pola: https://storage.googleapis.com/<bucket>/<object>
        if (preg_match('#storage\.googleapis\.com/[^/]+/(.+)$#i', $v, $m)) {
            return ltrim($m[1], '/');
        }

        // Pola: https://<bucket>.storage.googleapis.com/<object>
        if (preg_match('#://[^/]+\.storage\.googleapis\.com/(.+)$#i', $v, $m)) {
            return ltrim($m[1], '/');
        }

        // Pola custom domain/CDN: ambil path setelah domain
        $parsed = parse_url($v);
        if (!empty($parsed['path'])) {
            return ltrim($parsed['path'], '/');
        }

        return null;
    }
}

if (!function_exists('delete_object')) {
    /**
     * Hapus object di GCS berdasarkan public URL atau relative path.
     *
     * @param  string $urlOrPath  Public URL (GCS/CDN) atau path relatif di bucket.
     * @return bool               True jika dihapus/dianggap selesai; false jika gagal.
     */
    function delete_object(string $urlOrPath): bool
    {
        try {
            $bucketName = env('GCS_BUCKET');
            $keyPathEnv = env('GOOGLE_APPLICATION_CREDENTIALS');

            if (!$bucketName) {
                Log::error('GCS delete: GCS_BUCKET not defined in .env');
                return false;
            }
            if (!$keyPathEnv) {
                Log::error('GCS delete: GOOGLE_APPLICATION_CREDENTIALS not defined in .env');
                return false;
            }

            $keyFilePath = base_path($keyPathEnv);
            $objectPath  = gcs_extract_object_path($urlOrPath);

            if (!$objectPath) {
                Log::warning('GCS delete: cannot extract object path from input', ['input' => $urlOrPath]);
                return false;
            }

            $client = new StorageClient([
                'projectId'   => env('PROJECT_ID'),
                'keyFilePath' => $keyFilePath,
            ]);

            $bucket = $client->bucket($bucketName);
            if (!$bucket->exists()) {
                Log::error("GCS delete: bucket '{$bucketName}' not found");
                return false;
            }

            $object = $bucket->object($objectPath);
            if (!$object->exists()) {
                Log::info('GCS delete: object not found (treated as deleted)', ['object' => $objectPath]);
                return true; // idempotent: dianggap sudah tidak ada
            }

            $object->delete();
            Log::info('GCS delete: object removed', ['bucket' => $bucketName, 'object' => $objectPath]);
            return true;

        } catch (\Throwable $e) {
            Log::error('GCS delete failed: ' . $e->getMessage(), ['input' => $urlOrPath]);
            return false;
        }
    }
}
