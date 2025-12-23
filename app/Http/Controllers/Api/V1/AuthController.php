<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ApiErrorCode;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
        /**
         * Регистрация пользователя
         */
        public function register(Request $request)
        {
                try {
                        $request->validate([
                            'name' => 'required|string|min:1|max:100',
                            'surname' => 'required|string|min:1|max:100',
                            'patronymic' => 'required|string|min:1|max:100',
                            'phone' => ['required', 'max:16', 'regex:/^\d+$/'],
                            'email' => 'required|email|unique:users',
                            'password' => 'required|min:6'
                        ]);
                } catch (ValidationException $e) {
                        return response()->json([
                            'success' => false,
                            'error' => [
                                'code' => ApiErrorCode::VALIDATION_ERROR,
                                'message' => 'Invalid request data',
                                'details' => $e->errors(),
                            ]
                        ], 422);
                }

                $user = User::create([
                    'name' => $request->name,
                    'surname' => $request->surname,
                    'patronymic' => $request->patronymic,
                    'phone' => $request->phone,
                    'email_code' => '111111', // todo заменить
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);

//                $token = $user->createToken('api_token')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'data' => [
                        //'token' => $token,
                        'message' => 'You must confirm your email to complete registration',
                    ]
                ], 201);
        }
        public function verify(Request $request)
        {
                try {
                        $request->validate([
                            'email' => 'required|email|exists:users,email',
                            'code' => ['required', 'max:6', 'min:6', 'regex:/^\d+$/'],
                        ]);
                } catch (ValidationException $e) {
                        return response()->json([
                            'success' => false,
                            'error' => [
                                'code' => ApiErrorCode::VALIDATION_ERROR,
                                'message' => 'Invalid request data',
                                'details' => $e->errors(),
                            ]
                        ], 422);
                }

                $user = User::where('email', $request->email)->first();
                if ($user->email_code != $request->code){
                        return response()->json([
                            'success' => false,
                            'error' => [
                                    //'token' => $token,
                                'code' => ApiErrorCode::VALIDATION_ERROR,
                                'message' => 'Verification code is invalid',
                            ]
                        ], 401);
                } else {
                        $user->email_verified_at = now();
                        $user->save();
                        return response()->json([
                            'success' => true,
                            'data' => [
                                    //'token' => $token,
                                'message' => 'Your email confirmed successfully',
                            ]
                        ], 200);
                }
        }
        /**
         * Логин пользователя
         */
        public function login(Request $request)
        {
                $request->validate([
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

                $user = User::where('email', $request->email)
                    ->whereNotNull('email_verified_at')
                    ->first();

                if (! $user || ! Hash::check($request->password, $user->password)) {
                        return response()->json([
                            'success' => false,
                            'error' => [
                                'code' => ApiErrorCode::AUTH_UNAUTHENTICATED,
                                'message' => 'Invalid credentials',
                            ]
                        ], 401);
                }

                $token = $user->createToken('api_token')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'data' => [
                        'token' => $token,
                    ]
                ]);
        }

        /**
         * Профиль пользователя
         */
        public function profile(Request $request)
        {
                return response()->json([
                    'success' => true,
                    'data' => $request->user(),
                ]);
        }

        /**
         * Логаут пользователя
         */
        public function logout(Request $request)
        {
        //echo $request->user()->currentAccessToken();
        //die;
                $request->user()->currentAccessToken()->delete();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'message' => 'Logged out',
                    ]
                ]);
        }
}
