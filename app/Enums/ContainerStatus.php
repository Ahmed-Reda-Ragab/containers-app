<?php

namespace App\Enums;

enum ContainerStatus: string
{
    case AVAILABLE = 'available';
    case IN_USE = 'in_use';
    case FILLED = 'filled';
    case MAINTENANCE = 'maintenance';
    case OUT_OF_SERVICE = 'out_of_service';
    
    public function label(): string
    {
        return match($this) {
            self::AVAILABLE => __('status.container.available'),
            self::IN_USE => __('status.container.in_use'),
            self::FILLED => __('status.container.filled'),
            self::MAINTENANCE => __('status.container.maintenance'),
            self::OUT_OF_SERVICE => __('status.container.out_of_service'),
        };
    }

    public function color(): string
    {
        return match($this) {
            self::AVAILABLE => 'success',
            self::IN_USE => 'primary',
            self::FILLED => 'warning',
            self::MAINTENANCE => 'warning',
            self::OUT_OF_SERVICE => 'danger',
        };
    }
}
