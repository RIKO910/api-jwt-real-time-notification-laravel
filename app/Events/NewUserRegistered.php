<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Facades\Log;

class NewUserRegistered implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new Channel('admin.notifications');
    }

    public function broadcastAs()
    {
        return 'new-user-registered';
    }



    public function broadcastWith()
    {
        $data = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            // Add any additional user attributes you wish to send
        ];

        Log::info('Broadcasting data:', $data); // Check what data is being sent
        return $data;
    }
}
