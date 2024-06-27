<?php

namespace App\Util;

class UnixHelper
{
    static final function getLastMonthUnix(): int
    {
        return time() - strtotime('last month');
    }
}