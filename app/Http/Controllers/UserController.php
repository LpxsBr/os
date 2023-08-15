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
                'status' => 'ok',
                'message'=>'usuario registrado com sucesso :)',
                'user' => $request['name']
            ];

            DB::commit();

            return response($response, 201);

        } catch (Exception $exception) {
            
            $response = [
                'status' => 'failed',
                'message'=>'houve um erro ao cadastrar o usuario'];

            Log::error($exception->getMessage().'\n'.$exception->getTraceAsString());

            DB::rollBack();

            return response($response, 500);
        }
    }
    public function show(Request $request)
    {
        if(!Auth::user()){
            return response([
                'status' => 'failed',
                'message'=>'usuario nao autenticado'
            ], 403);
        }
        return response(User::all(), 200);
    }
    public function update(Request $request){
        if(!Auth::user()){
            return response([
                'status' => 'failed',
                'message'=>'usuario nao autenticado'
            ], 403);
        }

        try {
            //code...
            DB::beginTransaction();
            $id = Auth::user()->id;
            $user = User::find($id);
            // ->update(
            //     [ 
            //         'name' => $request['name'],
            //         'email' => $request['email'],
            //         'password' => bcrypt($request['password']),
            //         'is_admin' => 0
            //     ]
            // );

            $response = [
                'status' => 'ok',
                'message'=>'usuario atualizado com sucesso :)',
                'id' => $id,
                'user' => $user
            ];
            // ->update(
                // // [  
                    $user->name = $request['name'];
                    $user->email = $request['email'];
                    $user->password = bcrypt($request['password']);
                    $user->i_admin = 0;
                // ]
            // );
            // $user->name = $request['name'];
            $user->save();
            return response($response, 200);
        } catch (Exception $exception) {
            
            $response = [
                'status' => 'failed',
                'message'=>'houve um erro ao atualizar o usuario'];

            Log::error($exception->getMessage().'\n'.$exception->getTraceAsString());

            DB::rollBack();

            return response($response, 500);
        }
    }
}
