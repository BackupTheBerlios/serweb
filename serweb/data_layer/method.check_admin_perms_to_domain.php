<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.check_admin_perms_to_domain.php,v 1.4 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for get admin permissions to given domain
 * 
 *	@package    serweb
 */ 
class CData_Layer_check_admin_perms_to_domain {
	var $required_methods = array();
	
	/**
	 *  check if admin have permissions to change domain setting
	 *
	 *  Possible options parameters:
	 *	 - none
	 *
	 *	@param object $admin		admin - instance of class Auth
	 *	@param string $domain_id	id of domain
	 *	@param array $opt			associative array of options
	 *	@return bool				TRUE on permit, FALSE on forbid, -1 on failure
	 */ 
	function check_admin_perms_to_domain(&$admin, $domain_id, $opt){

		if (false === $adm_domains = $admin->get_administrated_domains()) return -1;

		if (in_array($domain_id, $adm_domains)) return true;

		return false;
	}
	
}
?>
