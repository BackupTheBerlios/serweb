<?
/*
 * $Id: method.find_users.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_find_users {
	var $required_methods = array('get_aliases_by_uri', 'get_status');

	/*
	 * find users - white pages
	 */	

	/* filter is associative array which may contains this options:
		fname		- first name
		lname		- last name
		uname		- username
		domain		- domain
		onlineonly	- show only online users
	 */

	function find_users($filter, &$errors){
		global $config, $sess;

		if (!$this->connect_to_db($errors)) return false;

		// create where phrase from filter
		$q_where = "s.allow_find='1' ";
		$q_from  = "";
		foreach($filter as $k=>$f){
			if (!$f) continue;
			switch ($k){
				case 'fname':
					$q_where.="and s.first_name like '%".$f."%' ";
					break;
				case 'lname':
					$q_where.="and s.last_name like '%".$f."%' ";
					break;
				case 'uname':
					$q_where.="and s.username like '%".$f."%' ";
					break;
				case 'domain':
					$q_where.="and s.domain like '%".$f."%' ";
					break;
				case 'sip_uri':
					$reg=new Creg;
					$q_where.="and s.username = '".$reg->get_username($f)."' and s.domain = '".$reg->get_domainname($f)."' ";
					break;
				case 'alias':
					if ($config->users_indexed_by=='uuid'){
						$q_from  .= " inner join ".$config->data_sql->table_uuidaliases." a on (a.uuid=s.uuid)";
						$q_where .= "and a.username like '%".$f."%' ";
					}
					else{
						$q_from  .= " inner join ".$config->data_sql->table_aliases." a on (lower(a.contact) = lower(concat('sip:', s.username, '@', s.domain)))";
						$q_where .= "and a.username like '%".$f."%' ";
					}
					break;
			}
		}
		
		
		/* get num rows */		
		if ($filter['onlineonly'])
			$q=	"select count(distinct s.username, s.domain) ". 
				"from ".$config->data_sql->table_subscriber." s ".$q_from.", ".$config->data_sql->table_location." l ".
				" where s.username=l.username and s.domain=l.domain and ".$q_where;
		else
			$q=	"select count(distinct s.username, s.domain) from ".$config->data_sql->table_subscriber." s ".$q_from.
				" where ".$q_where;
	
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$this->set_num_rows($row[0]);
		$res->free();

		/* if act_row is bigger then num_rows, correct it */
		$this->correct_act_row();


		if ($filter['onlineonly'])
			$q=	"select distinct s.timezone, s.first_name, s.last_name, s.username, s.domain ".
				"from ".$config->data_sql->table_subscriber." s ".$q_from.", ".$config->data_sql->table_location." l ".
				" where s.username=l.username and s.domain=l.domain and ".$q_where.
				" limit ".$this->get_act_row().", ".$this->get_showed_rows();
		else
			$q=	"select distinct s.timezone, s.first_name, s.last_name, s.username, s.domain from ".$config->data_sql->table_subscriber." s ".$q_from.
				" where ".$q_where.
				" limit ".$this->get_act_row().", ".$this->get_showed_rows();
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_OBJECT); $i++){

			$out[$i]['name'] = implode(' ', array($row->last_name, $row->first_name));
			$out[$i]['sip_uri'] = "sip:".$row->username."@".$row->domain;
			$out[$i]['status'] = $this->get_status($out[$i]['sip_uri'], $errors);
			$out[$i]['timezone'] = $row->timezone;
			$out[$i]['url_add'] = $sess->url("phonebook.php?kvrk=".uniqID("").
												"&okey_x=1".
												"&fname=".RawURLEncode($row->first_name).
												"&lname=".RawURLEncode($row->last_name).
												"&sip_uri=".RawURLEncode($out[$i]['sip_uri']));
			$out[$i]['aliases']='';

			$al_arr=array();
			if (!$aliases = $this->get_aliases_by_uri("sip:".$row->username."@".$row->domain, $errors)) continue;
			foreach($aliases as $val) $al_arr[] = $val->username;
			
			$out[$i]['aliases'] = implode(', ', $al_arr);
		}
		$res->free();

		return $out;			
	
	}
	
}
?>
