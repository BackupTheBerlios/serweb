<?
/*
 * $Id: method.get_missed_calls.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_missed_calls {
	var $required_methods = array('get_status');
	
	function get_missed_calls($user, &$errors){
		global $config, $serweb_auth;

		if (!$this->connect_to_db($errors)) return false;

		/* get num rows */
		if ($config->users_indexed_by=='uuid'){
			$q="SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status  ".
					"FROM ".$config->data_sql->table_missed_calls." t1 ".
                                               "WHERE t1.callee_UUID='".$user->uuid."'";
		}
		else{
			$q="(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status  ".
					"FROM ".$config->data_sql->table_missed_calls." t1 ".
					"WHERE t1.username='".$user->uname."' and t1.domain='".$user->domain."' ) ".
				"UNION ".
				"(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status ".
					"FROM ".$config->data_sql->table_missed_calls." t1, ".$config->data_sql->table_aliases." t2 ".
					"WHERE 'sip:".$user->uname."@".$user->domain."'".
						"=t2.contact AND t2.username=t1.username AND t2.domain=t1.domain ) ";
		}

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$this->set_num_rows($res->numRows());
		$res->free();

		/* if act_row is bigger then num_rows, correct it */
		$this->correct_act_row();

		if ($config->users_indexed_by=='uuid'){
			$q="SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status  ".
					"FROM ".$config->data_sql->table_missed_calls." t1 ".
                                               "WHERE t1.callee_UUID='".$user->uuid."'".
				"ORDER BY time DESC ".
				"limit ".$this->get_act_row().", ".$this->get_showed_rows();
		}
		else{
			$q="(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status  ".
					"FROM ".$config->data_sql->table_missed_calls." t1 ".
					"WHERE t1.username='".$user->uname."' and t1.domain='".$user->domain."' ) ".
				"UNION ".
				"(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status ".
					"FROM ".$config->data_sql->table_missed_calls." t1, ".$config->data_sql->table_aliases." t2 ".
					"WHERE 'sip:".$user->uname."@".$user->domain."'".
						"=t2.contact AND t2.username=t1.username AND t2.domain=t1.domain ) ".
				"ORDER BY time DESC ".
				"limit ".$this->get_act_row().", ".$this->get_showed_rows();
		}

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_OBJECT); $i++){
			$timestamp=gmmktime(substr($row->time,11,2), 	//hour
								substr($row->time,14,2), 	//minute
								substr($row->time,17,2), 	//second
								substr($row->time,5,2), 	//month
								substr($row->time,8,2), 	//day
								substr($row->time,0,4));	//year
			if ($timestamp <=0 ) $time="";
			else {
				if (date('Y-m-d',$timestamp)==date('Y-m-d')) $time="today ".date('H:i',$timestamp);
				else $time=date('Y-m-d H:i',$timestamp);
			}

			$out[$i]['from_uri']   = $row->from_uri;
			$out[$i]['url_ctd']    = "javascript: open_ctd_win2('".rawURLEncode($row->from_uri)."', '".RawURLEncode("sip:".$serweb_auth->uname."@".$serweb_auth->domain)."');";
			$out[$i]['sip_from']   = htmlspecialchars(ereg_replace("(.*)(;tag=.*)","\\1", $row->sip_from));
			$out[$i]['time']       = $time;
			$out[$i]['sip_status'] = $row->sip_status;
			$out[$i]['status']     = $this->get_status($row->from_uri, $errors);
		}
		$res->free();

		return $out;
	}
	
}
?>
