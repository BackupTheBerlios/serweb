<?
/*
 * $Id: method.is_alias_exists.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_is_alias_exists {
	var $required_methods = array();
	
	function is_alias_exists($alias_u, $alias_d, &$errors){
	 	global $config;

		if ($config->users_indexed_by!='uuid'){
			log_errors($this->not_implemented(), $errors);
			return -1;
		}

		if (!$this->connect_to_db($errors)) return -1;

		$q="select count(*) from ".$config->data_sql->table_uuidaliases."
			 where username='".$alias_u."' and domain='".$alias_d."'";
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return -1;}

		$row = $res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();

		return $row[0]?true:false;
	}
	
}
?>
