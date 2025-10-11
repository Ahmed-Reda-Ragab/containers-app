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
        'monthly_dumping_cont',
        'dumping_cost',
        'monthly_total_dumping_cost',
        'additional_trip_cost',
        'contract_period',
        'tax_value',
        'total_price',
        'total_payed',
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
        'dumping_cost' => 'decimal:2',
        'monthly_total_dumping_cost' => 'decimal:2',
        'additional_trip_cost' => 'decimal:2',
        'tax_value' => 'decimal:2',
        'total_price' => 'decimal:2',
        'total_payed' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function type(): BelongsTo
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
}
