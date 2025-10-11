<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'company_name',
        'notes',
        'contact_person',
        'telephone',
        'ext',
        'fax',
        'mobile',
        'city',
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
