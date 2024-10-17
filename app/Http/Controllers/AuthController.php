<?php

namespace App\Http\Controllers;

use App\Events\NewUserRegistered;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    // Register for users and admins
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:user,admin',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        event(new NewUserRegistered($user));
        Log::info('Event dispatched: NewUserRegistered', [$user]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    // Logout
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    // Password reset request
    public function forgotPassword(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email|exists:users',
            ]);

            Log::info('Request data: ', $request->all());
            ResetCodePassword::where('email', $request->email)->delete();
            $data['code'] = mt_rand(100000, 999999);
            $codeData     = ResetCodePassword::create($data);

            // Send email to user with the reset code in HTML format
            Mail::send('emails.reset_password', ['code' => $codeData->code], function($message) use ($request) {
                $message->to($request->email)->subject('Password Reset');
            });

            return response(['message' => "Reset code sent successfully"], 200);
        } catch (Exception $e) {
            Log::error('Error in forgot-password: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }

    // JWT token response
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
