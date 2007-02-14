<?
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_vms.php,v 1.2 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for get voice messages of user
 * 
 *	@package    serweb
 */ 
class CData_Layer_get_vms {
	var $required_methods = array();
	
	function get_VMs($user, &$errors){
		global $config, $sess;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="select mid, src_addr, inc_time, subject, file from ".
			$config->data_sql->table_voice_silo." where ".$this->get_indexing_sql_where_phrase_uri($user);
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_OBJECT); $i++){
			if (date('Y-m-d',$row->inc_time)==date('Y-m-d')) $time="today ".date('H:i',$row->inc_time);
			else $time=date('Y-m-d H:i',$row->inc_time);

			$out[$i]['subject']   = $row->subject;
			$out[$i]['src_addr']  = htmlspecialchars($row->src_addr);
			$out[$i]['time']      = $time;
			$out[$i]['url_reply'] = $sess->url("send_im.php?kvrk=".uniqid("")."&sip_addr=".rawURLEncode($row->src_addr));
			$out[$i]['url_get']   = $sess->url("ms_get_v_msg.php?kvrk=".uniqid("")."&mid=".rawURLEncode($row->mid));
			$out[$i]['url_dele']  = $sess->url("message_store.php?kvrk=".uniqid("")."&dele_vm=".rawURLEncode($row->mid));
		}
		$res->free();
		return $out;
	}
	
}
?>
