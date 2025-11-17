<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'mobile',
        'email',
        'address',
        'city',
        'type',
        'tax_number',
        'commercial_number',
        'contact_person',
    ];

    protected $casts = [
        'type' => 'string', // individual, business
        'contact_person' => 'array', // name, phone
    ];
    

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class)->latest();
    }


}
