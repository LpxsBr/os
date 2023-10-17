<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

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
        $user = Auth::user();
        if(!Auth::user()){
            return response([
                'status' => 'failed',
                'message'=>'usuario nao autenticado'
            ], 403);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => [
                    'required',
                    'string',
                    Rule::unique('users')->ignore($user->id),
                ],
                'password' => [
                    Rule::requiredIf($request->has('password')),
                    'string',
                    'confirmed'
                ]
            ]    
        );

        // Check if data is valid
        if ($validator->fails()) {
            // Create the response
            $response = [
                'message' => 'Os dados enviados estão inválidos',
                'errors' => $validator->errors()
            ];

            return response($response, 422);
        }

        // Get the data validated
        $fields = $validator->validated();
        try {
            DB::beginTransaction();
            $response = [
                'status' => 'ok',
                'message'=>'usuario atualizado com sucesso :)',  
                'user' => $user
            ];
                    $user->name = $request['name'] ?? $user->name;
                    $user->email = $request['email'] ?? $user->email;
                    if ($request->has('password')) {
                        $user->password = bcrypt($fields['password']);
                    }
                    $user->is_admin = 0;
            $user->save();
        
            DB::commit();
        
            return response($response, 201);
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
