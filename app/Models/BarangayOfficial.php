<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangayOfficial extends Model
{
    protected $fillable = [
        'name',
        'position',
        'order',
        'photo_path',
        'contact_number',
        'email',
        'bio',
        'term_start',
        'term_end',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'term_start' => 'date',
        'term_end' => 'date',
    ];
}
