<?php

namespace App\Util;

interface ContextGroup
{
    public const USER_DETAILS = 'user_details';
    public const USER_COMPANY = 'user_company_info';
    public const COMPANY_DETAILS = 'company_details';
    public const COMPANY_USERS = 'company_users_info';
    public const AD_DETAILS = 'ad_details';
}