<?
/*
 * $Id: method.check_admin_perms_to_user.php,v 1.1 2004/08/09 11:40:58 kozlik Exp $
 */

class CData_Layer_check_admin_perms_to_user {
	var $required_methods = array();
	
	/* check if $user domain is same as $admin domain */
	function check_admin_perms_to_user($admin, $user, &$errors){
		global $config;
		if ($config->users_indexed_by=='uuid'){
			if (false === $usr=$this->get_user_dom_from_uid($user->uuid, $errors)) return -1;
			
			return $admin->domain == $usr['domain'];
		}
		else
			return $admin->domain == $user->domain;
		
	}
	
}
?>
