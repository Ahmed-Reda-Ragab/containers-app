<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_type',
        'password',
        'smartcar_access_token',
        'smartcar_refresh_token',
        'smartcar_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'smartcar_expires_at' => 'datetime',
            'user_type' => UserType::class,
        ];
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function deliveredContainers()
    {
        return $this->hasMany(ContractContainerFill::class, 'deliver_id');
    }

    public function dischargedContainers()
    {
        return $this->hasMany(ContractContainerFill::class, 'discharge_id');
    }

    // Scopes
    public function scopeByType($query, UserType $type)
    {
        return $query->where('user_type', $type);
    }

    public function scopeSupervisors($query)
    {
        return $query->where('user_type', UserType::SUPERVISOR);
    }

    public function scopeAdmins($query)
    {
        return $query->where('user_type', UserType::ADMIN);
    }

    public function scopeDrivers($query)
    {
        return $query->where('user_type', UserType::DRIVER);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getUserTypeLabelAttribute()
    {
        return $this->user_type?->label();
    }

    public function getUserTypeColorAttribute()
    {
        return $this->user_type?->color();
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->user_type === UserType::ADMIN;
    }

    public function isSupervisor(): bool
    {
        return $this->user_type === UserType::SUPERVISOR;
    }

    public function isDriver(): bool
    {
        return $this->user_type === UserType::DRIVER;
    }
}
