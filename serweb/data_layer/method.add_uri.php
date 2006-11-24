<?php
/*
 * $Id: method.add_uri.php,v 1.6 2006/11/24 13:33:02 kozlik Exp $
 */

class CData_Layer_add_uri {
	var $required_methods = array();
	
	/**
	 *  Add new URI to DB
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *	  disabled	(bool)	default: false
	 *    	set flag disabled
	 *
	 *	  canon	(bool)	default: false
	 *    	set flag canonical
	 *
	 *	  flags	(int)	default: null
	 *    	Set value of flags directly. If is set, options 'canon' and 
	 *		'disabled' are ignored.
	 *
	 *	@return bool
	 */ 
	 
	function add_uri($uid, $scheme, $uname, $did, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table name */
		$t_name = &$config->data_sql->uri->table_name;
		/* col names */
		$c = &$config->data_sql->uri->cols;
		/* flags */
		$f = &$config->data_sql->uri->flag_values;

		$an = &$config->attr_names;


		/* set default values for options */
		$opt_disabled = isset($opt["disabled"]) ? (bool)$opt["disabled"] : false;
		$opt_canon    = isset($opt["canon"]) ? (bool)$opt["canon"] : false;
		$opt_flags    = isset($opt["flags"]) ? $opt["flags"] : null;


		if (!is_null($opt_flags)){
			$flags = $opt_flags;
		}
		else {
			$ga = &Global_attrs::singleton();
			if (false === $flags = $ga->get_attribute($an['uri_default_flags'])) return false;
			if (!is_numeric($flags)){
				ErrorHandler::log_errors(PEAR::raiseError("Global attribute '".$an['uri_default_flags']."' is not defined or is not a number Can't create URI."));
				return false;
			}
	
			if ($opt_disabled) $flags = ($flags | $f['DB_DISABLED']);
			if ($opt_canon)    $flags = ($flags | $f['DB_CANON']);
		}

		$q = "insert into ".$t_name."(
	             ".$c->uid.", ".$c->scheme.", ".$c->username.", ".$c->did.", ".$c->flags.")
		      values (".$this->sql_format($uid,   "s").", 
			          ".$this->sql_format($scheme,"s").", 
			          ".$this->sql_format($uname, "s").", 
					  ".$this->sql_format($did,   "s").", 
					  ".$this->sql_format($flags, "n").")";

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>
