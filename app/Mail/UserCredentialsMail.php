<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class UserCredentialsMail extends Mailable
{
    public function __construct(
        public $user,
        public $password
    ) {}

    public function build()
    {
        return $this->subject('User Account Credentials')
            ->view('emails.user-credentials');
    }
}