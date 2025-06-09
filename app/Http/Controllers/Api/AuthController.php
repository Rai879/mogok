<?php

namespace App\Http\Controllers\Api; // IMPORTANT: This namespace is for your API controller

use App\Http\Controllers\Controller; // Base Controller
use App\Models\User; // Your User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // For Auth::attempt (though Sanctum is mostly token-based)
use Illuminate\Support\Facades\Hash; // For password hashing
use Illuminate\Validation\ValidationException; // For validation errors
use Illuminate\Support\Facades\Log; // For debugging, good practice

class AuthController extends Controller // This is your API Auth Controller
{
    /**
     * Handle user registration for API.
     */
    public function register(Request $request)
    {
        Log::info('API Register method started.');

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
            Log::info('API Register validation passed for email: ' . $request->email);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            Log::info('User created via API registration: ' . $user->email);

            // Generate a new API token for the registered user
            $token = $user->createToken('auth_token')->plainTextToken;
            Log::info('API token generated for user: ' . $user->email);

            return response()->json([
                'message' => 'User registered successfully.',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user->only(['id', 'name', 'email']), // Return selected user data
            ], 201); // 201 Created status for successful resource creation

        } catch (ValidationException $e) {
            Log::warning('API Register validation failed: ' . json_encode($e->errors()));
            // Return validation errors in JSON format
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422); // 422 Unprocessable Entity for validation errors
        } catch (\Exception $e) {
            Log::error('API Register method failed: ' . $e->getMessage() . ' on line ' . $e->getLine());
            // Return a generic error for other exceptions
            return response()->json([
                'message' => 'Registration failed due to a server error.',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Handle user login for API.
     */
    public function login(Request $request)
    {
        Log::info('API Login method started.');

        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            Log::info('API Login validation passed for email: ' . $request->email);

            // Attempt to authenticate using web guard first, then api
            // For API, we mostly rely on checking credentials directly as Auth::attempt might use session by default.
            // However, Sanctum's Auth::attempt should work for plain token authentication.
            if (!Auth::attempt($request->only('email', 'password'))) {
                Log::warning('API Login failed: Invalid credentials for email: ' . $request->email);
                throw ValidationException::withMessages([
                    'email' => ['These credentials do not match our records.'],
                ]);
            }

            $user = Auth::user(); // Get the authenticated user
            Log::info('User authenticated via API login: ' . $user->email);

            // Delete old tokens for security (optional, but good practice)
            $user->tokens()->where('name', 'auth_token')->delete();

            // Create a new API token
            $token = $user->createToken('auth_token')->plainTextToken;
            Log::info('New API token generated for login: ' . $user->email);

            return response()->json([
                'message' => 'Logged in successfully.',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user->only(['id', 'name', 'email']),
            ], 200); // 200 OK status for successful login

        } catch (ValidationException $e) {
            Log::warning('API Login validation failed: ' . json_encode($e->errors()));
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('API Login method failed: ' . $e->getMessage() . ' on line ' . $e->getLine());
            return response()->json([
                'message' => 'Login failed due to a server error.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle user logout for API.
     */
    public function logout(Request $request)
    {
        // For Sanctum API token logout, you delete the current token.
        // This expects the request to be authenticated via 'auth:sanctum' middleware.
        if ($request->user()) { // Ensure a user is authenticated
            $request->user()->currentAccessToken()->delete();
            Log::info('API Logout successful for user: ' . $request->user()->email);
            return response()->json(['message' => 'Logged out successfully.'], 200);
        }

        Log::warning('API Logout attempted by unauthenticated user.');
        return response()->json(['message' => 'Unauthorized or already logged out.'], 401);
    }
}