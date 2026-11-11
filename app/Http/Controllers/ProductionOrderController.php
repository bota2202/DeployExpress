<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionOrderController extends Controller
{
    public function index()
    {
        return view('orders.index', [
            'orders' => ProductionOrder::with('product', 'creator', 'receiver')->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('orders.create', ['products' => Product::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        ProductionOrder::create($data + ['created_by' => $request->user()->id]);

        return redirect()->route('orders.index')->with('ok', 'Ordem de produção aberta.');
    }

    /** Factory marks the order as finished; shipping is now expected to pick it up. */
    public function ready(ProductionOrder $order)
    {
        abort_unless($order->status === 'em_producao', 400, 'Esta ordem não está em produção.');
        $order->update(['status' => 'pronta', 'ready_at' => now()]);

        return back()->with('ok', 'Ordem marcada como pronta. A expedição já vê o aviso.');
    }

    /** Shipping counts what actually arrived. Difference from the planned quantity is the loss. */
    public function receive(Request $request, ProductionOrder $order)
    {
        abort_unless($order->status === 'pronta', 400, 'Esta ordem ainda não está pronta.');

        $data = $request->validate([
            'received_quantity' => "required|integer|min:0|max:{$order->quantity}",
            'loss_note' => 'nullable|string|max:255',
        ]);

        if ($data['received_quantity'] < $order->quantity && empty($data['loss_note'])) {
            return back()->withInput()->withErrors(['loss_note' => 'Houve perda: explique o que aconteceu.']);
        }

        DB::transaction(function () use ($order, $data, $request) {
            $order->forceFill($data + ['status' => 'recebida', 'received_by' => $request->user()->id, 'received_at' => now()])->save();
            Product::whereKey($order->product_id)->increment('stock', $data['received_quantity']);
        });

        return redirect()->route('orders.index')->with('ok', 'Recebimento registrado. Estoque atualizado.');
    }
}
