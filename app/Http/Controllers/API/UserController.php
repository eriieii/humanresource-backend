<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            //validate Request
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            //Find User by Email
            $user = User::where('email', $request->email)
                            ->firstOrFail();
            if(!Hash::check($request->password, $user->password)) {
                throw new Exception('Invalid Password');
            }

            //Generate Token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            //Return Respon
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user 
            ], 'Login Success');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function register(Request $request)
    {
        try {
            // validate Request
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required','unique:users' ,'string', 'email', 'max:255'],
                'password' => ['required', 'string', Password::min(8)],
            ]);

            //Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            //Generate Token
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            
            //Return Response
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Register Success');

        } catch (Exception $e) {
            // Return error response
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        // Revoke Token
        $token = $request->user()->currentAccessToken()->delete();

        // Return response
        return ResponseFormatter::success($token, 'Logout success');
    }

    public function fetch(Request $request)
    {
        // Get user
        $user = $request->user();

        // Return response
        return ResponseFormatter::success($user, 'Fetch success');
    }
}
