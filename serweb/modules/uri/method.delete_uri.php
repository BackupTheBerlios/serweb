<?
/*
 * $Id: method.delete_uri.php,v 1.1 2005/12/22 13:48:46 kozlik Exp $
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
	 *	@param Cserweb_auth $user	owner of the contact 
	 *	@param string $alias_u		username part from alias
	 *	@param string $alias_d		domain part from alias
	 *	@param array $errors	
	 *	@return bool				TRUE on success, FALSE on failure
	 */

	function delete_uri($uid, $alias_u, $alias_did, $opt){
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
		      where ".$c->uid." = '".$uid."' and 
		            ".$c->username." = '".$alias_u."' and 
		            ".$c->did." = '".$alias_did."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }


		if (isModuleLoaded('xxl')){
			$alias_uri = "sip:".$alias_u."@".$alias_d;

			if (false === $this->clear_proxy_xxl($alias_uri, null, $errors)) {
				ErrorHandler::add_error($errors);
				return false;
			}
		}

		return true;		
	}
	
}
?>
