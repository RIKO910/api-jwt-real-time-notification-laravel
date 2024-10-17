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
                'code' => 'required|string|exists:reset_code_passwords,code',
            ]);

            Log::info('Request data: ', $request->all());
            $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

            if (!$passwordReset) {
                return response(['message' => trans('passwords.code_not_found')], 404);
            }

            if ($passwordReset->created_at < now()->subHour()) {
                $passwordReset->delete();
                return response(['message' => 'Correct Code'], 422);
            }

            return response([
                'code' => $passwordReset->code,
                'message' => trans('Cade is valid')
            ], 200);

        } catch (Exception $e) {
            Log::error('Error in code check: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }
}
