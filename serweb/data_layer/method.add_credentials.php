<?php
/*
 * $Id: method.add_credentials.php,v 1.1 2005/12/22 13:44:41 kozlik Exp $
 */

class CData_Layer_add_credentials {
	var $required_methods = array();
	
	/**
	 *  Add new credentials to DB
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *	  disabled	(bool)	default: false
	 *    	set flag disabled
	 *
	 *	@return bool
	 */ 
	 
	function add_credentials($uid, $uname, $realm, $passw, $opt){
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

		$an = &$config->attr_names;


		/* set default values for options */
		$opt_disabled = isset($opt["disabled"]) ? (bool)$opt["disabled"] : false;


		$ga = &Global_attrs::singleton();
		if (false === $flags = &$ga->get_attribute($an['credential_default_flags'])) return false;

		if ($opt_disabled) $flags = ($flags | $f['DB_DISABLED']);

		$ha1  = md5($uname.":".$realm.":".$passw);
		$ha1b = md5($uname."@".$realm.":".$realm.":".$passw);

		$q = "insert into ".$t_name."(
	             ".$c->uid.", ".$c->uname.", ".$c->realm.", ".$c->password.", 
				 ".$c->ha1.", ".$c->ha1b.", ".$c->flags.")
		      values ('".$uid."', '".$uname."', '".$realm."', '".$passw."', 
			          '".$ha1."', '".$ha1b."', ".$flags.")";

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>
