<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    public function index()
    {
        return response()->json(Design::latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'image' => 'required|image|max:5120',
        ]);
        $path = $request->file('image')->store('designs', 'public');
        $data['image'] = Storage::url($path);
        return response()->json(Design::create($data), 201);
    }

    public function destroy($id)
    {
        $design = Design::findOrFail($id);
        $design->delete();
        return response()->json(['deleted' => true]);
    }
}
