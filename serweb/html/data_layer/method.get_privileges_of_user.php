<?
/*
 * $Id: method.get_privileges_of_user.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_privileges_of_user {
	var $required_methods = array();
	
	 /*
	  * get privileges of user, gets only privilegfes specified in array $only_privileges
	  * return: array of objects with priv_name and priv_value
	  */

	function get_privileges_of_user($user, $only_privileges, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$qw=$this->get_indexing_sql_where_phrase($user);

		// if $only_privileges is array, generate where phrase which select only this privileges
		if (is_array($only_privileges)){
			foreach($only_privileges as $k=>$v) $only_privileges[$k]="priv_name = '".$v."'";
			$qw.=" and (".implode(" or ", $only_privileges).")";
		}

		$q="select priv_name, priv_value
			from ".$config->data_sql->table_admin_privileges."
			where ".$qw;
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=$row;
		$res->free();
		return $out;
	}
}
?>
