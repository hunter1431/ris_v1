<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    protected $fillable = ['name', 'code'];

    public function risHeaders(): HasMany
    {
        return $this->hasMany(RisHeader::class);
    }
}
