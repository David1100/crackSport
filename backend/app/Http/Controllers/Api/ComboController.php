<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComboController extends Controller
{
    public function index()
    {
        return response()->json(Combo::latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric',
            'photo' => 'nullable|image|max:5120',
            'includes' => 'nullable|string',
            'applies_for' => 'nullable|string|max:255',
        ]);
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('combos', 'public');
            $data['photo'] = Storage::url($path);
        }
        return response()->json(Combo::create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $combo = Combo::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'nullable|numeric',
            'photo' => 'nullable|image|max:5120',
            'includes' => 'nullable|string',
            'applies_for' => 'nullable|string|max:255',
        ]);
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('combos', 'public');
            $data['photo'] = Storage::url($path);
        }
        $combo->update($data);
        return response()->json($combo);
    }

    public function destroy($id)
    {
        Combo::findOrFail($id)->delete();
        return response()->json(['deleted' => true]);
    }
}
