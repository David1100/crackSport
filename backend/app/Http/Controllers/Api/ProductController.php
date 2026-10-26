<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index() { return response()->json(Product::with('category')->latest()->get()); }
    public function store(Request $r) {
        $data = $r->validate(['name'=>'required','description'=>'nullable','image'=>'nullable|image','category_id'=>'nullable','price'=>'nullable','discount_price'=>'nullable']);
        if ($r->hasFile('image')) {
            $path = $r->file('image')->store('products','public');
            $data['image'] = Storage::url($path);
        }
        if ($r->hasFile('images')) {
            $imgs = [];
            foreach ($r->file('images') as $file) {
                $path = $file->store('products','public');
                $imgs[] = Storage::url($path);
            }
            $data['images'] = $imgs;
        }
        return response()->json(Product::create($data)->load('category'), 201);
    }
    public function show($id)
    {
        return response()->json(\App\Models\Product::with('category')->findOrFail($id));
    }

    public function update(Request $r, $id) {
        $p = Product::findOrFail($id);
        $data = $r->validate(['name'=>'sometimes','description'=>'nullable','image'=>'nullable|image','category_id'=>'nullable','price'=>'nullable','discount_price'=>'nullable']);
        if ($r->hasFile('image')) {
            $path = $r->file('image')->store('products','public');
            $data['image'] = Storage::url($path);
        }
        if ($r->hasFile('images')) {
            $imgs = [];
            foreach ($r->file('images') as $file) {
                $path = $file->store('products','public');
                $imgs[] = Storage::url($path);
            }
            $data['images'] = $imgs;
        }
        $p->update($data);
        return response()->json($p->load('category'));
    }
    public function destroy($id) {
        Product::findOrFail($id)->delete();
        return response()->json(['deleted'=>true]);
    }
}