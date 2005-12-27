<?
/*
 * $Id: method.get_ims.php,v 1.3 2005/12/27 16:13:48 kozlik Exp $
 */

class CData_Layer_get_ims {
	var $required_methods = array();
	
	function get_IMs($uid){
		global $config, $sess;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		$t_name = &$config->data_sql->msg_silo->table_name;	/* table's name */
		$c = &$config->data_sql->msg_silo->cols;				/* col names */

		/* get num rows */		
		$q="select count(*) from ".$t_name."
		    where ".$c->uid." = '".$uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$this->set_num_rows($row[0]);
		$res->free();

		/* if act_row is bigger then num_rows, correct it */
		$this->correct_act_row();


		$q="select ".$c->mid.", ".$c->from.", ".$c->inc_time.", ".$c->body." 
		    from ".$t_name.
			" where ".$c->uid." = '".$uid."'".
			$this->get_sql_limit_phrase();
			
		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}

		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$timestamp = gmmktime(substr($row[$c->inc_time], 11, 2), 	//hour
							      substr($row[$c->inc_time], 14, 2), 	//minute
							      substr($row[$c->inc_time], 17, 2), 	//second
							      substr($row[$c->inc_time], 5, 2), 	//month
							      substr($row[$c->inc_time], 8, 2), 	//day
							      substr($row[$c->inc_time], 0, 4));	//year
		
			if (date('Y-m-d', $timestamp) == date('Y-m-d')) $time="today ".date('H:i',$timestamp);
			else $time = date('Y-m-d H:i', $timestamp);

			$out[$i]['src_addr'] = 	htmlspecialchars($row[$c->from]);
			$out[$i]['raw_src_addr'] = $row[$c->from];
			$out[$i]['mid'] = 		$row[$c->mid];
			$out[$i]['time'] = 		$time;
			$out[$i]['timestamp'] =	$timestamp;
			$out[$i]['body'] =     	$row[$c->body];
			$out[$i]['url_reply']=	$sess->url("send_im.php?kvrk=".uniqid("")."&sip_addr=".rawURLEncode($row[$c->from]));
			$out[$i]['url_dele'] =	$sess->url("message_store.php?kvrk=".uniqid("")."&dele_im=".rawURLEncode($row[$c->mid]));
		}
		$res->free();
		return $out;
	}
	
}
?>
