<?
/*
 * $Id: method.update_forward_to_voicemail_grp.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_update_forward_to_voicemail_grp {
	var $required_methods = array();
	
	function update_forward_to_voicemail_grp($user, $action, &$errors){
		global $config;
	
		if (!$this->connect_to_db($errors)) return false;

		if ($action=='add'){
			$att=$this->get_indexing_sql_insert_attribs($user);
		
			$q="insert into ".$config->data_sql->table_grp." (".$att['attributes'].", grp, last_modified) ".
			   "values (".$att['values'].", 'voicemail', now())";
		}
		elseif ($action=='del')
			$q="delete from ".$config->data_sql->table_grp.
			   " where ".$this->get_indexing_sql_where_phrase($user)." and grp='voicemail'";
		
		else{
			log_errors(PEAR::raiseError("unknow action:'".$action."'"), $errors); return false;
		}
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;
	}
	
}
?>
