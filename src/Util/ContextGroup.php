<?php

namespace App\Util;

interface ContextGroup
{
    public final const USER_ALL_DETAILS = 'user_details';
    public final const USER_COMPANY = 'user_company_info';
    public final const COMPANY_ALL_DETAILS = 'company_details';
    public final const COMPANY_USERS = 'company_users_info';
    public final const AD_ALL_DETAILS = 'ad_details';
}