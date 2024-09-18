<?php

namespace App\Util;

interface ResponseMessage
{
    public const USER_CREATED = 'User created';
    public const USER_UPDATED = 'User updated';
    public const USER_DELETED = 'User deleted';
    public const COMPANY_CREATED = 'Company created';
    public const COMPANY_UPDATED = 'Company updated';
    public const COMPANY_DELETED = 'Company deleted';
    public const AD_CREATED = 'Ad created';
    public const AD_UPDATED = 'Ad updated';
    public const AD_DELETED = 'Ad deleted';
    public const AD_ACTIVATED = "Ad activated";
    public const AD_DEACTIVATED = "Ad deactivated";
    const PHONE_UPDATED = 'Phone updated';
    const PHONE_CREATED = 'Phone created';
    const PHONE_DELETED = 'Phone deleted';
}