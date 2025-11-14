<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'title',
    ];

    /**
     * Get the title attribute, fallback to name if not set
     */
    public function getDisplayTitleAttribute()
    {
        return $this->title ?? $this->name;
    }
}

