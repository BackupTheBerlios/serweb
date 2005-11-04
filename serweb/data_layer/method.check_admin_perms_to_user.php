<?
/*
 * $Id: method.check_admin_perms_to_user.php,v 1.3 2005/11/04 13:23:18 kozlik Exp $
 */

class CData_Layer_check_admin_perms_to_user {

	function _get_required_methods(){
		global $config;

		$out = array();
		if ($config->multidomain) $out[] = 'get_domain';
		if ($config->users_indexed_by=='uuid') $out[] = 'get_user_dom_from_uid';
		return $out;
	}
	
	/* check if $user domain is same as $admin domain */
	function check_admin_perms_to_user($admin, $user, &$errors){
		global $config;

		/* if users are indexed by uuid, get the real domain from DB in order 
		 * to domain can't be faked 
		 */
		if ($config->users_indexed_by=='uuid'){
			if (false === $usr=$this->get_user_dom_from_uid($user->uuid, $errors)) return -1;
			$user->domain = $usr['domain'];
		}

		if ($config->multidomain){
			$opt['filter']['name'] = $user->domain;
			$opt['order_by'] = "";
			if (false === $dom=$this->get_domain($opt, $errors)) return -1;
			
			$dom = reset($dom);					/* get first field of array */
			if (false === $dom) return false;	/* if returned array is empty (domain not exists) */
			
			if (in_array($dom['id'], $admin->domains_perm)) return true;
			return false;
		}
		else{
			return $admin->domain == $user->domain;
		}
		
	}
	
}
?>
