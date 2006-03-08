<?
/*
 * $Id: method.set_password_to_user.php,v 1.4 2006/03/08 15:46:25 kozlik Exp $
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
		    set ".$c->password." = ".$this->sql_format($passwd, "s").", 
			    ".$c->ha1."      = ".$this->sql_format($ha1,    "s").", 
				".$c->ha1b."     = ".$this->sql_format($ha1b,   "s")."
			where ".$c->uid."   = ".$this->sql_format($user->uuid,   "s")." and
			      ".$c->uname." = ".$this->sql_format($user->uname,  "s")." and
				  ".$c->realm." = ".$this->sql_format($user->domain, "s");

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;
	}
	
}
?>
