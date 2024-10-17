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

        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);
        Log::info('Request data: ', $request->all());

        if ($passwordReset->created_at < now()->subHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        $user = User::firstWhere('email', $passwordReset->email);
        $user->update(['password' => bcrypt($request->password)]);
        $passwordReset->delete();

        return response(['message' => 'Password has been successfully reset'], 200);
    }

    public function showResetCodeView($code)
    {
        return view('emails.reset-code', ['code' => $code]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

}
