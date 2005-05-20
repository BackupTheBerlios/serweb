<?
/*
 * $Id: method.set_password_to_user.php,v 1.2 2005/05/20 10:08:20 kozlik Exp $
 */

class CData_Layer_set_password_to_user {
	var $required_methods = array();
	
	/*
	 *	set password for user
	 */

	function set_password_to_user($user, $passwd, &$errors){
		global $config;

		$ha1=md5($user->uname.":".$user->domain.":".$passwd);
		$ha1b=md5($user->uname."@".$user->domain.":".$user->domain.":".$passwd);

		if (!$this->connect_to_db($errors)) return false;

		$q="update ".$config->data_sql->table_subscriber." set password='".addslashes($passwd)."', ha1='$ha1', ha1b='$ha1b' ".
			" where ".$this->get_indexing_sql_where_phrase($user);

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;
	}
	
}
?>
