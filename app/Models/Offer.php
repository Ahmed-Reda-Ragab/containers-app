<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{
    protected $fillable = [
        'customer_id',
        'customer',
        'size_id',
        'container_price',
        'no_containers',
        'monthly_dumping_cont', // number of dumpings per month for one container
        'monthly_total_dumping_cost',
        'additional_trip_cost',
        'contract_period', // number of months
        'tax_value',
        'total_price',
        'total_payed',
        'total',
        'total_paid',
        'start_date',
        'end_date',
        'status',
        'notes',
        'user_id',
        'agreement_terms',
        'material_restrictions',
        'delivery_terms',
        'payment_policy',
        'valid_until',
    ];

    protected $casts = [
        'customer' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'valid_until' => 'date',
        'container_price' => 'decimal:2',
        'monthly_dumping_cont' => 'decimal:2',
        'monthly_total_dumping_cost' => 'decimal:2',
        'tax_value' => 'decimal:2',
        'total_price' => 'decimal:2',
        'total_payed' => 'decimal:2',
        'total' => 'decimal:2',
        'total_paid' => 'decimal:2',
    ];

    public function customerData(): BelongsTo
    {
        return $this->belongsTo(Customer::class , 'customer_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
    public function size(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
    

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function contractContainerFills(): HasMany
    {
        return $this->hasMany(ContractContainerFill::class);
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->total_price - $this->total_payed;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'active' => 'success',
            'expired' => 'danger',
            'canceled' => 'secondary',
            default => 'secondary'
        };
    }

    public function getIsNearExpiryAttribute(): bool
    {
        $referenceDate = $this->valid_until ?? $this->end_date;
        if (!$referenceDate) {
            return false;
        }

        return ($this->status === 'active')
            && $referenceDate->isFuture()
            && $referenceDate->diffInDays(now()) <= 10;
    }

    public function getLifecycleStatusAttribute(): string
    {
        $referenceDate = $this->valid_until ?? $this->end_date;
        if (in_array($this->status, ['expired', 'canceled'], true) || ($referenceDate && $referenceDate->isPast())) {
            return 'inactive';
        }

        return $this->is_near_expiry ? 'near_expiry' : 'active';
    }

    public function getLifecycleBadgeAttribute(): string
    {
        return match ($this->lifecycle_status) {
            'inactive' => 'secondary',
            'near_expiry' => 'warning',
            default => 'success',
        };
    }
    public function calculateMonthlyContainerPrice()
    {
        return $this->container_price * $this->no_containers * $this->monthly_dumping_cont;
    }
    public function calculateMonthlyExpectedCost()
    {
        return $this->container_price * $this->no_containers * $this->monthly_dumping_cont;
    }

    public function priceForNextContainer()
    {
        $count = $this->contractContainerFills()->count();
        $totalCount = $this->monthly_dumping_cont * $this->no_containers;
        return $totalCount > $count ? $this->container_price : $this->additional_trip_cost;
    }
    public function calculateMonthlyDumpingTotalPrice()
    {
        return $this->monthly_dumping_cont * $this->no_containers;
    }
    public function calculateAdditionalTripTotalPrice()
    {
        return $this->additional_trip_cost * $this->no_containers;
    }
    
}
