<?php

namespace App\Util;

enum AdStatus: string
{
    case ACTIVE = 'active';
    case ARCHIVE = 'archive';
    case DELETED = 'deleted';
    case DRAFT = 'draft';
    case HIDDEN = 'hidden';

    public function isActive(): bool
    {
        return match ($this) {
            self::ACTIVE => true,
            default => false
        };
    }

    public function isDeleted(): bool
    {
        return match ($this) {
            self::DELETED => true,
            default => false
        };
    }

    public function isDraft(): bool
    {
        return match ($this) {
            self::DRAFT => true,
            default => false
        };
    }

    public function isHidden(): bool
    {
        return match ($this) {
            self::HIDDEN => true,
            default => false
        };
    }
}