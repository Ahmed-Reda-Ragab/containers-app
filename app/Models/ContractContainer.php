<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractContainer extends Model
{
    protected $fillable = [
        'contract_id',
        'size_id',
        'container_id',
        'no_of_containers',
        'monthly_dumping',
        'price_per_container',
        'additional_trip_price',
        'status',
        'filled_at',
        'discharged_at',
    ];

    protected $casts = [
        'price_per_container' => 'decimal:2',
        'additional_trip_price' => 'decimal:2',
        'filled_at' => 'datetime',
        'discharged_at' => 'datetime',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function getTotalPriceAttribute(): float
    {
        $containerTotal = $this->no_of_containers * $this->price_per_container;
        $dumpingTotal = $this->monthly_dumping * ($this->additional_trip_price ?? 0);
        return $containerTotal + $dumpingTotal;
    }
}
