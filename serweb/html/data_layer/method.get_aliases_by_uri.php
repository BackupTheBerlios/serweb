<?
/*
 * $Id: method.get_aliases_by_uri.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_aliases_by_uri {
	var $required_methods = array('get_uuid_of_user');
	
	 /*
	  *	return array of aliases of user
	  */
	  
	function get_aliases_by_uri($sip_uri, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		if ($config->users_indexed_by=='uuid'){
			//we must get uuid first

			//parse username and domain from sip uri
			$reg=new Creg;
			$uname = $reg->get_username($sip_uri);
			$domain = $reg->get_domainname($sip_uri);
			
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
