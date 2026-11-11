<?php

namespace Tests\Feature;

use App\Models\Delivery;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlowTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::create(['name' => $role, 'email' => "$role@test.com", 'password' => 'x', 'role' => $role]);
    }

    public function test_receiving_an_order_records_loss_and_updates_stock(): void
    {
        $product = Product::create(['code' => 'P1', 'name' => 'Produto', 'stock' => 0]);
        $order = ProductionOrder::create(['product_id' => $product->id, 'quantity' => 5, 'status' => 'pronta', 'created_by' => $this->user('fabrica')->id]);

        $this->actingAs($this->user('expedicao'))
            ->post(route('orders.receive', $order), ['received_quantity' => 3])
            ->assertSessionHasErrors('loss_note'); // loss without a reason is refused

        $this->post(route('orders.receive', $order), ['received_quantity' => 3, 'loss_note' => 'caiu'])->assertRedirect();

        $this->assertSame(2, $order->fresh()->loss);
        $this->assertSame(3, $product->fresh()->stock);
    }

    public function test_delivery_cannot_exceed_stock_and_client_sees_only_own(): void
    {
        $product = Product::create(['code' => 'P1', 'name' => 'Produto', 'stock' => 2]);
        $client = $this->user('cliente');
        $other = User::create(['name' => 'o', 'email' => 'o@test.com', 'password' => 'x', 'role' => 'cliente']);

        $this->actingAs($this->user('expedicao'))
            ->post(route('deliveries.store'), ['customer_id' => $client->id, 'product_id' => $product->id, 'quantity' => 3, 'address' => 'Rua A'])
            ->assertSessionHasErrors('quantity');

        $this->post(route('deliveries.store'), ['customer_id' => $client->id, 'product_id' => $product->id, 'quantity' => 2, 'address' => 'Rua A'])->assertRedirect();
        $delivery = Delivery::first();
        $this->assertSame(0, $product->fresh()->stock);

        $this->actingAs($other)->get(route('deliveries.show', $delivery))->assertForbidden();
        $this->actingAs($client)->get(route('deliveries.show', $delivery))->assertOk()->assertSee($delivery->tracking_code);
        $this->get(route('orders.index'))->assertForbidden();
        $this->get(route('track', ['codigo' => $delivery->tracking_code]))->assertOk()->assertSee('Produto');
    }
}
