<?
/*
 * $Id: method.delete_sip_user.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_delete_sip_user {
	var $required_methods = array('delete_user_calls_forwarding', 'delete_user_speed_dial', 'delete_user_events', 
		'delete_user_msilo', 'delete_user_vsilo', 'delete_user_phonebook', 'delete_user_usr_preferences', 
		'delete_user_acl', 'delete_user_admin_privileges', 'delete_user_aliases', 'delete_user_from_subscriber',
		'delete_user_missed_calls');
	
	function delete_sip_user($user, &$errors){
	 	global $config;

		if (false === $this->delete_user_calls_forwarding($user, $errors)) return false;
		if (false === $this->delete_user_speed_dial($user, $errors)) return false;
		if (false === $this->delete_user_events($user, $errors)) return false;
		if (false === $this->delete_user_msilo($user, $errors)) return false;
		if (false === $this->delete_user_vsilo($user, $errors)) return false;
		if (false === $this->delete_user_missed_calls($user, NULL, $errors)) return false;
		if (false === $this->delete_user_phonebook($user, $errors)) return false;
		if (false === $this->delete_user_usr_preferences($user, $errors)) return false;
		if (false === $this->delete_user_acl($user, $errors)) return false;
		if (false === $this->delete_user_admin_privileges($user, $errors)) return false;
		if (false === $this->delete_user_aliases($user, $errors)) return false;
		if (false === $this->delete_user_from_subscriber($user, $errors)) return false;

	}
	
}
?>
