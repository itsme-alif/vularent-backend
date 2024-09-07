<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $user_id)
    {
        try {

            $all_transaction = Transactions::find($user_id);

            if (empty($all_transaction)) {
                return ApiResponses::send('No transactions found for this user', 404);
            }

            return ApiResponses::send('Transactions for this user', 200, null, $all_transaction);

        } catch (\Exception $e) {
            return ApiResponses::send($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user_id = Helper::getUserCookie();

            if (!$user_id) {
                return ApiResponses::send('Unauthorized access', 401);
            }

            $validator = Validator::make($request->all(), [
                'order_id' => 'required|numeric',
                'amount' => 'required|numeric',
                'status' => 'required|in:1,2,3',
                'payment_method' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return ApiResponses::send('Validation failed', 400, $validator->errors());
            }

            $is_validOrder = Helper::ValidateOrder($request->order_id);

            if (!$is_validOrder) {
                return ApiResponses::send('Invalid order ID', 400);
            }

            $is_validPaymentMethod = Helper::ValidatePaymentMethod($request->payment_method);

            if (!$is_validPaymentMethod) {
                return ApiResponses::send('Invalid payment method', 400);
            }

            $transaction = Transactions::create([
                'order_id' => $request->order_id,
                'amount' => $request->amount,
                'status_id' => $request->status,
                'payment_method_id' => $request->payment_method,
            ]);

            if (!$transaction) {
                return ApiResponses::send('Failed to create order', 500);
            }

            return ApiResponses::send('Order created successfully', 201);

        } catch (\Exception $e) {
            return ApiResponses::send($e->getMessage(), 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(int $transaction_id)
    {
        try {

            $transaction = Transactions::where('id', $transaction_id)->first();

            if (empty($transaction)) {
                return ApiResponses::send('No transaction found for this ID', 404);
            }

            return ApiResponses::send('Transaction details', 200, null, $transaction);

        } catch (\Exception $e) {
            return ApiResponses::send($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $transaction_id)
    {
        try {

            $transaction = Transactions::where('id', $transaction_id)->first();

            if (empty($transaction)) {
                return ApiResponses::send('Transaction not found for this user', 404);
            }

            $transaction->delete();

            return ApiResponses::send('Transaction deleted successfully', 200);

        } catch (\Exception $e) {
            return ApiResponses::send($e->getMessage(), 500);
        }
    }
}
