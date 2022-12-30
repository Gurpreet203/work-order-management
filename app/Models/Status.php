<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory, Sluggable;

    const OPEN = 1;
    const INPROGRESS = 2;
    const RESOLVE = 3;
    const CLOSE = 4;
    const REOPEN = 5;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
            ];
    }
}
