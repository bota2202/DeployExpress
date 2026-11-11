<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $query = Delivery::with('customer', 'product')->latest();
        if ($request->user()->hasRole('cliente')) {
            $query->where('customer_id', $request->user()->id);
        }

        return view('deliveries.index', ['deliveries' => $query->get()]);
    }

    public function show(Request $request, Delivery $delivery)
    {
        abort_if($request->user()->hasRole('cliente') && $delivery->customer_id !== $request->user()->id, 403);

        return view('deliveries.show', ['delivery' => $delivery->load('customer', 'product', 'events.user')]);
    }

    public function create()
    {
        return view('deliveries.create', [
            'products' => Product::where('stock', '>', 0)->orderBy('name')->get(),
            'customers' => User::where('role', 'cliente')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['required', Rule::exists('users', 'id')->where('role', 'cliente')],
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
        ]);

        $delivery = DB::transaction(function () use ($data, $request) {
            // Lock the row so two shippers cannot both take the last unit.
            $product = Product::whereKey($data['product_id'])->lockForUpdate()->first();
            if ($product->stock < $data['quantity']) {
                throw ValidationException::withMessages(['quantity' => "Estoque insuficiente: só há {$product->stock} unidade(s)."]);
            }
            $product->decrement('stock', $data['quantity']);

            $delivery = Delivery::create($data + [
                'tracking_code' => Delivery::newTrackingCode(),
                'created_by' => $request->user()->id,
            ]);
            $delivery->events()->create(['status' => 'pendente', 'note' => 'Entrega criada', 'user_id' => $request->user()->id]);

            return $delivery;
        });

        return redirect()->route('deliveries.show', $delivery)->with('ok', "Entrega criada. Código de rastreio: {$delivery->tracking_code}");
    }

    public function addEvent(Request $request, Delivery $delivery)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Delivery::STATUSES))],
            'location' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($delivery, $data, $request) {
            $delivery->events()->create($data + ['user_id' => $request->user()->id]);
            $delivery->update(['status' => $data['status']]);
        });

        return back()->with('ok', 'Evento registrado.');
    }

    /** Public page: anyone with the code can follow the delivery. */
    public function track(Request $request)
    {
        $code = strtoupper(trim($request->query('codigo', '')));
        $delivery = $code ? Delivery::with('product', 'events')->where('tracking_code', $code)->first() : null;

        return view('track', ['code' => $code, 'delivery' => $delivery]);
    }
}
