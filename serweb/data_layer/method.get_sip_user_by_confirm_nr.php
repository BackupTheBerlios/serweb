<?php
/*
 * $Id: method.get_sip_user_by_confirm_nr.php,v 1.1 2005/04/26 21:09:44 kozlik Exp $
 */

class CData_Layer_get_sip_user_by_confirm_nr {
	var $required_methods = array();
	
	function get_sip_user_by_confirm_nr($confirm, $opt, &$errors){
		global $auth, $config, $lang_str;

		if (!$this->connect_to_db($errors)) return false;
	
		//which attributes will be selected
		if ($config->users_indexed_by=='uuid') 	$attributes="uuid";
		else $attributes="phplib_id as uuid";
		
		//formulate query
		$q="select ".$attributes.", email_address, username, domain ".
		   "from ".$config->data_sql->table_subscriber." ".
		   "where confirmation='".$confirm."'";

		//query db
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		if (!$res->numRows()) {
			 return false;
		}

		$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
		$res->free();

		return $row;
	}
	
}
?>
