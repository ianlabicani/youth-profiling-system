<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barangay extends Model
{
    protected $fillable = [
        'name',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Get the youths in this barangay.
     */
    public function youths(): HasMany
    {
        return $this->hasMany(Youth::class);
    }

    /**
     * Get the SK Councils for this barangay.
     */
    public function skCouncils(): HasMany
    {
        return $this->hasMany(SKCouncil::class);
    }
}
