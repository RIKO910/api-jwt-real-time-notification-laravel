<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class ResetPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);
        Log::info('Request data: ', $request->all());


        // check if it has expired (time is one hour)
        if ($passwordReset->created_at < now()->subHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        // find user's email
        $user = User::firstWhere('email', $passwordReset->email);

        // update user password
        $user->update(['password' => bcrypt($request->password)]);

        // delete the current code
        $passwordReset->delete();

        return response(['message' => 'Password has been successfully reset'], 200);
    }

    public function showResetCodeView($code)
    {
        // Render the Blade view and pass the code to it
        return view('emails.reset-code', ['code' => $code]);
    }
}
