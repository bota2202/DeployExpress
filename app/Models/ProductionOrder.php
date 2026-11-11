<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'quantity', 'status', 'created_by'])]
class ProductionOrder extends Model
{
    public const STATUSES = [
        'em_producao' => 'Em produção',
        'pronta' => 'Pronta (aguardando expedição)',
        'recebida' => 'Recebida pela expedição',
    ];

    protected $casts = ['ready_at' => 'datetime', 'received_at' => 'datetime'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /** Units the factory sent but the shipping yard never got. */
    public function getLossAttribute(): int
    {
        return $this->received_quantity === null ? 0 : $this->quantity - $this->received_quantity;
    }
}
