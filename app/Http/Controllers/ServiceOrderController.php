<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class ServiceOrderController extends Controller
{
    //
    public function createOrder(Request $request)
    {
        try {
            DB::beginTransaction();
            

            $user = Auth::user();

            $order = ServiceOrder::create([
                'user_id' => $user->id,
                'equipament' => $request['equipament'],
                'description' => $request['description'],
                'area' => $request['area'],
                'profile' => $request['profile'],
                'is_active' => $request['is_active'],
                'is_preventive' => $request['is_preventive'],
            ]);

            $response = [
                'message' => 'Ordem de servico criada com sucesso',
                'order' => [
                    'user_id' => $user->id,
                    'equipament' => $request['equipament'],
                    'description' => $request['description'],
                    'area' => $request['area'],
                    'profile' => $request['profile'],
                    'is_active' => $request['is_active'],
                    'is_preventive' => $request['is_preventive'],
                ]
            ];

            DB::commit();

            return response($response, 201);
        } catch (Exception $exception) {

            $response = ['message' => 'houve um erro ao cadastrar o usuario'];

            Log::error($exception->getMessage() . '\n' . $exception->getTraceAsString());

            DB::rollBack();

            return response($response, 500);
        }
    }
}
