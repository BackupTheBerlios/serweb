<?
/*
 * $Id: ms_get_v_msg.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class CData_Layer extends CDL_common{

	function get_VM($user, $domain, $mid, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="select subject, file from ".$config->data_sql->table_voice_silo.
				" where mid=".$mid." and r_uri like 'sip:".$user."@".$domain."%'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			if (!$res->numRows()) {$errors[]="Message not found or you haven't access to read message"; return false;}
			$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
			$res->free();
		
			@$fp=fopen($config->voice_silo_dir.$row->file,'r');
		
			if (!$fp){$errors[]="Can't open message"; return false;}
		
			Header("Content-Disposition: attachment;filename=".RawURLEncode(($row->subject?$row->subject:"received message").".wav"));
			Header("Content-type: audio/wav");
		
			@fpassthru($fp);
			@fclose($fp);

			return true;
		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}
}

?>