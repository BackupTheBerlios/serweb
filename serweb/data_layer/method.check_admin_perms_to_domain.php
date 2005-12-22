<?php
/*
 * $Id: method.check_admin_perms_to_domain.php,v 1.3 2005/12/22 13:17:46 kozlik Exp $
 */

class CData_Layer_check_admin_perms_to_domain {
	var $required_methods = array();
	
	/**
	 *  check if admin have permissions to change domain setting
	 *
	 *  Possible options parameters:
	 *	 none
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
