<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */

namespace Agilelogics\CustomCatalog\Api;

interface UpdateManagementInterface
{
    /**
     * Case 2.2.2
     * HTTP-Rest-Call
     * [Method: Put]
     * API for update VPN
     * @params: list of params
     * @return[response]: response string
     */

    public function putUpdate($param);
}
