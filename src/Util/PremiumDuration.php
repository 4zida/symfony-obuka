<?php

namespace App\Util;

enum PremiumDuration: int
{
    use DaysAwareTrait;
    case DAYS_3 = 3;
    case DAYS_7 = 7;
    case DAYS_15 = 15;
}