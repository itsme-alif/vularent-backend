<?php

namespace App\Http\Controllers;

use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $all_items = Items::all();

            if ($all_items->isEmpty()) {
                return ApiResponses::send('No data available', 404);
            }

            return ApiResponses::send('Successfully retrieved item data', 200, null, $all_items);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponses::send('An error occurred while fetching items', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_name' => 'required|string|max:255',
                'item_description' => 'required|string',
                'category_id' => 'required|integer|exists:categories,id',
                'owner_id' => 'required|integer|exists:users,id',
                'price_per_day' => 'required|numeric|min:0',
                'status_id' => 'required|integer|exists:item_statuses,id'
            ]);

            if ($validator->fails()) {
                return ApiResponses::send('Validation failed', 400, $validator->errors());
            }

            $item = Items::create($request->all());

            return ApiResponses::send('Successfully created new item', 201, null, $item);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponses::send('An error occurred while creating the item', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $item = Items::find($id);

            if (!$item) {
                return ApiResponses::send('Item not found', 404);
            }

            return ApiResponses::send('Successfully retrieved item', 200, null, $item);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponses::send('An error occurred while retrieving the item', 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_name' => 'required|string|max:255',
                'item_description' => 'string',
                'category_id' => 'integer|exists:categories,id',
                'owner_id' => 'integer|exists:owners,id',
                'price_per_day' => 'required|numeric|min:0',
                'status_id' => 'integer|exists:item_statuses,id'
            ]);

            if ($validator->fails()) {
                return ApiResponses::send('Validation failed', 400, $validator->errors());
            }

            $item = Items::find($id);

            if (!$item) {
                return ApiResponses::send('Item not found', 404);
            }

            $item->update($request->only([
                'item_name',
                'item_description',
                'category_id',
                'owner_id',
                'price_per_day',
                'status_id'
            ]));

            return ApiResponses::send('Item updated successfully', 200, null, $item);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponses::send('An error occurred while updating the item', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $item = Items::find($id);

            if ($item->isEmpty()) {
                return ApiResponses::send('Item not found', 404);
            }

            $item->delete();

            return ApiResponses::send('Item deleted successfully', 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponses::send('An error occurred while deleting the item', 500);
        }
    }
}
