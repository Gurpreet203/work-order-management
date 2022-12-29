<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'description',
        'user_id',
        'assigned_to',
        'status_id'
    ];

    //relations

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assigned_user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function attachment()
    {
        return $this->hasOne(Attachment::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    //scopes
    
    public function scopeVisibleTo($query, $workOrder)
    {
        return $query->where('work_order_id', $workOrder->id);
    }
}
