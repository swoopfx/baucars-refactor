<?php
namespace CsnUser\Service;

/**
 *
 * @author swoopfx
 *        
 */
class RoleService
{

    const GUEST = 1;

    const AGENT_SETUP = 2;

    const BROKER_SETUP = 3;

    const USER_PROFILE_SETUP = 4;

    const COMPANY_SETUP = 5;

    const USER = 10;

    const CUSTOMER = 25;

    const COMPANY = 50;

    const AGENT = 100;

    const BROKER = 200;

    const INSURANCE_COMPANY = 500;

    const SUPER_ADMIN = 1000;

    public function __construct()
    {}
}

