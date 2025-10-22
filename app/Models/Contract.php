<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    protected $fillable = [
        'customer_id',
        'customer',
        'type_id',
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
        return $this->belongsTo(Customer::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Contract $contract) {
            // Ensure default customer type is Business if not set
            $customer = is_array($contract->customer) ? $contract->customer : [];
            if (!isset($customer['type']) || empty($customer['type'])) {
                $customer['type'] = 'business';
            }

            // Generate contract number based on type prefix
            $prefix = strtolower($customer['type'] ?? 'business') === 'individual' ? 'IND' : 'BUS';
            // number format: PREFIX-YYYYMM-XXXX
            $datePart = now()->format('Ym');
            $sequence = str_pad((string) (Contract::whereYear('created_at', now()->year)->count() + 1), 4, '0', STR_PAD_LEFT);
            $contract->number = sprintf('%s-%s-%s', $prefix, $datePart, $sequence);

            $contract->customer = $customer;
        });
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
        return $totalCount > $count ? $this->container_price : $this->additional_trip_cost;
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
    
}
