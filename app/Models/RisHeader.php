<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RisHeader extends Model
{
    protected $fillable = [
        'ris_no',
        'entity_name',
        'fund_cluster',
        'division_id',
        'office',
        'responsibility_center_code',
        'purpose',
        'requested_by',
        'approved_by',
        'issued_by',
        'received_by',
        'status',
        'qr_token',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(RisDetail::class, 'ris_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class, 'ris_id');
    }
}
