<?php

namespace App\Notifications;

use Abuhawwa\Textlocal\TextlocalChannel;
use Abuhawwa\Textlocal\TextlocalMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Registered extends Notification implements ShouldQueue
{
    use Queueable;

    protected $smsMsg;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($smsMsg)
    {
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
        return $notifiable->isVerified() ? [TextlocalChannel::class] : ['mail', TextlocalChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $firstName = $notifiable->first_name;
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

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Abuhawwa\Textlocal\Message
     */
    public function toTextlocal($notifiable)
    {
        return (new TextlocalMessage())
            ->content($notifiable->isVerified() ? 'Your OTP to reset password ' . $this->smsMsg : 'Your OTP for registration ' . $this->smsMsg);
    }
}
