<?
/*
 * $Id: method.is_user_exists.php,v 1.4 2006/04/12 13:41:20 kozlik Exp $
 */

class CData_Layer_is_user_exists {
	var $required_methods = array('get_did_by_realm');
	
	/*
	 *	check if user exists
	 */

	function is_user_exists($uname, $did){
	 	global $config;

		if (isModuleLoaded('xxl')){
			die('method is_user_exists not correctly implemented in XXL mode');
			if (!$this->connect_to_xml_rpc(array("uri" => "sip:".$uname."@".$udomain), $errors)) return false;
			
			$params = array(new XML_RPC_Value("sip:".$uname."@".$udomain, 'string'));
			$msg = new XML_RPC_Message('get_proxy', $params);
			$res = $this->rpc->send($msg);

			if ($this->rpc_is_error($res)){
				if ($res->getCode() == "404") return 1;
				log_errors($res, $errors); return 0;
			}
	
			return -3;			
		}
		else {
			$errors = array();
			if (!$this->connect_to_db($errors)) {
				ErrorHandler::add_error($errors); return 0;
			}

			/* table's name */
			$tc_name = &$config->data_sql->credentials->table_name;
			$tu_name = &$config->data_sql->uri->table_name;
			/* col names */
			$cc = &$config->data_sql->credentials->cols;
			$cu = &$config->data_sql->uri->cols;
			/* flags */
			$fc = &$config->data_sql->credentials->flag_values;
			$fu = &$config->data_sql->uri->flag_values;

			$an = &$config->attr_names;

			/* get all domain names of domain with given did */
			$dh = &Domains::singleton();
			if (false === $d_names = $dh->get_domain_names($did)) return 0;

			/* get digest realm of given domain */
			$da = &Domain_Attrs::singleton($did);
			if (false === $d_realm = $da->get_attribute($an['digest_realm'])) return 0;

			/* append digest realm to list of domains */
			if (!isset($d_names)) $d_names = array();
			if (!is_null($d_realm))	$d_names[] = $d_realm;

			$q="select count(*) from ".$tc_name." 
			    where lower(".$cc->uname.")=lower(".$this->sql_format($uname, "s").")";
			/* get credentials only with realm from given list */
			if (count($d_names)) $q .= " and ".$this->get_sql_in($cc->realm, $d_names);

			$res=$this->db->query($q);
			if (DB::isError($res)) {ErrorHandler::log_errors($res); return 0;}
			$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
			$res->free();
			
			if ($row[0]) {
				sw_log("Credentials for user (username, did) - (".$uname.", ".$did.") already exists", PEAR_LOG_DEBUG);
				return -1;
			}
			
			
			/* 
			 *	Credentials not found, check uri table 
			 */
			$q="select count(*) from ".$tu_name." 
			    where lower(".$cu->username.")=lower(".$this->sql_format($uname, "s").") and 
				      lower(".$cu->did.")=lower(".$this->sql_format($did, "s").")";
			$res=$this->db->query($q);
	
			if (DB::isError($res)) {ErrorHandler::log_errors($res); return 0;}
	
			$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
			$res->free();
			if ($row[0]) {
				sw_log("Uri for user (username, did) - (".$uname.", ".$did.") already exists", PEAR_LOG_DEBUG);
				return -2;
			}
	
			return 1;
		}
	}
	
}
?>
