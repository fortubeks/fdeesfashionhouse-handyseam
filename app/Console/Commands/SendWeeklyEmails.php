<?php

namespace App\Console\Commands;

use App\Mail\WeeklyMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SendWeeklyEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:weeklyemails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to all the verified users of their weekly handyseam update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    { 
        $users = User::where('email_verified_at', '!=', null)->where('user_type','admin')->get();
        //$users = User::where('email', '=', 'ja@ja.com')->where('user_type','admin')->get();

        foreach($users as $user){
            if (filter_var($user->email, FILTER_VALIDATE_EMAIL)){
                
                $domain = substr($user->email, strrpos($user->email, '@') + 1);
                $isValid = checkdnsrr($domain, 'MX');
                if ($isValid) {
                    try{
                        Mail::to($user->email)
                        ->send(new WeeklyMail($user,$user->getWeeklyInfo()));
                    }
                    
                    catch(\Exception $e){
                        error_log('Email not found'.$user->email);
                    }
                }                
            }
        }
    }
}
