<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response(['message' => 'dados invalidos']);
        }

        $data = $validator->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            $response = ['message' => 'informacoes nao aceitas'];
            return response($response, 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        $response = [
            'message' => 'usuario encontrado e retornado',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'is_admin' => $user->is_admin
            ],
            'token' => $token
        ];

        return response($response, 200);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
    
        return response()->json([
        'message' => 'Deslogado com sucesso'
        ]);
    
    }

}