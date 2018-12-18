<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Item;
use App\Menu;
use App\Http\Resources\Item as ItemResource;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'perPage' => 'integer'
        ]);

        if ($request->has('perPage')) {
            return ItemResource::collection(Item::paginate($request->perPage));
        } else {
            return ItemResource::collection(Item::all());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|integer',
            'menu_id' => 'required'
        ]);

        // If file is set then validate file name and file type
        if ($request->filled('file')) {
            $request->validate([
                'file_name' => 'required',
                'file_type' => 'required'
            ]);
        }

        $image_url = null;

        if  ($request->has('file')) {
            $file = $request->file;
            if (preg_match('/^data:image\/(\w+);base64,/', $file)) {
                $data = substr($file, strpos($file, ',') + 1);
                $data = base64_decode($data);
                $file_type = $request->file_type;
                $extension = explode("/", $file_type)[1];
                $filename = $request->file_name;
                // Filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                // Upload Image
                Storage::disk('local')->put('public/items/'.$fileNameToStore, $data);
                $image_url = Storage::url('public/items/'.$fileNameToStore);
            }
        }

        // Can't find associated menu id
        if (Menu::find($request->menu_id) === null) {
            return response()->toJson([
                'message' => 'Menu not found',
            ], 404);
        }

        $item = Item::create([
            'name' => $request->name,
            'price' => $request->price,
            'image_url' => $image_url,
            'menu_id' => $request->menu_id
        ]);

        return new ItemResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return new ItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|integer',
            'menu_id' => 'required'
        ]);

        if ($request->filled('file')) {
            $image_url = null;

            $request->validate([
                'file_name' => 'required',
                'file_type' => 'required'
            ]);

            $file = $request->file;
            if (preg_match('/^data:image\/(\w+);base64,/', $file)) {
                $data = substr($file, strpos($file, ',') + 1);
                $data = base64_decode($data);
                $file_type = $request->file_type;
                $extension = explode("/", $file_type)[1];
                $filename = $request->file_name;
                // Filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                // Upload Image
                Storage::disk('local')->put('public/restaurants/'.$fileNameToStore, $data);
                $image_url = Storage::url('public/restaurants/'.$fileNameToStore);
            }

            $item->update([
                'name' => $request->name,
                'price' => $request->price,
                'image_url' => $image_url,
                'menu_id' => $request->menu_id
            ]);
        } else {
            $item->update([
                'name' => $request->name,
                'price' => $request->price,
                'menu_id' => $request->menu_id
            ]);
        }

        return new ItemResource($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json(null, 204);
    }
}
