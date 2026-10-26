<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json(Client::latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'image' => 'required|image|max:5120',
        ]);
        $path = $request->file('image')->store('clients', 'public');
        $data['image'] = Storage::url($path);
        return response()->json(Client::create($data), 201);
    }

    public function destroy($id)
    {
        Client::findOrFail($id)->delete();
        return response()->json(['deleted' => true]);
    }
}
