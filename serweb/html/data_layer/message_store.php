<?
/*
 * $Id: message_store.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class Cinst_mess{
	var $mid, $src_addr, $time, $body;
	function Cinst_mess($mid, $src_addr, $time, $body){
		$this->mid=$mid;
		$this->src_addr=$src_addr;
		$this->time=$time;
		$this->body=$body;
	}
}

class Cvoice_mess{
	var $mid, $src_addr, $time, $subject, $file;
	function Cvoice_mess($mid, $src_addr, $time, $subject, $file){
		$this->mid=$mid;
		$this->src_addr=$src_addr;
		$this->time=$time;
		$this->subject=$subject;
		$this->file=$file;
	}
}

class CData_Layer extends CDL_common{

	function del_IM($user, $domain, $mid, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="delete from ".$config->data_sql->table_message_silo." where mid=".$mid." and r_uri like 'sip:".$user."@".$domain."%'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			return true;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}
	
	function del_VM($user, $domain, $mid, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
			$q="select file from ".$config->data_sql->table_voice_silo." where mid=".$mid." and r_uri like 'sip:".$user."@".$domain."%'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			if (!$res->numRows()) {$errors[]="Message not found or you haven't access to message"; return false;}
			$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
			$res->free();
	
			@$unl=unlink($config->voice_silo_dir.$row->file);
			if (!$unl and file_exists($config->voice_silo_dir.$row->file)) {$errors[]="Error when deleting message"; return false;}
	
			$q="delete from ".$config->data_sql->table_voice_silo." where mid=".$mid;
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			return true;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}

	function get_IMs($user, $domain, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="select mid, src_addr, inc_time, body from ".
				$config->data_sql->table_message_silo.
				" where r_uri like 'sip:".$user."@".$domain."%'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
	
			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
				if (date('Y-m-d',$row->inc_time)==date('Y-m-d')) $time="today ".date('H:i',$row->inc_time);
				else $time=date('Y-m-d H:i',$row->inc_time);

				$out[]=new Cinst_mess($row->mid, $row->src_addr,
					$time, $row->body);
			}
			$res->free();
			return $out;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}
	
	function get_VMs($user, $domain, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="select mid, src_addr, inc_time, subject, file from ".
				$config->data_sql->table_voice_silo." where r_uri like 'sip:".$user."@".$domain."%'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
				if (date('Y-m-d',$row->inc_time)==date('Y-m-d')) $time="today ".date('H:i',$row->inc_time);
				else $time=date('Y-m-d H:i',$row->inc_time);

				$out[]=new Cvoice_mess($row->mid, $row->src_addr,
					$time, $row->subject, $row->file);
			}
			$res->free();
			return $out;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}
	
}

?>