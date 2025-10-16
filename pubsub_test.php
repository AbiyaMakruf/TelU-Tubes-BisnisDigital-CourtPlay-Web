<?php

use Google\Cloud\PubSub\MessageBuilder;
use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Support\Facades\Log;

if (!function_exists('publish_message')) {
    function publish_message(string $message): bool
    {
        try {
            $pubsub = new PubSubClient([
                'projectId'   => env('PROJECT_ID'),
                'keyFilePath' => base_path(env('GOOGLE_APPLICATION_CREDENTIALS')),
            ]);

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

            Log::info("✅ Pub/Sub message published successfully to '{$topicName}'");
            return true;

        } catch (\Throwable $e) {
            Log::error('❌ Pub/Sub publish failed: ' . $e->getMessage());
            return false;
        }
    }
}
