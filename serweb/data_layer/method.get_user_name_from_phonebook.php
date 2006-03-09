<?
/*
 * $Id: method.get_user_name_from_phonebook.php,v 1.2 2006/03/09 09:17:23 kozlik Exp $
 */

class CData_Layer_get_user_name_from_phonebook {
	var $required_methods = array();
	
	function get_user_name_from_phonebook($user, $sip_uri, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$q="select fname, lname 
		    from ".$config->data_sql->table_phonebook." 
		    where sip_uri=".$this->sql_format($sip_uri, "s")." and 
			      ".$this->get_indexing_sql_where_phrase($user);
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		if ($res->numRows() == 0) return false;
		$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();

		return implode(' ',array($row->fname, $row->lname));			
	}
	
}
?>
