<?
/*
 * $Id: method.get_aliases.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_aliases {
	var $required_methods = array();
	
	/*
	 *	return array of aliases of user
	 */
	  
	function get_aliases($user, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		if ($config->users_indexed_by=='uuid'){
			$q="select username, domain from ".$config->data_sql->table_uuidaliases.
				" where uuid='".$user->uuid."' order by username";
		}
		else{
			$q="select username, domain from ".$config->data_sql->table_aliases.
				" where lower(contact)=lower('sip:".$user->uname."@".$user->domain."') order by username";
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
