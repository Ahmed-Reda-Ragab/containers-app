<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discharge extends Model
{
    protected $fillable = [
        'contract_id', 'container_id', 'customer_id', 'customer_name',
        'customer_phone', 'pickup_address', 'city', 'driver_id',
        'discharge_date', 'discharge_time', 'status', 'discharge_notes',
        'disposal_location', 'photo_proof',
    ];

    protected $casts = [
        'discharge_date' => 'date',
        'discharge_time' => 'datetime:H:i',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}