<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\Items;
use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $user_id)
    {
        try {
            $all_orders = Orders::where('user_id', $user_id)->get();

            if (empty($all_orders)) {
                return ApiResponses::send('No data available', 404);
            }

            return ApiResponses::send('Successfully retrieved order data', 200, null, $all_orders);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponses::send('an error occurred while retrieving order data', 500, $e->getMessage());
        }

    }

    /**
     * Get the authenticated user ID from cookies.
     */
    private function getAuthenticatedUserId()
    {
        $userCookie = Cookie::get('__userAuth');

        if (empty($userCookie)) {
            return null;
        }

        return $userCookie['user_id'];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user_id = $this->getAuthenticatedUserId();

            if (!$user_id) {
                return ApiResponses::send('Unauthorized access', 401);
            }

            $validator = Validator::make($request->all(), [
                'item_id' => 'required|exists:items,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            if ($validator->fails()) {
                return ApiResponses::send('Validation failed', 400, $validator->errors());
            }

            $item = Items::find($request->input('item_id'));
            $startDate = Carbon::parse($request->input('start_date'));
            $endDate = Carbon::parse($request->input('end_date'));

            $days = $endDate->diffInDays($startDate);
            $total_price = $item->price_per_day * $days;

            $order = Orders::create([
                'user_id' => $user_id,
                'item_id' => $request->input('item_id'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'total_price' => $total_price,
                'status_id' => 1, // Default to pending
            ]);

            return ApiResponses::send('Order created successfully', 201, null, $order);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponses::send('An error occurred while storing the order', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $user_id = $this->getAuthenticatedUserId();

            if (!$user_id) {
                return ApiResponses::send('Unauthorized access', 401);
            }

            $order = Orders::where('id', $id)->where('user_id', $user_id)->first();

            if (empty($order)) {
                return ApiResponses::send('Order not found', 404);
            }

            return ApiResponses::send('Successfully retrieved order', 200, null, $order);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponses::send('An error occurred while retrieving order data', 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $user_id = $this->getAuthenticatedUserId();

            if (!$user_id) {
                return ApiResponses::send('Unauthorized access', 401);
            }

            $validator = Validator::make($request->all(), [
                'status_id' => 'required|exists:statuses,id',
            ]);

            if ($validator->fails()) {
                return ApiResponses::send('Validation failed', 400, $validator->errors());
            }

            $order = Orders::where('id', $id)->where('user_id', $user_id)->first();

            if (empty($order)) {
                return ApiResponses::send('Order not found', 404);
            }

            $order->status_id = $request->input('status_id');
            $order->save();

            return ApiResponses::send('Order status updated successfully', 200, null, $order);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponses::send('An error occurred while updating the order status', 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {

            $user_id = $this->getAuthenticatedUserId();

            if (!$user_id) {
                return ApiResponses::send('Unauthorized access', 401);
            }

            $order = Orders::where('id', $id)->where('user_id', $user_id)->first();

            if (empty($order)) {
                return ApiResponses::send('Order not found', 404);
            }

            $order->delete();

            return ApiResponses::send('Order deleted successfully', 200);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponses::send('An error occurred while deleting the order', 500, $e->getMessage());
        }
    }
}
