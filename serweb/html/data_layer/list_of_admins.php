<?
/*
 * $Id: list_of_admins.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class CData_Layer extends CDL_common{
	function get_admins(&$fusers, $domain, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
		
			$query_c=$fusers->get_query_where_phrase('s');
		
			// get num of users
			if ($fusers->adminsonly)
				$q="select count(*) 
					from ".$config->data_sql->table_subscriber." s left join ".$config->data_sql->table_admin_privileges." p on
						(s.username=p.username and s.domain=p.domain and p.priv_name='is_admin')
					where p.priv_value and ".$query_c;
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
			if ($this->get_act_row() >= $this->get_num_rows()) 
				$this->set_act_row(max(0, $this->get_num_rows()-$this->get_showed_rows()));
				
			/* get admins */
			
			if ($fusers->adminsonly)
				$q="select s.username, s.domain, s.first_name, s.last_name, s.phone, s.email_address 
					from ".$config->data_sql->table_subscriber." s left join ".$config->data_sql->table_admin_privileges." p on
						(s.username=p.username and s.domain=p.domain and p.priv_name='is_admin')
					where p.priv_value and ".$query_c."
					order by s.domain, s.username 
					limit ".$this->get_act_row().", ".$this->get_showed_rows();
			else
				$q="select s.username, s.domain, s.first_name, s.last_name, s.phone, s.email_address 
					from ".$config->data_sql->table_subscriber." s 
					where ".$query_c."
					order by s.domain, s.username 
					limit ".$this->get_act_row().", ".$this->get_showed_rows();
		
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			
			
			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
				$name=$row->last_name;
				if ($name) $name.=" "; $name.=$row->first_name;

				$row->name=$name;
				$out[]=$row;
			}
			$res->free();
		
			return $out;
		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}

}

?>