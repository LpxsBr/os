<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    //
    public function create(Request $request){
        try {
            DB::beginTransaction();

            $user  = User::create([
                'name'=>$request['name'],
                'email'=>$request['email'],
                'password'=>bcrypt($request['password']),
                'is_admin'=>'0'
            ]);

            $response = [
                'message'=>'usuario registrado com sucesso :)',
                'user' => $request['name']
            ];

            DB::commit();

            return response($response, 201);

        } catch (Exception $exception) {
            
            $response = ['message'=>'houve um erro ao cadastrar o usuario'];

            Log::error($exception->getMessage().'\n'.$exception->getTraceAsString());

            DB::rollBack();

            return response($response, 500);
        }
    }
    public function show(Request $request)
    {
        if(!Auth::user()){
            return response(['message'=>'usuario nao autenticado'], 403);
        }
        return response(User::all(), 200);
    }
}
