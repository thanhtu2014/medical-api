<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    protected function sendResetLinkResponse(Request $request, $response)
    {
        $response = ['message' => "Password reset email sent"];
        return response($response, 200);
    }
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        $response = "Email could not be sent to this email address";
        return response($response, 500);
    }
}
