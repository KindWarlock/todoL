<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TasksIndexPageLoaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $date;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(String $date)
    {
        $this->date = $date;
    }
}
