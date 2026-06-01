<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalMatrixStep extends Model
{
    protected $fillable = ['module', 'level', 'role_name', 'action_label', 'is_final', 'is_active'];
    protected $casts = ['is_final' => 'boolean', 'is_active' => 'boolean'];
}
