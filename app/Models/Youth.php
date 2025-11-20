<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Youth extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'barangay_id',
        'photo',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'date_of_birth',
        'sex',
        'purok',
        'municipality',
        'province',
        'contact_number',
        'email',
        'guardians',
        'siblings',
        'household_income',
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
        'guardians' => 'array',
        'siblings' => 'array',
        'date_of_birth' => 'date',
        'household_income' => 'decimal:2',
    ];

    public function getNameAttribute()
    {
        return $this->first_name.', '.$this->last_name;
    }

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.($this->middle_name ? substr($this->middle_name, 0, 1).'. ' : '').$this->last_name;
    }

    /**
     * Get the barangay that this youth belongs to.
     */
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    /**
     * Get SK councils where this youth is the chairperson.
     */
    public function chairmanOf(): HasMany
    {
        return $this->hasMany(SKCouncil::class, 'chairperson_id');
    }

    /**
     * Get SK councils where this youth is the secretary.
     */
    public function secretaryOf(): HasMany
    {
        return $this->hasMany(SKCouncil::class, 'secretary_id');
    }

    /**
     * Get SK councils where this youth is the treasurer.
     */
    public function treasurerOf(): HasMany
    {
        return $this->hasMany(SKCouncil::class, 'treasurer_id');
    }
}
