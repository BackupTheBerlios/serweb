<?
/*
 * $Id: method.delete_sip_user.php,v 1.4 2006/04/10 15:01:12 kozlik Exp $
 */

class CData_Layer_delete_sip_user {
	var $required_methods = array('delete_user_speed_dial', 
		'delete_user_msilo', 'delete_user_vsilo', 'delete_user_phonebook', 'delete_user_attrs', 
		'delete_user_acl', 'delete_user_uri', 'delete_user_credentials',
		'delete_user_missed_calls');
	
	function delete_sip_user($uid){
	 	global $config;

		if (false === $this->transaction_start()) return false;

		if (false === $this->delete_user_speed_dial($uid)) { $this->transaction_rollback(); return false;}
		if (false === $this->delete_user_msilo($uid)) { $this->transaction_rollback(); return false;}
//		if (false === $this->delete_user_vsilo($user, $errors)) { $this->transaction_rollback(); return false;}
		if (false === $this->delete_user_missed_calls($uid, NULL)) { $this->transaction_rollback(); return false;}
		if (false === $this->delete_user_phonebook($uid)) { $this->transaction_rollback(); return false;}
		if (false === $this->delete_user_attrs($uid)) { $this->transaction_rollback(); return false;}
		if (false === $this->delete_user_acl($uid)) { $this->transaction_rollback(); return false;}
		if (false === $this->delete_user_uri($uid)) { $this->transaction_rollback(); return false;}
		if (false === $this->delete_user_credentials($uid)) { $this->transaction_rollback(); return false;}

		if (false === $this->transaction_commit()) return false;
		
		return true;
	}
	
}
?>
