<?
/*
 * $Id: method.get_users.php,v 1.3 2004/12/13 14:02:29 kozlik Exp $
 */

class CData_Layer_get_users {
	var $required_methods = array('get_aliases');
	
	function get_users(&$fusers, $domain, &$errors){
		global $config, $sess;

		if (!$this->connect_to_db($errors)) return false;
	
		$query_c=$fusers->get_query_where_phrase('s');

		if ($domain) {
			$query_c .= " and s.domain='".$domain."'";
		}

		/* get num rows */		
		if ($fusers->onlineonly){
			if ($config->users_indexed_by=='uuid')
				$q="select distinct s.username from ".$config->data_sql->table_subscriber." s, ".$config->data_sql->table_location." l ".
					" where s.uuid=l.uuid and ".$query_c;
			else
				$q="select distinct s.username from ".$config->data_sql->table_subscriber." s, ".$config->data_sql->table_location." l ".
					" where s.username=l.username and s.domain=l.domain and ".$query_c;
		}
		else
			$q="select s.username from ".$config->data_sql->table_subscriber." s ".
				" where ".$query_c;

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$this->set_num_rows($res->numRows());
		$res->free();
	
		/* if act_row is bigger then num_rows, correct it */
		$this->correct_act_row();

		if ($config->users_indexed_by=='uuid') $attribute='s.uuid';
		else $attribute='s.phplib_id';
		
		/* get users */
		if ($fusers->onlineonly){
			if ($config->users_indexed_by=='uuid')
				$q="select distinct s.username, s.domain, s.first_name, s.last_name, s.phone, s.email_address, ".$attribute." as uuid from ".
					$config->data_sql->table_subscriber." s, ".$config->data_sql->table_location." l ".
					" where s.uuid=l.uuid and ".$query_c.
					" order by s.username limit ".$this->get_act_row().", ".$this->get_showed_rows();
			else
				$q="select distinct s.username, s.domain, s.first_name, s.last_name, s.phone, s.email_address, ".$attribute." as uuid from ".
					$config->data_sql->table_subscriber." s, ".$config->data_sql->table_location." l ".
					" where s.username=l.username and s.domain=l.domain and ".$query_c.
					" order by s.username limit ".$this->get_act_row().", ".$this->get_showed_rows();
		}
		else
			$q="select s.username, s.domain, s.first_name, s.last_name, s.phone, s.email_address, ".$attribute." as uuid from ".$config->data_sql->table_subscriber." s ".
				" where ".$query_c.
				" order by s.username limit ".$this->get_act_row().", ".$this->get_showed_rows();

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
			
	
		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_OBJECT); $i++){
			$out[$i]['username']       = $row->username;
			$out[$i]['domain']         = $row->domain;
			$out[$i]['name']           = implode(' ', array($row->last_name, $row->first_name));
			$out[$i]['phone']          = $row->phone;
			$out[$i]['email_address']  = $row->email_address;
			$out[$i]['url_aliases']    = $sess->url("aliases.php?kvrk=".uniqid('')."&".user_to_get_param($row->uuid, $row->username, $row->domain, 'u'));
			$out[$i]['url_acl']        = $sess->url("acl.php?kvrk=".uniqid('')."&".user_to_get_param($row->uuid, $row->username, $row->domain, 'u'));
			$out[$i]['url_my_account'] = $sess->url($config->user_pages_path."my_account.php?kvrk=".uniqid('')."&".user_to_get_param($row->uuid, $row->username, $row->domain, 'u'));
			$out[$i]['url_accounting'] = $sess->url($config->user_pages_path."accounting.php?kvrk=".uniqid('')."&".user_to_get_param($row->uuid, $row->username, $row->domain, 'u'));
			$out[$i]['url_dele']       = $sess->url("users.php?kvrk=".uniqid('')."&".user_to_get_param($row->uuid, $row->username, $row->domain, 'd'));

			$out[$i]['aliases']='';
			if (false === ($aliases = $this->get_aliases(new Cserweb_auth($row->uuid, $row->username, $row->domain), $errors))) continue;

			$alias_arr=array();
			foreach($aliases as $val) $alias_arr[] = $val->username;
			
			$out[$i]['aliases'] = implode(", ", $alias_arr);

		}
		$res->free();
	
		return $out;
	}
	
}
?>
