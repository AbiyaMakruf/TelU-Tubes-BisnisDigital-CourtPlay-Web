<?php

namespace App\Helpers;

use Google\Cloud\PubSub\MessageBuilder;
use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Support\Facades\Log;

class PubSubHelper
{
    public static function publish_message(string $message)
    {
        try {
            $pubsub = new PubSubClient([
                'projectId' => env('PROJECT_ID'),
                'keyFilePath' => storage_path('app/keys/courtplay-gcs-key.json'),
            ]);

            $topic = $pubsub->topic(env('TOPIC_ID'));

            $topic->publish(
                (new MessageBuilder)
                    ->setData($message)
                    ->build()
            );

            Log::info('Pub/Sub message published successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Pub/Sub publish failed: ' . $e->getMessage());
            return false;
        }
    }
}
