<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'work_order_id',
        'attachmentable_id',
        'attachmentable_type',
        'extension',
        'progress_id'
    ];

    public function attachmentable()
    {
        return $this->morphTo();
    }
}
