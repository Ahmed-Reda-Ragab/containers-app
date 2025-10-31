<?php

namespace App\Enums;

enum UserType: string
{
    case SUPERVISOR = 'supervisor';
    case ADMIN = 'admin';
    case DRIVER = 'driver';

    public function label(): string
    {
        return match($this) {
            self::SUPERVISOR => __('user.type.supervisor'),
            self::ADMIN => __('user.type.admin'),
            self::DRIVER => __('user.type.driver'),
        };
    }

    public function color(): string
    {
        return match($this) {
            self::SUPERVISOR => 'primary',
            self::ADMIN => 'danger',
            self::DRIVER => 'success',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(function ($case) {
            return [$case->value => $case->label()];
        })->toArray();
    }
}
