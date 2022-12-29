<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Auth;

class Assignment extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'user_id',
        'assigned_by'
    ];

    // relations

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);

    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    // // scopes

    // public function scopeVisibleTo($query)
    // {
    //     return $query->where('assigned_to', Auth::id())
    //         ->latest();
    // }

    public function scopeExist($query, $workOrder)
    {
        return $query->where('work_order_id', $workOrder->id)
            ->latest();
    }
}
