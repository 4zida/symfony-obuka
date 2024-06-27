<?php

namespace App\Util;

class UnixHelper
{
    const DAY = 86400;
    static final function getLastMonthUnix(): int
    {
        return time() - strtotime('last month');
    }

}