<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = ['stock_no', 'item_code', 'description', 'unit', 'category_id', 'quantity_on_hand', 'reorder_level', 'status'];
    protected $casts = ['quantity_on_hand' => 'decimal:2', 'reorder_level' => 'decimal:2'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function risDetails(): HasMany
    {
        return $this->hasMany(RisDetail::class);
    }

    public function deduct(float $quantity): void
    {
        $this->decrement('quantity_on_hand', $quantity);
        $this->refreshStatus();
    }

    public function refreshStatus(): void
    {
        $status = match (true) {
            (float) $this->quantity_on_hand <= 0 => 'out_of_stock',
            (float) $this->quantity_on_hand <= (float) $this->reorder_level => 'low_stock',
            default => 'active',
        };

        $this->forceFill(['status' => $status])->save();
    }
}
