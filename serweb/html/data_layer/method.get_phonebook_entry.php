<?
/*
 * $Id: method.get_phonebook_entry.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_phonebook_entry {
	var $required_methods = array();
	
	function get_phonebook_entry($user, $pbid, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$q="select fname, lname, sip_uri from ".$config->data_sql->table_phonebook.
			" where ".$this->get_indexing_sql_where_phrase($user)." and id=".$pbid;
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();

		$out=array();
		$out['fname']=$row->fname;
		$out['lname']=$row->lname;
		$out['sip_uri']=$row->sip_uri;

		return $out;			
	}
	
}
?>
