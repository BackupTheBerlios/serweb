<?
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.del_vm.php,v 1.2 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for delete voice message
 * 
 *	@package    serweb
 */ 
class CData_Layer_del_vm {
	var $required_methods = array();
	
	function del_VM($user, $mid, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$q="select file from ".$config->data_sql->table_voice_silo." where mid=".$mid." and ".$this->get_indexing_sql_where_phrase_uri($user);
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		if (!$res->numRows()) {$errors[]="Message not found or you haven't access to message"; return false;}
		$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();

		@$unl=unlink($config->voice_silo_dir.$row->file);
		if (!$unl and file_exists($config->voice_silo_dir.$row->file)) {$errors[]="Error when deleting message"; return false;}

		$q="delete from ".$config->data_sql->table_voice_silo." where mid=".$mid." and ".$this->get_indexing_sql_where_phrase_uri($user);
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;
	}
	
}
?>
