<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Youth extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'photo',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'date_of_birth',
        'sex',
        'purok',
        'barangay',
        'municipality',
        'province',
        'contact_number',
        'email',
        'educational_attainment',
        'skills',
        'latitude',
        'longitude',
        'status',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'skills' => 'array',
        'date_of_birth' => 'date',
    ];
}
