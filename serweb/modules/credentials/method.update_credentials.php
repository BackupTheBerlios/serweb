<?php
/*
 * $Id: method.update_credentials.php,v 1.2 2006/09/08 12:27:34 kozlik Exp $
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
	 *	@param	string		$did
	 *	@param	string		$uname
	 *	@param	string		$realm
	 *	@param	Credential	$new_vals
	 *	@param	array		$opt
	 *	@return bool
	 */ 
	 
	function update_credentials($uid, $did, $uname, $realm, $new_vals, $opt){
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

		$set = array();
		if ($new_vals->did_changed()) {
			$set[] = $c->did." = ".$this->sql_format($new_vals->get_did(), "s");
		}
		if ($new_vals->uname_changed()) {
			$set[] = $c->uname." = ".$this->sql_format($new_vals->get_uname(), "s");
		}
		if ($new_vals->realm_changed()) {
			$set[] = $c->realm." = ".$this->sql_format($new_vals->get_realm(), "s");
		}
		if ($new_vals->password_changed()) {
			if ($config->clear_text_pw){
				$set[] = $c->password." = ".$this->sql_format($new_vals->get_password(), "s");
			}
			else{
				$set[] = $c->password." = ".$this->sql_format("", "s");
			}
		}
		if ($new_vals->ha1_changed()) {
			$set[] = $c->ha1." = ".$this->sql_format($new_vals->get_ha1(), "s");
			$set[] = $c->ha1b." = ".$this->sql_format($new_vals->get_ha1b(), "s");
		}
		if ($new_vals->flags_changed()) {
			$set[] = $c->flags." = ".$this->sql_format($new_vals->get_flags(), "s");
		}

		if (!count($set)) return true;  // nothing to change

		$q = "update ".$t_name."
			  set	".implode(", ", $set)."
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
