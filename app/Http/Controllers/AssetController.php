<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Exception;
use Illuminate\Http\Request;

class AssetController extends Controller
{

    public function show(Asset $asset) {
        return response()->json($asset, 200);
    }

    public function search(Request $request) {
        $request->validate(['key' => 'string|required']);

        $assets = Asset::where('name', 'like', "%$request->key%")
            ->orWhere('description', 'like', "%$request->key%")->get();

        return response()->json($assets, 200);
    }

    public function store(Request $request) {
        $request->validate([
            'name'          => 'string|required',
            'description'   => 'string|required',
            'location'      => 'string|required',
            'contained_in'  => 'numeric',
            'value'         => 'numeric|required',
            'acquired_on'   => 'date|required'
        ]);

        try {
            $asset = Asset::create($request->all());
            return response()->json($asset, 202);
        }catch(Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }
    

    public function update(Request $request, Asset $asset) {
        try {
            $asset->update($request->all());
            return response()->json($asset, 202);
        }catch(Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    public function destroy(Asset $asset) {
        $asset->delete();
        return response()->json(['message' => 'Asset deleted.'], 202);
    }

    public function index() {
        $assets = Asset::orderBy('name')->get();
        return response()->json($assets, 200);
    }
}
