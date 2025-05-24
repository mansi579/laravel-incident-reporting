<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use App\Traits\Auditable;

class Incident extends Model
{
    use HasFactory, SoftDeletes, Auditable;

     protected $fillable = [
        'title',
        'description',
        'file',
        'date',
        'category_id',
        'priority',
        'status',
        'started_at',
        'resolved_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function logs()
    {
        return $this->hasMany(IncidentLog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
