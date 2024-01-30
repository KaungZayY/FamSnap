<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * register new user
     * POST /api/register
     * @param name,email,password,password_confirmation
     */
    public function store()
    {
        try {
            $validator = Validator::make(request()->all(),[
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed'
            ]);
            if($validator->fails()){
                $flattenedErrors = collect($validator->errors())->flatMap(function ($e,$field){
                    return [$field=>$e[0]];
                });
                return response()->json([
                    'message' => $flattenedErrors,
                    'status' => 400
                ],400);
            }
            //if valid
            $user = new User();
            $user->name = request()->name;
            $user->email = request()->email;
            $user->password = bcrypt(request()->password);
            $user->save();

            //login and create token
            Auth::login($user);
            $userToken = $user->createToken('user-token');
            return response($userToken,201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ],500);
        }
    }

    /**
     * login user
     * POST /api/login
     * @param email,password
     */
    public function login()
    {
        try {
            $validator = Validator::make(request()->all(),[
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if($validator->fails()){
                $flattenedErrors = collect($validator->errors())->flatMap(function ($e,$field){
                    return [$field=>$e[0]];
                });
                return response()->json([
                    'message' => $flattenedErrors,
                    'status' => 400
                ],400);
            }
            //if valid
            $user = User::where('email',request()->email)->first();
            if(!$user || !Hash::check(request()->password,$user->password))
            {
                return response()->json([
                    'message' => 'Invalid User Credentials',
                    'status' => 401
                ],400);
            }

            //login and create token
            Auth::login($user);
            $userToken = $user->createToken('user-token');
            return response($userToken,201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ],500);
        }
    }

    /**
     * logout user
     * POST /api/logout
     */
    public function logout()
    {
        try {
            auth()->user()->tokens->each(function ($token, $key) {
                $token->delete();
            });
            auth()->guard('web')->logout();
            return response()->json([
                'message' => 'logout successful',
                'status' => 200,
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ],500); 
        }
    }
}
