<?php

namespace App\Console\Commands;

use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PubSubPush extends Command
{
    protected $signature = 'pubsub:push
                                    {topic : Topic name where messages should be sent}
                                    {--message= : Text message that must be sent}
                                    {--path= : Path of the JSON file that must be sent}';

    protected $description = 'Send messages to the topic';

    public function handle(PubSubClient $pubSub)
    {
        $topic = $this->argument('topic');
        $message = $this->option('message');
        $path = $this->option('path');

        if (!$this->validation($message, $path)) {
            return 1;
        }

        try {
            $pubSub->topic($topic)->publish([
                'data' => $this->getData($message, $path),
            ]);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }

    private function validation($message, $path): bool
    {
        if (empty($message) && empty($path)) {
            $this->error('It is necessary to inform the message or the path');
            return false;
        }

        if (!empty($message) && !empty($path)) {
            $this->error('Only need to enter one, path or message');
            return false;
        }

        return true;
    }

    private function getData($message, $path)
    {
        if ($message) return $message;

        try {
            $content = Storage::get('public/' . $path);
            return $content;

        } catch (\Exception $e) {
            $this->error($e);
            exit();
        }
    }
}
