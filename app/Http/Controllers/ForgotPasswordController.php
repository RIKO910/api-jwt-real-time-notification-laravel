<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email|exists:users',
            ]);

            // Log request data
            Log::info('Request data: ', $request->all());

            // Delete all old codes for the user
            ResetCodePassword::where('email', $request->email)->delete();

            // Generate a random code
            $data['code'] = mt_rand(100000, 999999);

            // Create a new code record in the database
            $codeData = ResetCodePassword::create($data);

            // Send email to user with the reset code
            Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

            return response(['message' => trans('passwords.sent')], 200);
        } catch (Exception $e) {
            Log::error('Error in forgot-password: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }
}
