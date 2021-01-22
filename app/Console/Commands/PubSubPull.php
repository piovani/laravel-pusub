<?php

namespace App\Console\Commands;

use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Console\Command;

class PubSubPull extends Command
{
    protected $signature = 'pubsub:pull
                                    {topic : Name of the topic that should be picked up the messages}
                                    {--no-delete : Do not delete the message after reading}';

    protected $description = 'Retrieve messages from the informed topic';

    public function handle(PubSubClient $pubSub)
    {
        $nameTopic = $this->argument('topic');
        $topic = $pubSub->topic($nameTopic);

        if (!$topic->exists()) {
            $topic->create();
            $subscription = $topic->subscribe($nameTopic);
        } else {
            $subscription = $topic->subscription($nameTopic);
        }

        while (true) {
            $messages = $subscription->pull(['returnImmediately' => true]);

            if (empty($messages)) {
                sleep(1);
            }

            foreach ($messages as $message) {
                if (!$this->option('no-delete')) {
                    $subscription->acknowledge($message);
                }

                $this->info($message->data());
            }
        }

        return 0;
    }
}
