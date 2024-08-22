<?php

namespace App\Util;

interface ContextGroup
{
    public final const DETAILS = 'details';
    public final const USER_ALL_DETAILS = 'user_details';
    public final const COMPANY_ALL_DETAILS = 'company_details';
    public final const AD_ALL_DETAILS = 'ad_details';
    public final const SEARCH = 'search';
    public final const ADMIN_AD_SEARCH = 'admin_ad_search';
    public final const ADMIN_USER_SEARCH = 'admin_user_search';
    public final const ADMIN_COMPANY_SEARCH = 'admin_company_search';


}