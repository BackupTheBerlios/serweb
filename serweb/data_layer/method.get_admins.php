<?
/*
 * $Id: method.get_admins.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_get_admins {
	var $required_methods = array();
	
	function get_admins(&$fusers, &$errors){
		global $config, $sess;

		if (!$this->connect_to_db($errors)) return false;
	
		$query_c=$fusers->get_query_where_phrase('s');
	
		// get num of users
		if ($fusers->adminsonly)
			$q="select count(*) 
				from ".$config->data_sql->table_subscriber." s left join ".$config->data_sql->table_admin_privileges." p on ".
					(($config->users_indexed_by=='uuid')?
						"(s.uuid=p.uuid and p.priv_name='is_admin')":
						"(s.username=p.username and s.domain=p.domain and p.priv_name='is_admin')").
				"where p.priv_value and ".$query_c;
		else
			$q="select count(*) 
				from ".$config->data_sql->table_subscriber." s 
				where ".$query_c;
	
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
	
		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$this->set_num_rows($row[0]);
		$res->free();
		
		/* if act_row is bigger then num_rows, correct it */
		$this->correct_act_row();
			
		if ($config->users_indexed_by=='uuid') $attribute='s.uuid';
		else $attribute='s.phplib_id';

		/* get admins */
		
		if ($fusers->adminsonly)
			$q="select s.username, s.domain, s.first_name, s.last_name, s.phone, s.email_address, ".$attribute." as uuid 
				from ".$config->data_sql->table_subscriber." s left join ".$config->data_sql->table_admin_privileges." p on".
					(($config->users_indexed_by=='uuid')?
						"(s.uuid=p.uuid and p.priv_name='is_admin')":
						"(s.username=p.username and s.domain=p.domain and p.priv_name='is_admin')").
				"where p.priv_value and ".$query_c."
				order by s.domain, s.username 
				limit ".$this->get_act_row().", ".$this->get_showed_rows();
		else
			$q="select s.username, s.domain, s.first_name, s.last_name, s.phone, s.email_address, ".$attribute." as uuid 
				from ".$config->data_sql->table_subscriber." s 
				where ".$query_c."
				order by s.domain, s.username 
				limit ".$this->get_act_row().", ".$this->get_showed_rows();
	
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		
		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_OBJECT); $i++){
			$out[$i]['username']      = $row->username;
			$out[$i]['domain']        = $row->domain;
			$out[$i]['name']          = implode(' ', array($row->last_name, $row->first_name));
			$out[$i]['email_address'] = $row->email_address;
			$out[$i]['url_ch_priv']   = $sess->url("admin_privileges.php?kvrk=".uniqid('')."&".user_to_get_param($row->uuid, $row->username, $row->domain, 'u'));;
		}
		$res->free();
	
		return $out;
	}
	
}
?>
