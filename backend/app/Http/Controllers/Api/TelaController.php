<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tela;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TelaController extends Controller
{
    public function index()
    {
        return response()->json(Tela::latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'main_photo' => 'nullable|image|max:5120',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:5120',
        ]);
        if ($request->hasFile('main_photo')) {
            $path = $request->file('main_photo')->store('telas', 'public');
            $data['main_photo'] = Storage::url($path);
        }
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('telas', 'public');
                $photos[] = Storage::url($path);
            }
        }
        if (!empty($photos)) {
            $data['photos'] = $photos;
        }
        $tela = Tela::create($data);
        return response()->json($tela, 201);
    }

    public function show($id)
    {
        return response()->json(Tela::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $tela = Tela::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'main_photo' => 'nullable|image|max:5120',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:5120',
        ]);
        if ($request->hasFile('main_photo')) {
            $path = $request->file('main_photo')->store('telas', 'public');
            $data['main_photo'] = Storage::url($path);
        }
        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $file) {
                $path = $file->store('telas', 'public');
                $photos[] = Storage::url($path);
            }
            $data['photos'] = $photos;
        }
        $tela->update($data);
        return response()->json($tela);
    }

    public function destroy($id)
    {
        Tela::findOrFail($id)->delete();
        return response()->json(['deleted' => true]);
    }

    public function destroyPhoto(Request $request, $id)
    {
        $tela = Tela::findOrFail($id);
        $photos = $tela->photos ?? [];
        $photo = $request->input('photo');
        $photos = array_values(array_filter($photos, fn ($p) => $p !== $photo));
        $tela->photos = $photos;
        $tela->save();
        return response()->json($tela);
    }
}