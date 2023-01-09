<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SetSessionTimeout
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $date = $event->date;
        session(['date' => $date]);
        // В нули сессия сбрасывается
        $lifetime = intdiv(strtotime('tomorrow') - time(), 60) - (8 * 60);
        config(['session.lifetime' => $lifetime]);
    }
}
