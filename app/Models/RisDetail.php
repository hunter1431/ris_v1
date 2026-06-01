<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RisDetail extends Model
{
    protected $fillable = ['ris_id', 'item_id', 'stock_no', 'unit', 'description', 'qty_requested', 'qty_issued', 'remarks'];
    protected $casts = ['qty_requested' => 'decimal:2', 'qty_issued' => 'decimal:2'];

    public function ris(): BelongsTo
    {
        return $this->belongsTo(RisHeader::class, 'ris_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
