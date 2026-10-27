<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['client_name', 'client_phone', 'images', 'players', 'notes', 'status', 'advance_payment', 'total', 'shirt_fabric', 'short_fabric', 'sock_quality', 'collar_type', 'delivered', 'order_type', 'product_id', 'product_name', 'quantity', 'size'];
    protected $casts = ['images' => 'array', 'players' => 'array', 'delivered' => 'boolean'];
}
