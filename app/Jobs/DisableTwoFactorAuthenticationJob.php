<?php

namespace App\Jobs;

use App\Mail\DisableTwoFactorAuthentication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class DisableTwoFactorAuthenticationJob implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public object $data;

    public function __construct(object $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        Mail::to($this->data->email)->send(new DisableTwoFactorAuthentication($this->data));
    }
}