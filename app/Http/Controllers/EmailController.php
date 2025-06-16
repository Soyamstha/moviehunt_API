<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\WelcomeMailSender;
class EmailController extends Controller
{
    function emailsend()
    {
        $user = auth()->user();
        $name = $user->name();
        $toemailaddress= $user->email();
        $welcome_message="Hey ,".$name ."welcome to MovieHunt . I hope You enjoy the Free Movie";
        Mail::to($toemailaddress)->send(new WelcomeMailSender($welcome_message));
    }
}
