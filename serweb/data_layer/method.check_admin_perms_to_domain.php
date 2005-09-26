<?php
/*
 * $Id: method.check_admin_perms_to_domain.php,v 1.1 2005/09/26 10:56:54 kozlik Exp $
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
	 *	@param object $auth			instance of class Auth
	 *	@param string $domain_id	id of domain
	 *	@param array $opt			associative array of options
	 *	@param array $errors		error messages
	 *	@return bool				TRUE on permit, FALSE on forbid, -1 on failure
	 */ 
	function check_admin_perms_to_domain($admin, $auth, $domain_id, $opt, &$errors){
		if (isset($auth->auth['domains_perm']) and is_array($auth->auth['domains_perm'])){
			if (in_array($domain_id, $auth->auth['domains_perm'])) return true;
		}
		
		return false;
	}
	
}
?>
