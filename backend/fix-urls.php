<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$base = 'http://127.0.0.1:8000';
$replace = '/storage';

// Simple single-column tables
$tables = [
    'categories' => 'image',
    'products' => 'image',
    'telas' => 'main_photo',
    'combos' => 'photo',
    'clients' => 'image',
    'designs' => 'image',
];
foreach ($tables as $table => $col) {
    DB::table($table)->where($col, 'like', $base . '%')->update([
        $col => DB::raw("REPLACE($col, '$base', '')"),
    ]);
}

// Orders images JSON
$orders = DB::table('orders')->get();
foreach ($orders as $o) {
    if (!$o->images) continue;
    $imgs = json_decode($o->images, true);
    if (!is_array($imgs)) continue;
    $changed = false;
    foreach ($imgs as &$im) {
        if (is_string($im) && str_starts_with($im, $base)) {
            $im = str_replace($base, '', $im);
            $changed = true;
        }
    }
    if ($changed) {
        DB::table('orders')->where('id', $o->id)->update(['images' => json_encode($imgs)]);
    }
}

echo "Listo. URLs actualizadas a rutas relativas.";
