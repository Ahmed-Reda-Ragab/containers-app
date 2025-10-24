<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    protected $table = 'sizes';
    protected $fillable = [
        'name',
    ];

    public function containers(): HasMany
    {
        return $this->hasMany(Container::class);
    }
}
