<?php

namespace App\Util;

enum PremiumDuration: int
{
    case DAYS_3 = 3;
    case DAYS_7 = 7;
    case DAYS_15 = 15;
}