<?php

namespace App\Jobs;

use App\Mail\NewPostEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendNewPostEmail implements ShouldQueue
{
    use Queueable;

    public $incoming;

    public function __construct($incoming)
    {
        $this->incoming = $incoming;
    }
    
    public function handle(): void
    {
        Mail::to($this->incoming['sendTo'])->send(new NewPostEmail([
                'name'  => $this->incoming['name'],
                'title' => $this->incoming['title'],
            ])
        );
    }
}
