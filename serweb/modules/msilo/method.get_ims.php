<?
/*
 * $Id: method.get_ims.php,v 1.2 2005/08/29 13:28:10 kozlik Exp $
 */

class CData_Layer_get_ims {
	var $required_methods = array();
	
	function get_IMs($user, &$errors){
		global $config, $sess;
		
		if (!$this->connect_to_db($errors)) return false;


		/* get num rows */		
		$q="select count(*) from ".$config->data_sql->table_message_silo.
			" where ".$this->get_indexing_sql_where_phrase($user);

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$this->set_num_rows($row[0]);
		$res->free();

		/* if act_row is bigger then num_rows, correct it */
		$this->correct_act_row();


		$q="select mid, src_addr, inc_time, body from ".
			$config->data_sql->table_message_silo.
			" where ".$this->get_indexing_sql_where_phrase($user).
			$this->get_sql_limit_phrase();
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_OBJECT); $i++){
			if (date('Y-m-d',$row->inc_time)==date('Y-m-d')) $time="today ".date('H:i',$row->inc_time);
			else $time=date('Y-m-d H:i',$row->inc_time);

			$out[$i]['src_addr'] = htmlspecialchars($row->src_addr);
			$out[$i]['raw_src_addr'] = $row->src_addr;
			$out[$i]['mid'] = 	   $row->mid;
			$out[$i]['time'] =     $time;
			$out[$i]['body'] =     $row->body;
			$out[$i]['url_reply']= $sess->url("send_im.php?kvrk=".uniqid("")."&sip_addr=".rawURLEncode($row->src_addr));
			$out[$i]['url_dele'] = $sess->url("message_store.php?kvrk=".uniqid("")."&dele_im=".rawURLEncode($row->mid));
		}
		$res->free();
		return $out;
	}
	
}
?>
