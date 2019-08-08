<?php

namespace App\Http\Controllers;

use App\Lists;
use App\ListItems;
use Illuminate\Http\Request;

class ListItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Lists $list)
    {
        return response()->json($list->items()->orderBy('name')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = ListItems::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'reminder' => $request->reminder,
            'complete' => $request->complete,
            'list_id' => $request->list_id,
        ]);

        return response()->json([
            'status' => (bool) $item,
            'data' => $item,
            'message' => $item ? 'Your item was added successfully!' : 'An error occurred while trying to add your item. Try again later.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ListItems $item)
    {
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ListItems $item)
    {
        $status = $item->update(
            $request->only(['name', 'price', 'quantity', 'reminder', 'complete'])
        );

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Your item was updated successfully.' : 'An error occurred while trying to update your item. Try again later.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ListItems $item)
    {
        $status = $item->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Your item was deleted successfully.' : 'An error occurred while trying to delete your item. Try again later.'
        ]);
    }
}
