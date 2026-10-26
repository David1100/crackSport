<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ComboController extends Controller
{
    public function index()
    {
        return Inertia::render('Combos/Index', ['combos' => Combo::all()]);
    }

    public function create()
    {
        return Inertia::render('Combos/Create');
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
        Combo::create($data);
        return redirect()->route('combos.index');
    }

    public function edit(Combo $combo)
    {
        return Inertia::render('Combos/Edit', ['combo' => $combo]);
    }

    public function update(Request $request, Combo $combo)
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
        $combo->update($data);
        return redirect()->route('combos.index');
    }

    public function destroy(Combo $combo)
    {
        $combo->delete();
        return redirect()->route('combos.index');
    }
}
