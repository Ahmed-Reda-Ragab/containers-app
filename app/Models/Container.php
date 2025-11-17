<?php

namespace App\Models;

use App\Enums\ContainerStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Container extends Model
{
    protected $fillable = [
        'code',
        'size_id',
        'status',
        'description',
    ];

    protected $casts = [
        'status' => ContainerStatus::class,
    ];

    public function size(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'size_id');
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

    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        $fillable = $this->getFillable();

        foreach ($filters as $field => $value) {
            if (!in_array($field, $fillable, true)) {
                continue;
            }

            if ($value === null || $value === '') {
                continue;
            }

            if (is_array($value)) {
                $query->whereIn($field, array_filter($value, fn ($v) => $v !== null && $v !== ''));
            } else {
                $query->where($field, $value);
            }
        }

        return $query;
    }
}