<?
/*
 * $Id: method.is_user_exists.php,v 1.2 2006/03/08 15:46:27 kozlik Exp $
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
			$msg = new XML_RPC_Message_patched('get_proxy', $params);
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


			/* select all credentials with given username */
			$q="select ".$cc->realm." from ".$tc_name." 
			    where lower(".$cc->uname.")=lower(".$this->sql_format($uname, "s").")";
			$res=$this->db->query($q);
			if (DB::isError($res)) {ErrorHandler::log_errors($res); return 0;}

			/* 	
			 *	Walk throught credentials and try find domain ID by realm from
			 *	credential. Compare this domain ID with given did. If they
			 *	equal, credentials for given (uname, did) already exists.
			 */
			while($row=$res->fetchRow(DB_FETCHMODE_ORDERED)){
				if (false === $d = $this->get_did_by_realm($row[0], null)) return 0;
				if ($d == $did) {
					sw_log("Credentials for user (username, did) - (".$uname.", ".$did.") already exists", PEAR_LOG_DEBUG);
					return -1;
				}
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
