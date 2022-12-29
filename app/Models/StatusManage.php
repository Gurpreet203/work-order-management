<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StatusManage extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'user_id',
        'status_id'
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    // scopes

    public function scopeClose($query, $workOrder, $user)
    {
        return $query->where('work_order_id', $workOrder->id)
            ->where('user_id', $user)
            ->where('status_id', Status::CLOSE);
    }
}
