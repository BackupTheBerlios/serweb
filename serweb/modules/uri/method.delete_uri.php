<?
/*
 * $Id: method.delete_uri.php,v 1.3 2006/03/08 15:46:28 kozlik Exp $
 */

class CData_Layer_delete_uri {
	
	function _get_required_methods(){
		global $config;
		
		return isModuleLoaded('xxl')?
					array('clear_proxy_xxl'):
					array();
	}
	
	/**
	 *	delete alias of user
	 *
	 *	@param string	$uid		owner of the contact 
	 *	@param string	$username	username part from URI
	 *	@param string	$did		domain part from URI
	 *	@param string	$flags		flags of the URI
	 *	@param array	$opt		various options
	 *	@return bool				TRUE on success, FALSE on failure
	 */

	function delete_uri($uid, $username, $did, $flags, $opt){
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

		$q = "delete from ".$t_name."
		      where ".$c->uid."      = ".$this->sql_format($uid,      "s")." and 
		            ".$c->username." = ".$this->sql_format($username, "s")." and 
		            ".$c->did."      = ".$this->sql_format($did,      "s")." and 
		            ".$c->flags."    = ".$this->sql_format($flags,    "n");

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }


		if (isModuleLoaded('xxl')){
			// get domain: $alias_d = domainname of $did
			$alias_uri = "sip:".$username."@".$alias_d;

			if (false === $this->clear_proxy_xxl($alias_uri, null, $errors)) {
				ErrorHandler::add_error($errors);
				return false;
			}
		}

		return true;		
	}
	
}
?>
