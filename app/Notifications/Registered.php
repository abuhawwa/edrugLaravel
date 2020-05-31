<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Registered extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $smsMsg;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $smsMsg)
    {
        $this->user = $user;
        $this->smsMsg = $smsMsg;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return is_null($notifiable->mobile_verified_at) ? ['mail'] : null;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $firstName = $this->user->first_name;
        return (new MailMessage)
            ->subject('Welcome to eDrug!')
            ->markdown('mail.Registered', ['firstName' => $firstName]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
