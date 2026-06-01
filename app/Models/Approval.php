<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    protected $fillable = ['ris_id', 'user_id', 'action', 'remarks', 'approved_at'];
    protected $casts = ['approved_at' => 'datetime'];

    public function ris(): BelongsTo
    {
        return $this->belongsTo(RisHeader::class, 'ris_id');
    }
}
