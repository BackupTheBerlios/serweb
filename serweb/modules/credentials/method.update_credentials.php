<?php
/*
 * $Id: method.update_credentials.php,v 1.1 2006/03/22 14:00:15 kozlik Exp $
 */

class CData_Layer_update_credentials {
	var $required_methods = array();
	
	/**
	 *  Delete credentials from DB
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *		none
	 *
	 *	@param	string		$uid
	 *	@param	string		$uname
	 *	@param	string		$realm
	 *	@param	Credential	$new_vals
	 *	@param	array		$opt
	 *	@return bool
	 */ 
	 
	function update_credentials($uid, $uname, $realm, $new_vals, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table name */
		$t_name = &$config->data_sql->credentials->table_name;
		/* col names */
		$c = &$config->data_sql->credentials->cols;
		/* flags */
		$f = &$config->data_sql->credentials->flag_values;


		$q = "update ".$t_name."
			  set	".$c->uname."    = ".$this->sql_format($new_vals->get_uname(),    "s").",
			        ".$c->realm."    = ".$this->sql_format($new_vals->get_realm(),    "s").",
			        ".$c->password." = ".$this->sql_format($new_vals->get_password(), "s").",
			        ".$c->flags."    = ".$this->sql_format($new_vals->get_flags(),    "s").",
			        ".$c->ha1."      = ".$this->sql_format($new_vals->get_ha1(),      "s").",
			        ".$c->ha1b."     = ".$this->sql_format($new_vals->get_ha1b(),     "s")."
		      where ".$c->uid."   = ".$this->sql_format($uid,   "s")." and
		            ".$c->uname." = ".$this->sql_format($uname, "s")." and
		            ".$c->realm." = ".$this->sql_format($realm, "s");

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>
