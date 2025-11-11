<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organization extends Model
{
    protected $fillable = [
        'president_id',
        'vice_president_id',
        'secretary_id',
        'treasurer_id',
        'committee_heads',
        'members',
        'description',
    ];

    protected $casts = [
        'committee_heads' => 'array',
        'members' => 'array',
    ];

    public function president(): BelongsTo
    {
        return $this->belongsTo(Youth::class, 'president_id');
    }

    public function vicePresident(): BelongsTo
    {
        return $this->belongsTo(Youth::class, 'vice_president_id');
    }

    public function secretary(): BelongsTo
    {
        return $this->belongsTo(Youth::class, 'secretary_id');
    }

    public function treasurer(): BelongsTo
    {
        return $this->belongsTo(Youth::class, 'treasurer_id');
    }
}
