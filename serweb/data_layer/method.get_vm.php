<?
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_vm.php,v 1.2 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for get voice messages
 * 
 *	@package    serweb
 */ 
class CData_Layer_get_vm {
	var $required_methods = array();
	
	function get_VM($user, $mid, &$errors){
		global $config, $lang_str;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="select subject, file from ".$config->data_sql->table_voice_silo.
			" where mid=".$mid." and ".$this->get_indexing_sql_where_phrase_uri($user);
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		if (!$res->numRows()) {$errors[]=$lang_str['err_voice_msg_not_found']; return false;}
		$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();
	
		@$fp=fopen($config->voice_silo_dir.$row->file,'r');
	
		if (!$fp){$errors[]=$lang_str['err_can_not_open_message']; return false;}
	
		Header("Content-Disposition: attachment;filename=".RawURLEncode(($row->subject?$row->subject:"received message").".wav"));
		Header("Content-type: audio/wav");
	
		@fpassthru($fp);
		@fclose($fp);

		return true;
	}
	
}
?>
