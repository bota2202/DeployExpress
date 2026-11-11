<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Product;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->user()->hasRole('cliente')) {
            return redirect()->route('deliveries.index');
        }

        return view('dashboard', [
            'stockTotal' => Product::sum('stock'),
            'productsCount' => Product::count(),
            'ordersInProduction' => ProductionOrder::where('status', 'em_producao')->count(),
            'ordersReady' => ProductionOrder::where('status', 'pronta')->count(),
            'lossTotal' => (int) ProductionOrder::where('status', 'recebida')->sum(DB::raw('quantity - received_quantity')),
            'deliveriesByStatus' => Delivery::selectRaw('status, COUNT(*) AS total')->groupBy('status')->pluck('total', 'status'),
            'recentLosses' => ProductionOrder::with('product')->where('status', 'recebida')->whereColumn('received_quantity', '<', 'quantity')->latest('received_at')->limit(5)->get(),
        ]);
    }
}
