<?
/*
 * $Id: method.get_missed_calls_of_yesterday.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_missed_calls_of_yesterday {
	var $required_methods = array();
	
	function get_missed_calls_of_yesterday($user, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;
	
		if ($config->users_indexed_by=='uuid'){
			$q="SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status  ".
				"FROM ".$config->data_sql->table_missed_calls." t1 ".
				"WHERE t1.callee_UUID='".$user->uuid."' and ".
						"date_format(t1.time, '%Y-%m-%d')=date_format(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d') ".
				"ORDER BY time DESC ";
		}
		else{
			$q="(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status  ".
					"FROM ".$config->data_sql->table_missed_calls." t1 ".
					"WHERE t1.username='".$user->uname."' and t1.domain='".$user->domain."' and ".
						"date_format(t1.time, '%Y-%m-%d')=date_format(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d') ) ".
				"UNION ".
				"(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status ".
					"FROM ".$config->data_sql->table_missed_calls." t1, ".$config->data_sql->table_aliases." t2 ".
					"WHERE 'sip:".$user->uname."@".$user->domain."'".
						"=t2.contact AND t2.username=t1.username AND t2.domain=t1.domain and ".
						"date_format(t1.time, '%Y-%m-%d')=date_format(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d') ) ".
				"ORDER BY time DESC ";
		}

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=$row;
		$res->free();
	
		return $out;
	}
	
}
?>
