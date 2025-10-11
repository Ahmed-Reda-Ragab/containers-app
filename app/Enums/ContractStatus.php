<?php

namespace App\Enums;

enum ContractStatus: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => __('status.contract.active'),
            self::EXPIRED => __('status.contract.expired'),
            self::CANCELED => __('status.contract.canceled'),
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'success',
            self::EXPIRED => 'warning',
            self::CANCELED => 'danger',
        };
    }
}
