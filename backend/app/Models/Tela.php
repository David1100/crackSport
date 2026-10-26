<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Tela extends Model
{
    protected $fillable = ['name', 'description', 'main_photo', 'photos'];
    protected $casts = ['photos' => 'array'];
}
