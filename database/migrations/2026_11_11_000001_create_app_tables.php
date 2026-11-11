<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['fabrica', 'expedicao', 'gerencia', 'cliente'])->default('cliente')->after('email');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('weight_kg', 10, 2)->nullable();
            $table->unsignedInteger('stock')->default(0); // units available in the shipping yard
            $table->timestamps();
        });

        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('quantity');
            $table->enum('status', ['em_producao', 'pronta', 'recebida'])->default('em_producao');
            $table->foreignId('created_by')->constrained('users');
            $table->unsignedInteger('received_quantity')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users');
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->string('loss_note')->nullable();
            $table->timestamps();
        });

        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code', 12)->unique();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('quantity');
            $table->string('address');
            $table->enum('status', ['pendente', 'em_transito', 'entregue', 'problema'])->default('pendente');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('delivery_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pendente', 'em_transito', 'entregue', 'problema']);
            $table->string('location')->nullable();
            $table->string('note')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_events');
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('production_orders');
        Schema::dropIfExists('products');
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('role'));
    }
};
