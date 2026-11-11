<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['tracking_code', 'customer_id', 'product_id', 'quantity', 'address', 'status', 'created_by'])]
class Delivery extends Model
{
    public const STATUSES = [
        'pendente' => 'Pendente',
        'em_transito' => 'Em trânsito',
        'entregue' => 'Entregue',
        'problema' => 'Problema',
    ];

    public static function newTrackingCode(): string
    {
        do {
            $code = 'DX'.strtoupper(Str::random(8));
        } while (static::where('tracking_code', $code)->exists());

        return $code;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(DeliveryEvent::class)->latest('id');
    }
}
