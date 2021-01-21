<?php

namespace App\Console\Commands;

use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Console\Command;

class PubSub extends Command
{
    protected $signature = 'pubsub:pull
                                    {topic : Name of the topic that should be picked up the messages}
                                    {--no-delete : Do not delete the message after reading}';

    protected $description = 'Command description';

    public function handle(PubSubClient $pubSub)
    {
        $subscription = $pubSub->subscription($this->argument('topic'));

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
