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
        'ext',
        // 'fax',
        // 'mobile',
        'city',
        'type',
        'tax_number',
        'commercial_number',
        'contact_person',
    ];

    protected $casts = [
        'type' => 'string',
        'contact_person' => 'array',
    ];
    

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }


}
