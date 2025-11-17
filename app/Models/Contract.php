<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    Const BUSINESS_TYPE = 'business';
    Const INDIVIDUAL_TYPE = 'individual';
    protected $fillable = [
        'customer_id',
        'type',
        'customer',
        'size_id',
        'container_price',
        'container_price_w_vat',
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
        'container_price_w_vat' => 'decimal:2',
        'monthly_dumping_cont' => 'decimal:2',
        // 'dumping_cost' => 'decimal:2',
        'monthly_total_dumping_cost' => 'decimal:2',
        // 'additional_trip_cost' => 'decimal:2',
        'tax_value' => 'decimal:2',
        'total_price' => 'decimal:2',
        'total_payed' => 'decimal:2',
        'total' => 'decimal:2',
        'total_paid' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class , 'customer_id');
    }
    public function customerData(): BelongsTo
    {
        return $this->belongsTo(Customer::class , 'customer_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Contract $contract) {
            // Generate contract number based on type prefix
            $prefix = strtolower($contract->type ?? 'business') === 'individual' ? 'IND' : 'BUS';
            // number format: PREFIX-YYYYMM-XXXX
            $datePart = now()->format('Ym');
            $sequence = str_pad((string) (Contract::where('type', $contract->type)->count() + 1), 4, '0', STR_PAD_LEFT);
            $contract->number = sprintf('%s-%s', $prefix, $sequence);
        });
    }
    public function isBusiness()
    {
        return str_starts_with($this->number, 'BUS') ? true : false;
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

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->total - $this->total_paid;
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
        if (!$this->end_date) {
            return false;
        }

        return $this->status === 'active'
            && $this->end_date->isFuture()
            && $this->end_date->diffInDays(now()) <= 15;
    }

    public function getLifecycleStatusAttribute(): string
    {
        if (in_array($this->status, ['expired', 'canceled'], true) || ($this->end_date && $this->end_date->isPast())) {
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
    public function getVisiteEveryDayAttribute() {
        return 30 / $this->monthly_dumping_cont??30; 
    }
    public function priceForNextContainer()
    {
        $count = $this->contractContainerFills()->count();
        $totalCount = $this->monthly_dumping_cont * $this->no_containers * $this->contract_period;
        return $totalCount > $count ? $this->container_price_w_vat :  $this->additional_trip_cost * (1.15);
    }
    
    public function calculateMonthlyDumpingTotalPrice()
    {
        return $this->container_price * $this->no_containers * $this->monthly_dumping_cont;
    }

    public function getVatValueAttribute()
    {
        return $this->calculateMonthlyDumpingTotalPrice() * $this->tax_value / 100;
    }
    public function calculateAdditionalTripTotalPrice()
    {
        return $this->additional_trip_cost * $this->no_containers;
    }

    // public function getPriceWithVatAttribute()
    // {
    //     return $this->price + ($this->price * $this->vat_rate / 100);
    // }
    public function getTotalContractContainersAttribute()
    {
        return $this->no_containers * $this->contract_period * $this->monthly_dumping_cont;
    }

}
