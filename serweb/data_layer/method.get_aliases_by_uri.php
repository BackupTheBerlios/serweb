<?
/*
 * $Id: method.get_aliases_by_uri.php,v 1.2 2005/05/02 11:23:49 kozlik Exp $
 */

class CData_Layer_get_aliases_by_uri {
	var $required_methods = array('get_uuid_of_user');
	
	 /*
	  *	return array of aliases of user
	  */
	  
	function get_aliases_by_uri($sip_uri, &$errors){
		global $config;

		//parse username and domain from sip uri
		$reg = Creg::singleton();
		$uname = $reg->get_username($sip_uri);
		$domain = $reg->get_domainname($sip_uri);

		/* create connection to proxy where are stored data of user */
		if ($config->enable_XXL and $this->name != "get_aliases_tmp"){

			$tmp_data = CData_Layer::singleton("get_aliases_tmp", $errors);
			$tmp_data->set_xxl_user_id($sip_uri);
			$tmp_data->expect_user_id_may_not_exists();

			return $tmp_data->get_aliases_by_uri($sip_uri, $errors);
			
		}


		if (!$this->connect_to_db($errors)) return false;

		if ($config->users_indexed_by=='uuid'){
			//we must get uuid first
			
			if (false === $uuid = $this->get_uuid_of_user($uname, $domain, $errors)) return false;
		
			$q="select username, domain from ".$config->data_sql->table_uuidaliases.
				" where uuid='".$uuid."' order by username";
		}
		else{
			$q="select username, domain from ".$config->data_sql->table_aliases.
				" where lower(contact)=lower('".$sip_uri."') order by username";
		}
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		while ($row = $res->fetchRow(DB_FETCHMODE_OBJECT)){
			$out[]=$row;
		}
		$res->free();
		return $out;
	}
	
}
?>
