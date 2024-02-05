<?php

namespace App\Jobs;

use App\Models\Channel;
use App\Models\Message;
use App\Models\Provider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Provider $provider;
    public Channel $channel;
    public string $message;

    public function __construct($provider, $channel, $message)
    {
        $this->provider = $provider;
        $this->channel = $channel;
        $this->message = $message;
    }

    public function handle(): void
    {
        Message::send($this->provider, $this->channel, $this->message);
    }
}
