<?php

namespace Database\Seeders;

use App\Models\Delivery;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $gerente = User::create(['name' => 'Gerente', 'email' => 'gerencia@deployexpress.com', 'password' => 'senha123', 'role' => 'gerencia']);
        $fabrica = User::create(['name' => 'Operador Fábrica', 'email' => 'fabrica@deployexpress.com', 'password' => 'senha123', 'role' => 'fabrica']);
        $expedicao = User::create(['name' => 'Expedição', 'email' => 'expedicao@deployexpress.com', 'password' => 'senha123', 'role' => 'expedicao']);
        $cliente = User::create(['name' => 'Construtora Horizonte', 'email' => 'cliente@deployexpress.com', 'password' => 'senha123', 'role' => 'cliente']);

        $escavadeira = Product::create(['code' => 'ESC-320', 'name' => 'Escavadeira Hidráulica 320', 'weight_kg' => 22000, 'stock' => 3]);
        $retro = Product::create(['code' => 'RET-416', 'name' => 'Retroescavadeira 416', 'weight_kg' => 8500, 'stock' => 5]);
        Product::create(['code' => 'PAC-950', 'name' => 'Pá Carregadeira 950', 'weight_kg' => 18000]);

        ProductionOrder::create(['product_id' => $escavadeira->id, 'quantity' => 4, 'created_by' => $fabrica->id]);
        ProductionOrder::create(['product_id' => $retro->id, 'quantity' => 2, 'status' => 'pronta', 'created_by' => $fabrica->id])->forceFill(['ready_at' => now()->subHours(3)])->save();
        ProductionOrder::create(['product_id' => $retro->id, 'quantity' => 6, 'status' => 'recebida', 'created_by' => $fabrica->id])
            ->forceFill(['received_quantity' => 5, 'received_by' => $expedicao->id, 'ready_at' => now()->subDays(2), 'received_at' => now()->subDay(), 'loss_note' => 'Uma unidade não localizada no pátio'])->save();

        $delivery = Delivery::create([
            'tracking_code' => 'DXDEMO0001', 'customer_id' => $cliente->id, 'product_id' => $retro->id, 'quantity' => 1,
            'address' => 'Rod. BR-116, km 45, Curitiba - PR', 'status' => 'em_transito', 'created_by' => $expedicao->id,
        ]);
        $delivery->events()->create(['status' => 'pendente', 'note' => 'Entrega criada', 'user_id' => $expedicao->id, 'created_at' => now()->subDay()]);
        $delivery->events()->create(['status' => 'em_transito', 'location' => 'Pátio da fábrica', 'note' => 'Saiu para entrega', 'user_id' => $expedicao->id, 'created_at' => now()->subHours(5)]);
        $delivery->events()->create(['status' => 'em_transito', 'location' => 'Registro - SP', 'note' => 'Parada para abastecimento', 'user_id' => $expedicao->id]);
    }
}
