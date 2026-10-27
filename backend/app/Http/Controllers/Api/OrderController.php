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
            'shirt_fabric' => 'nullable|string|max:255',
            'short_fabric' => 'nullable|string|max:255',
            'sock_quality' => 'nullable|string|max:50',
            'collar_type' => 'nullable|string|max:50',
            'delivered' => 'nullable|boolean',
            'order_type' => 'nullable|string|max:20',
            'product_id' => 'nullable|integer',
            'product_name' => 'nullable|string|max:255',
            'quantity' => 'nullable|integer',
            'size' => 'nullable|string|max:50',
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
                    'short_size' => $p['short_size'] ?? '',
                    'type' => $p['type'] ?? 'jugador',
                    'details' => $p['details'] ?? '',
                    'ready' => (bool) ($p['ready'] ?? false),
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

        // Abono-only (no other fields): accumulate
        if ($request->has('advance_payment') && !$request->has('client_name') && !$request->has('players') && !$request->has('keep_images') && !$request->hasFile('images')) {
            $order->advance_payment = $order->advance_payment + (float) $request->input('advance_payment', 0);
            if ($order->total > 0 && $order->advance_payment >= $order->total) {
                $order->status = 'pagado';
            }
            $order->save();
            return response()->json($order);
        }

        $data = $request->validate([
            'client_name' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'keep_images' => 'nullable|array',
            'players' => 'nullable|array',
            'notes' => 'nullable|string',
            'advance_payment' => 'nullable|numeric',
            'total' => 'nullable|numeric',
            'shirt_fabric' => 'nullable|string|max:255',
            'short_fabric' => 'nullable|string|max:255',
            'sock_quality' => 'nullable|string|max:50',
            'collar_type' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'delivered' => 'nullable|boolean',
            'order_type' => 'nullable|string|max:20',
            'product_id' => 'nullable|integer',
            'product_name' => 'nullable|string|max:255',
            'quantity' => 'nullable|integer',
            'size' => 'nullable|string|max:50',
        ]);

        if (isset($data['client_name'])) $order->client_name = $data['client_name'];
        if (array_key_exists('client_phone', $data)) $order->client_phone = $data['client_phone'];
        if (array_key_exists('notes', $data)) $order->notes = $data['notes'];
        if (isset($data['advance_payment'])) $order->advance_payment = $data['advance_payment'];
        if (isset($data['total'])) $order->total = $data['total'];
        if (array_key_exists('shirt_fabric', $data)) $order->shirt_fabric = $data['shirt_fabric'];
        if (array_key_exists('short_fabric', $data)) $order->short_fabric = $data['short_fabric'];
        if (array_key_exists('sock_quality', $data)) $order->sock_quality = $data['sock_quality'];
        if (array_key_exists('collar_type', $data)) $order->collar_type = $data['collar_type'];
        if (array_key_exists('delivered', $data)) $order->delivered = $data['delivered'];
        if (array_key_exists('order_type', $data)) $order->order_type = $data['order_type'];
        if (array_key_exists('product_id', $data)) $order->product_id = $data['product_id'];
        if (array_key_exists('product_name', $data)) $order->product_name = $data['product_name'];
        if (array_key_exists('quantity', $data)) $order->quantity = $data['quantity'];
        if (array_key_exists('size', $data)) $order->size = $data['size'];
        if (isset($data['status'])) $order->status = $data['status'];

        // images: keep existing + new uploads
        $keep = $request->input('keep_images', $order->images ?? []);
        if (!is_array($keep)) $keep = [];
        $images = array_values($keep);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('orders', 'public');
                $images[] = Storage::url($path);
            }
        }
        $order->images = $images;

        if (isset($data['players'])) {
            $players = [];
            foreach ($data['players'] as $p) {
                $players[] = [
                    'name' => $p['name'] ?? '',
                    'number' => $p['number'] ?? '',
                    'size' => $p['size'] ?? '',
                    'short_size' => $p['short_size'] ?? '',
                    'type' => $p['type'] ?? 'jugador',
                    'details' => $p['details'] ?? '',
                    'ready' => (bool) ($p['ready'] ?? false),
                ];
            }
            $order->players = $players;
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
