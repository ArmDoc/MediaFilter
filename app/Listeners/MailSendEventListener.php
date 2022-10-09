<?php

namespace App\Listeners;

use App\Events\FilterMediaEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\FiltrationCompleted;

class MailSendEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\FilterMediaEvent  $event
     * @return void
     */
    public function handle(FilterMediaEvent $event)
    {
      Mail::to(env('MAIL_FROM_ADDRESS'))->send(new FiltrationCompleted($event->result));
    }
}
