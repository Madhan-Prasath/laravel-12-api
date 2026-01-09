<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Registration API

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->all();
        $imagePath = null;
        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            $file = $request->file('profile_picture');

            $fileName = time().'_'.$file->getClientOriginalName();

            $file->move(public_path('/storage/profile'), $fileName);
            $imagePath = '/storage/profile/'.$fileName;
        }

        $data['profile_picture'] = $imagePath;

        $user = User::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    // Login API
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $response['token'] = $user->createToken('BlogApp')->plainTextToken;
            $response['email'] = $user->email;
            $response['name'] = $user->name;

            return response()->json([
                'status' => 'success',
                'message' => 'Logged in successfully',
                'data' => $response,
            ], 200);
        } else {

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Credentials',
            ], 400);
        }
    }

    // Profile API
    public function profile()
    {
        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ], 200);
    }

    // Logout API
    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ], 200);
    }
}
