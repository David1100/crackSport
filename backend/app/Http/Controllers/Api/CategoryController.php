<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index() { return response()->json(Category::latest()->get()); }
    public function store(Request $r) {
        $data = $r->validate(['name'=>'required','image'=>'nullable|image']);
        if ($r->hasFile('image')) {
            $path = $r->file('image')->store('categories','public');
            $data['image'] = Storage::url($path);
        }
        return response()->json(Category::create($data), 201);
    }
    public function update(Request $r, $id) {
        $c = Category::findOrFail($id);
        $data = $r->validate(['name'=>'sometimes','image'=>'nullable|image']);
        if ($r->hasFile('image')) {
            $path = $r->file('image')->store('categories','public');
            $data['image'] = Storage::url($path);
        }
        $c->update($data);
        return response()->json($c);
    }
    public function destroy($id) {
        Category::findOrFail($id)->delete();
        return response()->json(['deleted'=>true]);
    }
}