<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validate the request input
            $validator = Validator::make($request->all(), [
                'name' => 'string|required',
                'email' => [
                    'unique:users',
                    'required',
                    'email:rfc,dns,filter',
                ],
                'phone_number' => [
                    'required',
                    'unique:users',
                    'regex:/^\+\d{1,3}\d{8,14}$/'
                ],
                'password' => [
                    'required',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,16}$/'
                ]
            ]);

            // Check if the validation fails
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 400);
            }

            // Hash the password
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);

            // Create the user
            $user = User::create($input);

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $user
            ], 201);

        } catch (\Exception $err) {
            // Log the error
            Log::error($err->getMessage());

            // Return an error response
            return response()->json([
                'success' => false,
                'message' => 'User registration failed',
                'error' => $err->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email:rfc,dns,filter',
            ],
            'password' => 'required',
        ]);

        // Check if validation fails
        $message = 'Validation failed';

        if ($validator->fails()) {
            return ApiResponses::send($message, 400, $validator->errors());
        }

        $input = $request->only(['email', 'password']);

        // Find the user by email
        $user = User::where('email', $input['email'])->first();

        // Check if the user exists
        if (!$user) {
            return ApiResponses::send($message, 400, 'Email not registered');
        }

        // Check if the password is correct
        if (!Hash::check($input['password'], $user->password)) {
            return ApiResponses::send($message, 400, 'Invalid password');
        }

        $message = 'Successfully login to your account';
        return ApiResponses::send($message, 200);
    }


    // public function get_email_verified(String $email){

    //     if (empty($email)) {

    //     }

    //     return response()->json(['message' => 'The user has been verified'], 200);

    // }


    public function get_phone_verified(){

    }

}
