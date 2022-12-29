<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'first_name',
        'last_name',
        'slug',
        'email',
        'user_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['first_name', 'last_name']
            ]
        ];
    }

    // relations

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function assigned_to()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignment()
    {
        return $this->belongsToMany(WorkOrder::class, 'assignment')
            ->withPivot('id')
            ->withTimestamps();
    }

    public function attachment()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    // Attributes

    public function getCustomerAttribute()
    {
        return $this->role_id == Role::CUSTOMER;
    }

    public function getIsEmployeeAttribute()
    {
        return $this->role_id == Role::EMPLOYEE;
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    // scopes

    public function scopeVisibleTo($query)
    {
        if (Auth::user()->role_id == Role::ADMIN)
        {
            return $query->latest();
        }

        return $query->where('user_id', Auth::id())
            ->latest();
    }

    public function scopeSearch($query, array $fillter)
    {
        return $query->when($fillter['search'] ?? false, function($query, $search) {

            return $query->where('first_name', 'like', '%'.$search.'%')
                ->orwhere('email', 'like', '%'.$search.'%');
        });
    }

    public function scopeManagers($query)
    {
        return $query->where('role_id', Role::MANAGER);
    }

    public function scopeAssignedUsers($query)
    {
        return $query->where('user_id', Auth::id())
            ->latest();
    }

}
