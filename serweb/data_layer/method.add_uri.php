<?php
/*
 * $Id: method.add_uri.php,v 1.3 2006/01/12 14:40:47 kozlik Exp $
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
	 *	@return bool
	 */ 
	 
	function add_uri($uid, $uname, $did, $opt){
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

		$ga = &Global_attrs::singleton();
		if (false === $flags = $ga->get_attribute($an['uri_default_flags'])) return false;
		if (!is_numeric($flags)){
			ErrorHandler::log_errors(PEAR::raiseError("Global attribute '".$ca['uri_default_flags']."' is not defined or is not a number Can't create URI."));
			return false;
		}

		if ($opt_disabled) $flags = ($flags | $f['DB_DISABLED']);
		if ($opt_canon)    $flags = ($flags | $f['DB_CANON']);

		$q = "insert into ".$t_name."(
	             ".$c->uid.", ".$c->username.", ".$c->did.", ".$c->flags.")
		      values ('".$uid."', '".$uname."', '".$did."', ".$flags.")";

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>
