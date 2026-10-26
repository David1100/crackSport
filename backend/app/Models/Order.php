<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['client_name', 'client_phone', 'images', 'players', 'notes', 'status', 'advance_payment', 'total'];
    protected $casts = ['images' => 'array', 'players' => 'array'];
}
