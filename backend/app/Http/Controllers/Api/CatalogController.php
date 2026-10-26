<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class CatalogController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get()->map(function ($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'image' => $c->image ? (str_starts_with($c->image, 'http') ? $c->image : asset(ltrim($c->image, '/'))) : null,
                'products' => $p = $c->products->map(function ($pr) {
                    return [
                        'id' => $pr->id,
                        'name' => $pr->name,
                        'description' => $pr->description,
                        'image' => $pr->image ? (str_starts_with($pr->image, 'http') ? $pr->image : asset(ltrim($pr->image, '/'))) : null,
                        'price' => $pr->price,
                        'discount_price' => $pr->discount_price,
                    ];
                }),
            ];
        });
        return response()->json($categories);
    }
}
