<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */

namespace Agilelogics\CustomCatalog\Api;

interface ProductManagementInterface 
{
     /**
      * Case 2.2.1
     * HTTP-Rest-Call
     * [Method: Get] 
     * API for getting VPN
     * @param[request]: vpn text value
     * @return[response]: value[string]
     */
    public function getByVPN($vpn);
	
    /**
     * Case 2.2.2
     * HTTP-Rest-Call
     * [Method: Post]
     * API for update VPN
     * @params[request]: {entity_id, copywrite_info, vpn}
     * @return[response]: response / false
     */
    public function update($entity_id,$copywrite_info,$vpn);
}
