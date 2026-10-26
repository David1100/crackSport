<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index() {
        return Inertia::render('Products/Index', [
            'products' => Product::all(),
            'categories' => Category::all(),
        ]);
    }
    public function create() { return Inertia::render('Products/Create', ['categories' => Category::all()]); }
    public function edit(Product $product) { return Inertia::render('Products/Edit', ['product' => $product, 'categories' => Category::all()]); }
}
