<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    protected $fillable = ['name', 'description', 'image', 'images', 'category_id', 'price', 'discount_price'];
    protected $casts = ['images' => 'array'];
    public function category() { return $this->belongsTo(Category::class); }
}
