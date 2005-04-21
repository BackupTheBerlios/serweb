<?
/*
 * $Id: method.get_status_visibility.php,v 1.2 2005/04/21 15:09:45 kozlik Exp $
 */

class CData_Layer_get_status_visibility {
	var $required_methods = array('get_attribute_of_user', 'get_uuid_of_user');
	
	/*
	 * get status visibility of sip user
	 * return: true if others can see whether user is online
	 */

	function get_status_visibility($user, $domain, &$errors){
		global $config;

		if (false === $uuid = $this->get_uuid_of_user($user, $domain, $errors)) return -1;

		$user_auth = new Cserweb_auth($uuid, $user, $domain);

		if (false === $val = $this->get_attribute_of_user($user_auth, $config->status_vibility, null, $errors)) return -1;

		return $val?true:false;
	}
	
}
?>
