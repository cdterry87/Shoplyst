<?php

namespace App\Http\Controllers;

use App\Lists;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Auth::user()->lists()->orderBy('created_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $list = Lists::create([
            'name' => $request->name,
            'user_id' => Auth::user()->id
        ]);

        return response()->json([
            'status' => (bool) $list,
            'data' => $list,
            'message' => $list ? 'Your list was created successfully!' : 'An error occurred while trying to create your list.  Try again later.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Lists $list)
    {
        return response()->json($list->where('id', $list->id)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lists $list)
    {
        $status = $list->update(
            $request->only(['name'])
        );

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Your list was updated successfully!' : 'An error occurred while updating your list. Try again later.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lists $list)
    {
        $status = $list->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Your list was deleted successfully!' : 'An error occurred while trying to delete your list. Try again later.'
        ]);
    }
}
