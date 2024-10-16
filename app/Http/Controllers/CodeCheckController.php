<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use Illuminate\Support\Facades\Log;
use Exception;

class CodeCheckController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string|exists:reset_code_passwords,code', // Ensure the check is on the 'code' column
            ]);

            // Log request data
            Log::info('Request data: ', $request->all());

            // Find the code
            $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

            // Check if the code exists
            if (!$passwordReset) {
                return response(['message' => trans('passwords.code_not_found')], 404);
            }

            // Check if the code has expired (time is one hour)
            if ($passwordReset->created_at < now()->subHour()) {
                $passwordReset->delete();
                return response(['message' => trans('passwords.code_is_expire')], 422);
            }

            return response([
                'code' => $passwordReset->code,
                'message' => trans('passwords.code_is_valid')
            ], 200);
        } catch (Exception $e) {
            Log::error('Error in code check: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }
}
