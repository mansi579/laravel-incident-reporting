<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class IncidentLog extends Model
{
    use HasFactory;
      protected $fillable = [
        'user_id', 'action', 'auditable_type', 'auditable_id', 'data', 'ip_address',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
