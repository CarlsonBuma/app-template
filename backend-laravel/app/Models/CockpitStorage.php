<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CockpitStorage extends Model
{
    use HasFactory;

    protected $table = 'public.cockpit_storage';
    protected $fillable = [
        'is_public',
        'file_id', 
        'cockpit_id', 
        'bucket', 
        'prefix',
        'name',
        'url',
        'url_download',
        'content_type',
        'size_bytes'
    ];

    /**
     * Undocumented function
     *
     * @return BelongsTo
     */
    public function belongs_to_cockpit(): BelongsTo
    {
        return $this->belongsTo(Cockpit::class, 'cockpit_id');
    }
}

