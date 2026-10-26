<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'players' => 'nullable|array',
            'notes' => 'nullable|string',
            'advance_payment' => 'nullable|numeric',
            'total' => 'nullable|numeric',
        ]);
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('orders', 'public');
                $images[] = Storage::url($path);
            }
        }
        $data['images'] = $images;
        $players = [];
        if (isset($data['players'])) {
            foreach ($data['players'] as $p) {
                $players[] = [
                    'name' => $p['name'] ?? '',
                    'number' => $p['number'] ?? '',
                    'size' => $p['size'] ?? '',
                    'type' => $p['type'] ?? 'jugador',
                    'details' => $p['details'] ?? '',
                ];
            }
        }
        $data['players'] = $players;
        $order = Order::create($data);
        if ($order->total > 0 && $order->advance_payment >= $order->total) {
            $order->status = 'pagado';
            $order->save();
        }
        return response()->json($order, 201);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $data = $request->validate([
            'advance_payment' => 'nullable|numeric',
            'status' => 'nullable|string|max:50',
        ]);
        if (isset($data['advance_payment'])) {
            $order->advance_payment = $order->advance_payment + $data['advance_payment'];
        }
        if (isset($data['status'])) {
            $order->status = $data['status'];
        }
        if ($order->total > 0 && $order->advance_payment >= $order->total) {
            $order->status = 'pagado';
        }
        $order->save();
        return response()->json($order);
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return response()->json(['deleted' => true]);
    }
}
