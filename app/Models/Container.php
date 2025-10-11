<?php

namespace App\Models;

use App\Enums\ContainerStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Container extends Model
{
    protected $fillable = [
        'code',
        'type_id',
        'size',
        'status',
        'description',
    ];

    protected $casts = [
        'status' => ContainerStatus::class,
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function contractContainers(): HasMany
    {
        return $this->hasMany(ContractContainer::class);
    }

    public function offerContainers(): HasMany
    {
        return $this->hasMany(OfferContainer::class);
    }

    public function contracts()
    {
        return $this->belongsToMany(Contract::class, 'contract_containers')
                    ->withPivot(['no_of_containers', 'monthly_dumping', 'price_per_container', 'additional_trip_price'])
                    ->withTimestamps();
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_containers')
                    ->withPivot(['no_of_containers', 'monthly_dumping_per_container', 'total_monthly_dumping', 'price_per_container', 'monthly_price'])
                    ->withTimestamps();
    }
}