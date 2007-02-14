<?
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.set_password_to_user.php,v 1.6 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for set password of user
 * 
 *	@package    serweb
 */ 
class CData_Layer_set_password_to_user {
	var $required_methods = array();
	
	/**
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

		$ha1=md5($user->get_username().":".$user->get_realm().":".$passwd);
		$ha1b=md5($user->get_username()."@".$user->get_realm().":".$user->get_realm().":".$passwd);

		if (!$config->clear_text_pw)	$passwd = "";

		$q="update ".$t_name." 
		    set ".$c->password." = ".$this->sql_format($passwd, "s").", 
			    ".$c->ha1."      = ".$this->sql_format($ha1,    "s").", 
				".$c->ha1b."     = ".$this->sql_format($ha1b,   "s")."
			where ".$c->uid."   = ".$this->sql_format($user->get_uid(),      "s")." and
			      ".$c->uname." = ".$this->sql_format($user->get_username(), "s")." and
				  ".$c->realm." = ".$this->sql_format($user->get_realm(),    "s");

		if ($config->auth['use_did']){
			$q .= " and ".$c->did." = ".$this->sql_format($user->get_did(), "s");
		}

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;
	}
	
}
?>
