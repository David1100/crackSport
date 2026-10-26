<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index() {
        return Inertia::render('Categories/Index', ['categories' => Category::all()]);
    }
    public function create() { return Inertia::render('Categories/Create'); }
    public function edit(Category $category) { return Inertia::render('Categories/Edit', ['category' => $category]); }
}
