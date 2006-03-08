<?php
/*
 * $Id: method.add_credentials.php,v 1.3 2006/03/08 15:46:25 kozlik Exp $
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
		if (!is_numeric($flags)){
			ErrorHandler::log_errors(PEAR::raiseError("Global attribute '".$ca['credential_default_flags']."' is not defined or is not a number Can't create credentials."));
			return false;
		}

		if ($opt_disabled) $flags = ($flags | $f['DB_DISABLED']);

		$ha1  = md5($uname.":".$realm.":".$passw);
		$ha1b = md5($uname."@".$realm.":".$realm.":".$passw);

		$q = "insert into ".$t_name."(
	             ".$c->uid.", ".$c->uname.", ".$c->realm.", ".$c->password.", 
				 ".$c->ha1.", ".$c->ha1b.", ".$c->flags.")
		      values (".$this->sql_format($uid,   "s").", 
			          ".$this->sql_format($uname, "s").", 
					  ".$this->sql_format($realm, "s").", 
					  ".$this->sql_format($passw, "s").", 
			          ".$this->sql_format($ha1,   "s").", 
					  ".$this->sql_format($ha1b,  "s").", 
					  ".$this->sql_format($flags, "n").")";

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>
