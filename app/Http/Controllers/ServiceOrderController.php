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
                'status' => 'ok', 'message' => 'Ordem de servico criada com sucesso',
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

            $response = ['status' => 'ok', 'message' => 'houve um erro ao cadastrar o usuario'];

            Log::error($exception->getMessage() . '\n' . $exception->getTraceAsString());

            DB::rollBack();

            return response($response, 500);
        }
    }
    public function getAllOrder(Request $request)
    {
        return response(ServiceOrder::all(), 200);
    }

    public function getOrderById($id)
    {

        $order = ServiceOrder::all()->where('id', $id);

        $response = [
            'status' => 'ok', 'message' => 'ordem de servico encontrada',
            'order' => $order[1]
        ];

        return response($response, 200);
    }

    public function getOrderByUserId($user)
    {

        $order = ServiceOrder::all()->where('user_id', $user);

        $response = [
            'status' => 'ok', 'message' => 'ordem de servico encontrada',
            'order' => $order
        ];

        return response($response, 200);
    }
}
