<?php

namespace App\Util;

interface ContextGroup
{
    // User
    public final const USER_ALL_DETAILS = 'user_details';
    public final const ADMIN_USER_SEARCH = 'admin_user_search';
    public final const USER_WITH_PHONE = 'user_with_phone';

    // Company
    public final const COMPANY_ALL_DETAILS = 'company_details';
    public final const ADMIN_COMPANY_SEARCH = 'admin_company_search';

    // Ad
    public final const AD_ALL_DETAILS = 'ad_details';
    public final const ADMIN_AD_SEARCH = 'admin_ad_search';
    public final const AD_COMPLETE_INFO = 'ad_complete_info';
    public final const PREMIUM_INFO = 'premium_info';

    // Search
    public final const SEARCH = 'search';

    // Phone
    public final const PHONE_DETAILS = 'phone_details';
    public final const ADMIN_PHONE_SEARCH = 'admin_phone_search';

    // Image
    public final const IMAGE_DETAILS = 'image_details';
    public final const ADMIN_IMAGE_SEARCH = 'admin_image_search';

    // Credit Transaction Log
    public final const ADMIN_CREDIT_TRANSACTION_LOG = 'admin_credit_transaction_log';
    public final const USER_CREDIT_TRANSACTION_LOG = 'user_credit_transaction_log';

    // Promotion Log
    public final const ADMIN_PROMOTION_LOG = 'admin_promotion_log';
    public final const USER_PROMOTION_LOG = 'user_promotion_log';

    // Price Stats
    public final const ADMIN_PRICE_STATS = 'price_stats';
    public final const PRICE_STATS_DETAILS = 'price_stats_details';

    // Place
    public final const PLACE_DETAILS = 'place_details';

}