<?
/*
 * $Id: users.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class CData_Layer extends CDL_common{

	function get_users(&$fusers, $domain, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
		
			$query_c=$fusers->get_query_where_phrase('s');
	
			/* get num rows */		
			if ($fusers->onlineonly)
				$q="select distinct s.username from ".$config->data_sql->table_subscriber." s, ".$config->data_sql->table_location." l ".
					" where s.username=l.username and s.domain=l.domain and s.domain='".$domain."' and ".$query_c;
			else
				$q="select s.username from ".$config->data_sql->table_subscriber." s ".
					" where s.domain='".$domain."' and ".$query_c;
	
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
	
			$this->set_num_rows($res->numRows());
			$res->free();
		
			/* if act_row is bigger then num_rows, correct it */
			if ($this->get_act_row() >= $this->get_num_rows()) 
				$this->set_act_row(max(0, $this->get_num_rows()-$this->get_showed_rows()));
				
				
			/* get users */
			if ($fusers->onlineonly)
				$q="select distinct s.username, s.first_name, s.last_name, s.phone, s.email_address from ".
					$config->data_sql->table_subscriber." s, ".$config->data_sql->table_location." l ".
					" where s.username=l.username and s.domain=l.domain and s.domain='".$domain."' and ".$query_c.
					" order by s.username limit ".$this->get_act_row().", ".$this->get_showed_rows();
			else
				$q="select s.username, s.first_name, s.last_name, s.phone, s.email_address from ".$config->data_sql->table_subscriber." s ".
					" where s.domain='".$domain."' and ".$query_c.
					" order by s.username limit ".$this->get_act_row().", ".$this->get_showed_rows();
	
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