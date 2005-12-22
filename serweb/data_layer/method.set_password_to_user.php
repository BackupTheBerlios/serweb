<?
/*
 * $Id: method.set_password_to_user.php,v 1.3 2005/12/22 13:22:24 kozlik Exp $
 */

class CData_Layer_set_password_to_user {
	var $required_methods = array();
	
	/*
	 *	set password for user
	 */

	function set_password_to_user($user, $passwd, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		/* table name */
		$t_name = &$config->data_sql->credentials->table_name;
		/* col names */
		$c = &$config->data_sql->credentials->cols;
		/* flags */
		$f = &$config->data_sql->credentials->flag_values;

		$ha1=md5($user->uname.":".$user->domain.":".$passwd);
		$ha1b=md5($user->uname."@".$user->domain.":".$user->domain.":".$passwd);

		$q="update ".$t_name." 
		    set ".$c->password." = '".addslashes($passwd)."', 
			    ".$c->ha1." = '$ha1', 
				".$c->ha1b." = '$ha1b'
			where ".$c->uid." = '".$user->uuid."' and
			      ".$c->uname." = '".$user->uname."' and
				  ".$c->realm." = '".$user->domain."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;
	}
	
}
?>
