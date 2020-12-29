<?php

namespace App\Events;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserEvent extends Event implements ShouldBroadcast
{
    use InteractsWithSockets;

    /**
     * Event type
     *
     * @var string
     */
    private $type;

    /**
     * Event data User
     *
     * @var App\Model\User $user
     */
    private $user;

    /**
    * Create a new event instance.
    *
    * @param string $type Event type
    * @param \App\Model\User $user
    * @return void
    */
    public function __construct($type, User $user)
    {
        $this->type = $type;
        $this->user = $user;
    }

    /**
     * Broadcast with data
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'id'       => $this->user->id,
            'name'     => $this->user->name,
            'username' => $this->user->email,
            'action'   => ucfirst(strtolower($this->type)),
            'broadcast_time'       => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
    * Get the channels the event should be broadcast on.
    *
    * @return \Illuminate\Broadcasting\Channel
    */
    public function broadcastOn()
    {
        return new Channel('users-event-channel');
    }

   /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'UserEvent';
    }
}
