<?php

namespace App\Http\Controllers;

use App\Models\items;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_item = DB::table('items as i')
        ->leftJoin('item_statuses as is', 'is.id', '=', 'i.status_id')
        ->leftJoin('categories as c', 'c.id', '=', 'i.category_id')
        ->leftJoin('users as u', 'u.id', '=', 'i.owner_id')
        ->select([
            'i.*',
            'is.status_name',
            'c.category_name',
            'u.name as owner_name',
            'u.email  as owner_email',
            'u.phone_number as owner_phone'
        ])
        ->orderBy('i.id')
        ->get();


        if (empty($all_item)) {
            return ApiResponses::send('No data here', 206);
        }

        return ApiResponses::send('Successfully get item data', 200, null, $all_item);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_name' => 'required',
                'item_description' => 'required',
                'category_id' => 'integer|required',
                'owner_id' => 'integer|required',
                'price_per_day' => 'integer|required',
                'status_id' => 'integer|required'
            ]);

            if ($validator->fails()) {
                return ApiResponses::send('all input is required', 400, $validator->errors());
            }

            $item = items::created($request->all());

            if (!$item) {
                return ApiResponses::send('Failed to create a new item', 400, $item);
            }

            return ApiResponses::send('Successfully create new item', 200, null, $item);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $category_name)
    {
        $get_item = DB::table('items as i')
        ->where('c.category_name', '=', $category_name)
        ->leftJoin('item_statuses as is', 'is.id', '=', 'i.status_id')
        ->leftJoin('categories as c', 'c.id', '=', 'i.category_id')
        ->leftJoin('users as u', 'u.id', '=', 'i.owner_id')
        ->select([
            'i.*',
            'is.status_name',
            'c.category_name',
            'u.name as owner_name',
            'u.email  as owner_email',
            'u.phone_number as owner_phone'
        ])
        ->orderBy('i.id')
        ->get();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Integer $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'item_name' => 'required|string|max:255',
                'item_description' => 'required|string',
                'category_id' => 'required|integer|exists:categories,id',
                'owner_id' => 'required|integer|exists:owners,id',
                'price_per_day' => 'required|numeric|min:0',
                'status_id' => 'required|integer|exists:statuses,id'
            ]);

            if ($validator->fails()) {
                return ApiResponses::send('Validation failed', 400, $validator->errors());
            }

            // Find the item by ID
            $item = items::find($id);

            if (!$item) {
                return ApiResponses::send('Item not found', 404);
            }

            // Update the item with validated data
            $item->update($request->only([
                'item_name',
                'item_description',
                'category_id',
                'owner_id',
                'price_per_day',
                'status_id'
            ]));

            // Return a successful response
            return ApiResponses::send('Item updated successfully', 200, null, $item);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Find the item by ID
        $item = items::find($id);

        // Check if the item exists
        if (!$item) {
            return ApiResponses::send('Item not found', 404);
        }

        // Attempt to delete the item
        try {
            $item->delete();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return ApiResponses::send('Item deleted successfully', 200);
    }


}
