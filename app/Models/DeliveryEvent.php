<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['delivery_id', 'status', 'location', 'note', 'user_id'])]
class DeliveryEvent extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
