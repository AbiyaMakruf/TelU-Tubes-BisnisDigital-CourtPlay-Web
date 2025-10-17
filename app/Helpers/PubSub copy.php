<?php

use Google\Cloud\PubSub\MessageBuilder;
use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Support\Facades\Log;

if (!function_exists('publish_message')) {
    function publish_message(string $message): bool
    {
        // Delete Jika Sudah Berhasil Debug
        $projectId = env('PROJECT_ID');
        $keyFilePath = base_path(env('GOOGLE_APPLICATION_CREDENTIALS'));
        /// BATAS DELETE

        try {
            // Delete Jika Sudah Berhasil Debug
            $keyFileContent = file_get_contents($keyFilePath);
            if ($keyFileContent === false) {
                 throw new \Exception("Failed to read key file at: " . $keyFilePath);
            }
            $pubsub = new PubSubClient([
                'projectId' => $projectId,
                // Menggunakan 'keyFile' dengan array PHP (hasil decode JSON)
                'keyFile'   => json_decode($keyFileContent, true), 
                // keyFilePath DIBUANG
            ]);
            /// BATAS DELETE

            // $pubsub = new PubSubClient([
            //     'projectId'   => $projectId,
            //     'keyFilePath' => $keyFilePath,
            // ]);

            $topicName = env('TOPIC_ID');
            if (!$topicName) {
                Log::error('Pub/Sub: TOPIC_ID not defined in .env');
                return false;
            }

            $topic = $pubsub->topic($topicName);

            if (!$topic->exists()) {
                Log::error("Pub/Sub: Topic '{$topicName}' not found");
                return false;
            }

            $topic->publish(
                (new MessageBuilder)
                    ->setData($message)
                    ->build()
            );

            Log::info("Pub/Sub message published successfully to '{$topicName}'");
            return true;

        } catch (\Throwable $e) {
            // Log::error('Pub/Sub publish failed: ' . $e->getMessage());
            // return false;

            Log::error('Pub/Sub publish failed: ' . $e->getMessage(), [
                'projectId'   => $projectId,
                'keyFilePath' => $keyFilePath,
                'error_file'  => $e->getFile(),
                'error_line'  => $e->getLine(),
            ]);
            return false;
        }
    }
}
