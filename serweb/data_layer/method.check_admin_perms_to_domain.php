<?php
/*
 * $Id: method.check_admin_perms_to_domain.php,v 1.2 2005/10/07 14:17:14 kozlik Exp $
 */

class CData_Layer_check_admin_perms_to_domain {
	var $required_methods = array();
	
	/**
	 *  check if admin have permissions to change domain setting
	 *
	 *  Possible options parameters:
	 *	 none
	 *
	 *	@param object $admin		admin - instance of class Cserweb_auth
	 *	@param string $domain_id	id of domain
	 *	@param array $opt			associative array of options
	 *	@param array $errors		error messages
	 *	@return bool				TRUE on permit, FALSE on forbid, -1 on failure
	 */ 
	function check_admin_perms_to_domain($admin, $domain_id, $opt, &$errors){
		if (isset($admin->domains_perm) and is_array($admin->domains_perm)){
			if (in_array($domain_id, $admin->domains_perm)) return true;
		}
		
		return false;
	}
	
}
?>
