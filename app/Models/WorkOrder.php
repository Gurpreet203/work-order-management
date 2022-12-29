<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WorkOrder extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status_id',
        'assigned_to'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    // scopes

    public function scopeVisibleTo($query)
    {
        if (Auth::user()->role_id == Role::ADMIN)
        {
            return $query->whereNot('status_id', Status::CLOSE)
                ->latest();
        }

        return $query->where('user_id', Auth::id())
            ->latest();
    }

    public function scopevisibleToAssignee($query)
    {
        return $query->where('assigned_to', Auth::id());
    }

    public function scopeSearch($query, array $fillter)
    {
        return $query->when($fillter['search'] ?? false, function($query, $search) {

            return $query->where('title', 'like', '%'.$search.'%');
        });
    }

    //relations

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function assignment()
    {
        return $this->belongsToMany(User::class, 'assignment')
            ->withPivot('id', 'user_id', 'assigned_by')
            ->withTimestamps()
            ->wherePivot('user_id', Auth::id());
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    public function StatusManage()
    {
        return $this->hasOne(StatusManage::class)
            ->latestOfMany()
            ->with('status');
    }

    // functions

    public function exist($assigned_to, $user_id, $workOrder)
    {
        return Progress::where('assigned_to', $assigned_to)
            ->where('user_id', $user_id)
            ->where('work_order_id', $workOrder->id)
            ->first();
    }
}
