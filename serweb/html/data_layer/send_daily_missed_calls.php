<?
/*
 * $Id: send_daily_missed_calls.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class CData_Layer extends CDL_common{

	function get_send_MC_default_value(&$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
		
			$q="select default_value from ".$config->data_sql->table_user_preferences_types.
				" where att_name='".$config->up_send_daily_missed_calls."'";

			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return -1;}
	
			if (!$row=$res->fetchRow(DB_FETCHMODE_OBJECT)) {$errors[]="not found attribute '".$config->up_send_daily_missed_calls."' in user preferences"; return -1;}
			$default_value=$row->default_value;
			$res->free();

			return $default_value;

		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}
	
	function get_send_MC_list_of_users(&$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
		
			$q="select s.username, s.domain, s.email_address, p.value ".
				"from ".$config->data_sql->table_subscriber." s left outer join ".$config->data_sql->table_user_preferences." p ".
						" on s.username=p.username and s.domain=p.domain and p.attribute='".$config->up_send_daily_missed_calls."'";

			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			
			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=$row;
			$res->free();
		
			return $out;

		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}
	
	function get_missed_calls($user, $domain, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
		
			$q="(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status  ".
					"FROM ".$config->data_sql->table_missed_calls." t1 ".
					"WHERE t1.username='".$user."' and t1.domain='".$domain."' and ".
						"date_format(t1.time, '%Y-%m-%d')=date_format(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d') ) ".
				"UNION ".
				"(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status ".
					"FROM ".$config->data_sql->table_missed_calls." t1, ".$config->data_sql->table_aliases." t2 ".
					"WHERE 'sip:".$user."@".$domain."'".
						"=t2.contact AND t2.username=t1.username AND t2.domain=t1.domain and ".
						"date_format(t1.time, '%Y-%m-%d')=date_format(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d') ) ".
				"ORDER BY time DESC ";
		
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=$row;
			$res->free();
		
			return $out;

		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}

}

?>