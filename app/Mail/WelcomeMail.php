<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $_user)
    {
        $this->user = $_user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user.welcome')
        ->from('hello@handyseam.com', 'Fortune From HandySeam')
        ->subject('Welcome To The HandySeam')->replyTo('no-reply@handyseam.com', 'HandySeam'); 
    }
}
