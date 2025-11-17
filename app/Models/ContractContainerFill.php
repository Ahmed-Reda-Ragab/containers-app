<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractContainerFill extends Model
{
    protected $fillable = [
        'contract_id',
        'receipt_id',
        // 'no',
        'deliver_id',
        'deliver_at',
        'container_id',
        'expected_discharge_date',
        'discharge_date',
        'discharge_id',
        'price',
        'client_id',
        'city',
        'address',
        'notes',
        'deliver_car_id',
        'discharge_car_id',
    ];

    protected $casts = [
        'deliver_at' => 'date',
        'expected_discharge_date' => 'date',
        'discharge_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function deliver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deliver_id');
    }

    public function discharge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'discharge_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class);
    }

    public function deliverCar(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'deliver_car_id');
    }

    public function dischargeCar(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'discharge_car_id');
    }

    public function getIsDischargedAttribute(): bool
    {
        return !is_null($this->discharge_date);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->expected_discharge_date < now() && !$this->is_discharged;
    }
    public function getIsDischargedDateAttribute(): bool
    {
        return $this->discharge_date;
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
