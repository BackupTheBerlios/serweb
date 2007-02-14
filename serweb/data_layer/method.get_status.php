<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_status.php,v 1.8 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for get status of user
 * 
 *	@package    serweb
 */ 
class CData_Layer_get_status {
	var $required_methods = array('get_did_by_realm');
	
	/**
	 *  Get status of user specified by sip-uri
	 *
	 *	Return status: 'unknown', 'nonlocal', 'notexists', 'offline', 'online'
	 *
	 *  Possible options:
	 *	 - none
	 *
	 *	@param	string	$sip_uri	URI of user
	 *	@param	array	$opt		array of options
	 *	@return	string				FALSE on error
	 */ 
	 
	function get_status($sip_uri, $opt){
		global $config;
		
		/* create connection to proxy where are stored data of user */
		if (isModuleLoaded('xxl') and $this->name != "get_status_tmp"){

			$tmp_data = &CData_Layer::singleton("get_status_tmp", $errors);
			$tmp_data->set_xxl_user_id($sip_uri);
			//$tmp_data->expect_user_id_may_not_exists(); //need this?

			return $tmp_data->get_status($sip_uri, $errors);
		}


		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		/* table's name */
		$tu_name = &$config->data_sql->uri->table_name;
		$tl_name = &$config->data_sql->location->table_name;
		/* col names */
		$cu = &$config->data_sql->uri->cols;
		$cl = &$config->data_sql->location->cols;
		/* flags */
		$fu = &$config->data_sql->uri->flag_values;

		$an = &$config->attr_names;

		$reg   = &Creg::singleton();
		$uname = $reg->get_username($sip_uri);
		$realm = $reg->get_domainname($sip_uri);;

		if (!$uname or !$realm) return "unknown";

		if ($config->multidomain) {
			if (false === $did = $this->get_did_by_realm($realm, null)) return false;
			if (is_null($did)) return "nonlocal";
		}
		else {
			if ($realm != $config->domain) return "nonlocal";
			$did = $config->default_did;
		}

		$flags_val = $fu['DB_DISABLED'] | $fu['DB_DELETED'];

		$q="select ".$cu->uid." as uid
		    from ".$tu_name."
			where  ".$cu->did." = '".$did."' and 
			       ".$cu->username." = ".$this->sql_format($uname, "s")." and 
				  (".$cu->flags." & ".$flags_val.") = 0";

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
		
		$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
		if (!$row){	unset($res); return "notexists"; }
		
		$uid = $row['uid'];

		$o = array("uid" => $uid,
		           "did" => $did);

		if (false === $show = Attributes::get_attribute($an['show_status'], $o)) return false;

		if (!$show) return 'unknown';

		$q="select count(*)
		    from ".$tl_name."
			where  ".$cl->uid." = '".$uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
		
		if (!($row = $res->fetchRow(DB_FETCHMODE_ORDERED))){ 
			ErrorHandler::log_errors(PEAR::raiseError("Can't fetch data from DB")); 
			return false; 
		}

		if ($row[0]) return "online";
		else return "offline";

	}
}
?>
