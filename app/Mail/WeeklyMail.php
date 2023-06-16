<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $weekly_info;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $_user, $_weekly_info)
    {
        $this->user = $_user;
        $this->weekly_info = $_weekly_info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user.weekly-digest')
        ->from('hello@handyseam.com', 'HandySeam')
        ->subject('Your Weekly Handyseam Digest')->replyTo('no-reply@handyseam.com', 'HandySeam'); 
    }
}
