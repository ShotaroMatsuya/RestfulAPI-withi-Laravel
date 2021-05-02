<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // //出力にはhtmlではなくplaintextを用いる
        // return $this->text('emails.welcome');
        // // $userをviewテンプレートファイルに明示的にpassしなくても
        // // public propertyとしてconstructorで定義すると、laravleは自動的にviewにuserを渡してくれる

        return $this->markdown('emails.welcome')->subject('Please confirm your account');
    }
}
