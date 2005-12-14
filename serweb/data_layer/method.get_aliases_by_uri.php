<?php
/*
 * $Id: method.get_aliases_by_uri.php,v 1.4 2005/12/14 16:30:19 kozlik Exp $
 */

class CData_Layer_get_aliases_by_uri {
	var $required_methods = array('get_did_by_realm', 'get_aliases');
	
	/**
	 *  Get array of aliases of user with given sip-uri
	 *
	 *  Possible options:
	 *		none
	 *
	 *	@param	string	$sip_uri	URI of user
	 *	@param	array	$opt		array of options
	 *	@return	array				FALSE on error
	 */ 
	  
	function get_aliases_by_uri($sip_uri, $opt){
		global $config;

		/* create connection to proxy where are stored data of user */
		if (isModuleLoaded('xxl') and $this->name != "get_aliases_tmp"){

			$tmp_data = CData_Layer::singleton("get_aliases_tmp", $errors);
			$tmp_data->set_xxl_user_id($sip_uri);
			$tmp_data->expect_user_id_may_not_exists();

			return $tmp_data->get_aliases_by_uri($sip_uri, $errors);
			
		}

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

		if (false === $did = $this->get_did_by_realm($realm, null)) return false;
		if (is_null($did)) array();

		$flags_val = $fu['DB_DISABLED'] | $fu['DB_DELETED'];

		$q="select ".$cu->uid." as uid
		    from ".$tu_name."
			where  ".$cu->did." = '".$did."' and 
			       ".$cu->username." = '".$uname."' and 
				  (".$cu->flags." & ".$flags_val.") = 0";

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
		
		$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
		if (!$row){	unset($res); array(); }
		
		$uid = $row['uid'];

		$errors = array();
		if (false === $out = $this->get_aliases(new Cserweb_auth($uid, $uname, $realm), $errors)){
			ErrorHandler::add_error($errors); return false;
		}

		return $out;
	}
	
}
?>
