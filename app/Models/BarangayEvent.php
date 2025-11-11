<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangayEvent extends Model
{
    protected $table = 'barangay_events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'barangay_id',
        'sk_council_id',
        'title',
        'date',
        'time',
        'venue',
        'organizer',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the barangay that owns this event.
     */
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    /**
     * Get the SK Council associated with this event.
     */
    public function skCouncil(): BelongsTo
    {
        return $this->belongsTo(SKCouncil::class, 'sk_council_id');
    }
}
