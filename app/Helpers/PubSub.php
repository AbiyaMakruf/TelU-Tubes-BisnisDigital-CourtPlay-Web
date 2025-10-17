<?php

use Google\Cloud\PubSub\MessageBuilder;
use Google\Cloud\PubSub\PubSubClient;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\Cache\MemoryCacheItemPool;
use Illuminate\Support\Facades\Log;

if (!function_exists('publish_message')) {
    /**
     * Publish message to Google Pub/Sub
     *
     * Works both in local (using service-account key)
     * and in Cloud Run (using attached service account).
     */
    function publish_message(string $message): bool
    {
        $projectId   = env('PROJECT_ID');
        $keyFilePath = base_path(env('GOOGLE_APPLICATION_CREDENTIALS', ''));
        $topicName   = env('TOPIC_ID');

        try {
            if (!$projectId) {
                throw new \Exception('PROJECT_ID not defined in .env');
            }
            if (!$topicName) {
                throw new \Exception('TOPIC_ID not defined in .env');
            }

            $options = ['projectId' => $projectId];
            $usingKey = false;

            /**
             * LOCAL MODE (Docker/local dev)
             * If key file exists, use it for auth
             */
            if (file_exists($keyFilePath)) {
                $keyFileContent = file_get_contents($keyFilePath);
                if ($keyFileContent === false) {
                    throw new \Exception("Failed to read key file at: {$keyFilePath}");
                }

                $decoded = json_decode($keyFileContent, true);
                if (!$decoded || !isset($decoded['client_email'])) {
                    throw new \Exception("Invalid or corrupted service account JSON at {$keyFilePath}");
                }

                // Force environment so Google SDK doesn't fall back to ADC
                putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $keyFilePath);

                // Create credential manually
                $credentials = new ServiceAccountCredentials(
                    ['https://www.googleapis.com/auth/pubsub'],
                    $decoded
                );

                $options['credentials'] = $credentials;
                $options['authCache']   = new MemoryCacheItemPool();
                $usingKey = true;
            }

            /**
             * CLOUD RUN MODE (no key file)
             * Google SDK will automatically use the attached Service Account
             */
            $pubsub = new PubSubClient($options);
            $topic  = $pubsub->topic($topicName);

            if (!$topic->exists()) {
                throw new \Exception("Pub/Sub topic '{$topicName}' not found in project '{$projectId}'");
            }

            $topic->publish(
                (new MessageBuilder)
                    ->setData($message)
                    ->build()
            );

            Log::info("✅ Pub/Sub message published successfully to '{$topicName}'", [
                'project' => $projectId,
                'topic'   => $topicName,
                'usingKeyFile' => $usingKey,
            ]);

            return true;

        } catch (\Throwable $e) {
            Log::error('❌ Pub/Sub publish failed', [
                'message'      => $e->getMessage(),
                'exception'    => get_class($e),
                'file'         => $e->getFile(),
                'line'         => $e->getLine(),
                'trace'        => $e->getTraceAsString(),
                'projectId'    => $projectId,
                'topic'        => $topicName,
                'keyFilePath'  => $keyFilePath,
                'file_exists'  => file_exists($keyFilePath),
                'file_readable'=> is_readable($keyFilePath),
                'cwd'          => getcwd(),
                'user'         => get_current_user(),
            ]);

            return false;
        }
    }
}
