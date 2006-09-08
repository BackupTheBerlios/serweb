<?php
/*
 * $Id: method.add_credentials.php,v 1.5 2006/09/08 12:27:31 kozlik Exp $
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
	 *	  for_ser	(bool)	default: null
	 *    	set flag DB_LOAD_SER
	 *		by default value of this flag depends on global attribute 'credential_default_flags'
	 *
	 *	  for_serweb	(bool)	default: null
	 *    	set flag DB_FOR_SERWEB
	 *		by default value of this flag depends on global attribute 'credential_default_flags'
	 *
	 *	@return bool
	 */ 
	 
	function add_credentials($uid, $did, $uname, $realm, $passw, $opt){
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
		$opt_disabled   = isset($opt["disabled"])   ? (bool)$opt["disabled"]   : false;
		$opt_for_ser    = isset($opt["for_ser"])    ? (bool)$opt["for_ser"]    : null;
		$opt_for_serweb = isset($opt["for_serweb"]) ? (bool)$opt["for_serweb"] : null;


		$ga = &Global_attrs::singleton();
		if (false === $flags = $ga->get_attribute($an['credential_default_flags'])) return false;
		if (!is_numeric($flags)){
			ErrorHandler::log_errors(PEAR::raiseError("Global attribute '".$ca['credential_default_flags']."' is not defined or is not a number Can't create credentials."));
			return false;
		}

		if ($opt_disabled) $flags = ($flags | $f['DB_DISABLED']);

		if (!is_null($opt_for_ser)){
			if ($opt_for_ser) $flags = ($flags |  $f['DB_LOAD_SER']);
			else              $flags = ($flags & ~$f['DB_LOAD_SER']);
		}

		if (!is_null($opt_for_serweb)){
			if ($opt_for_serweb) $flags = ($flags |  $f['DB_FOR_SERWEB']);
			else                 $flags = ($flags & ~$f['DB_FOR_SERWEB']);
		}

		$did_c = $did_v = "";
		$pw_c  = $pw_v  = "";

		if ($config->auth['use_did']){
			$did_c = $c->did.", ";
			$did_v = $this->sql_format($did,   "s").", ";
		}

		if ($config->clear_text_pw){
			$pw_c  = $c->password.", ";
			$pw_v  = $this->sql_format($passw, "s").", ";
		}

		$ha1  = md5($uname.":".$realm.":".$passw);
		$ha1b = md5($uname."@".$realm.":".$realm.":".$passw);

		$q = "insert into ".$t_name."(
	             ".$c->uid.", ".$did_c.$c->uname.", ".$c->realm.", ".$pw_c." 
				 ".$c->ha1.", ".$c->ha1b.", ".$c->flags.")
		      values (".$this->sql_format($uid,   "s").", 
		              ".$did_v."
			          ".$this->sql_format($uname, "s").", 
					  ".$this->sql_format($realm, "s").", 
		              ".$pw_v."
			          ".$this->sql_format($ha1,   "s").", 
					  ".$this->sql_format($ha1b,  "s").", 
					  ".$this->sql_format($flags, "n").")";

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>
