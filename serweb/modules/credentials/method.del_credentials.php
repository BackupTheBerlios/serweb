<?php
/*
 * $Id: method.del_credentials.php,v 1.2 2006/09/08 12:27:34 kozlik Exp $
 */

class CData_Layer_del_credentials {
	var $required_methods = array();
	
	/**
	 *  Delete credentials from DB
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *		none
	 *
	 *	@return bool
	 */ 
	 
	function del_credentials($uid, $did, $uname, $realm, $opt){
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


		$q = "delete from ".$t_name."
		      where ".$c->uid."   = ".$this->sql_format($uid,   "s")." and
		            ".$c->uname." = ".$this->sql_format($uname, "s")." and
		            ".$c->realm." = ".$this->sql_format($realm, "s");

		if ($config->auth['use_did']){
			$q .= " and ".$c->did." = ".$this->sql_format($did, "s");
		}

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>
