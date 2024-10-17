<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistered322e32 extends Notification
{
    use Queueable;

    protected $user;


    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['broadcast'];
    }


    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "New user registered: {$this->user->name} ({$this->user->email}) at " . now()->format('h:i A'),
        ]);
    }
}
