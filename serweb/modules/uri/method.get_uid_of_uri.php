<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_uid_of_uri.php,v 1.2 2007/02/14 16:46:31 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_uri
 */ 

/**
 *	Data layer container holding the method for lookup UID for the uri
 * 
 *	@package    serweb
 *	@subpackage mod_uri
 */ 
class CData_Layer_get_uid_of_uri {
	var $required_methods = array('get_did_by_realm');
	
	/**
	 *  Get array of uids asociated with given uri
	 *
	 *  Possible options:
	 *	 - none
	 *
	 *	@param	string	$sip_uri	URI of user
	 *	@param	array	$opt		array of options
	 *	@return	array				FALSE on error
	 */ 
	  
	function get_uid_of_uri($sip_uri, $opt){
		global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		/* table's name */
		$tu_name = &$config->data_sql->uri->table_name;
		/* col names */
		$cu = &$config->data_sql->uri->cols;
		/* flags */
		$fu = &$config->data_sql->uri->flag_values;


		//parse username and domain from sip uri
		$reg   = &Creg::singleton();
		$uname = $reg->get_username($sip_uri);
		$realm = $reg->get_domainname($sip_uri);

		if (!$uname or !$realm) return array();

		if ($config->multidomain) {
			if (false === $did = $this->get_did_by_realm($realm, null)) return false;
			if (is_null($did)) return array();
		}
		else {
			$did = $config->default_did;
		}

		$flags_val = $fu['DB_DISABLED'] | $fu['DB_DELETED'];

		$q="select ".$cu->uid." as uid,
		           ".$cu->flags." as flags
		    from ".$tu_name."
			where  ".$cu->did."      = ".$this->sql_format($did,   "s")." and 
			       ".$cu->username." = ".$this->sql_format($uname, "s")." and 
				  (".$cu->flags." & ".$flags_val.") = 0";

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
		
		$out = array();
		for ($i=0; $row = $res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]['uid'] = $row['uid'];
			$out[$i]['is_to']   = (bool)($row['flags'] & $fu['DB_IS_TO']);
			$out[$i]['is_from'] = (bool)($row['flags'] & $fu['DB_IS_FROM']);
		}
		
		return $out;
	}
	
}
?>
