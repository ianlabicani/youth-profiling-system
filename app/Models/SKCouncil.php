<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SKCouncil extends Model
{
    protected $table = 'sk_councils';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'barangay_id',
        'term',
        'chairperson_id',
        'secretary_id',
        'treasurer_id',
        'kagawad_ids',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'kagawad_ids' => 'array',
    ];

    /**
     * Get the barangay that owns this SK council.
     */
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    /**
     * Get the chairperson youth record.
     */
    public function chairperson(): BelongsTo
    {
        return $this->belongsTo(Youth::class, 'chairperson_id');
    }

    /**
     * Get the secretary youth record.
     */
    public function secretary(): BelongsTo
    {
        return $this->belongsTo(Youth::class, 'secretary_id');
    }

    /**
     * Get the treasurer youth record.
     */
    public function treasurer(): BelongsTo
    {
        return $this->belongsTo(Youth::class, 'treasurer_id');
    }

    /**
     * Get the kagawad (council members) youth records.
     */
    public function kagawads()
    {
        return Youth::whereIn('id', $this->kagawad_ids ?? [])->get();
    }
}
