<?php

namespace App\Enums;

enum OfferStatus: string
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => __('status.offer.draft'),    
            self::SENT => __('status.offer.sent'),
            self::ACCEPTED => __('status.offer.accepted'),
            self::REJECTED => __('status.offer.rejected'),
            self::EXPIRED => __('status.offer.expired'),
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'secondary',
            self::SENT => 'primary',
            self::ACCEPTED => 'success',
            self::REJECTED => 'danger',
            self::EXPIRED => 'warning',
        };
    }
}
