<?php
namespace App\Traits;

use App\Models\IncidentLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logAudit('created');
        });

        static::updated(function ($model) {
            $model->logAudit('updated');
        });

        static::deleted(function ($model) {
            $model->logAudit('deleted');
        });
    }

    public function logAudit($action)
    {
        IncidentLog::create([
            'user_id'        => Auth::id(),
            'action'         => $action,
            'auditable_type' => get_class($this),
            'auditable_id'   => $this->id,
            'data'           => request()->except(['_token', '_method']),
            'ip_address'     => Request::ip(),
        ]);
    }
}
