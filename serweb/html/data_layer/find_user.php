<?
/*
 * $Id: find_user.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class Cuser{
	var $first_name, $last_name, $username, $timezone, $aliases;
	function Cuser($fname, $lname, $username, $timezone){
		$this->first_name 	= $fname;
		$this->last_name 	= $lname;
		$this->username 	= $username;
		$this->timezone 	= $timezone;

		$this->aliases=array();
	}
}

class CData_Layer extends CDL_common{

	function find_users($fname, $lname, $uname, $domain, $onlineonly, $errors){
		global $config;

		switch($this->container_type){
		case 'sql':
			if ($onlineonly)
				$q=	"select distinct s.timezone, s.first_name, s.last_name, s.username ".
					"from ".$config->data_sql->table_subscriber." s, ".$config->data_sql->table_location." l ".
					" where s.username=l.username and s.domain=l.domain and s.allow_find='1' and ".
						"s.first_name like '%".$fname."%' and s.last_name like '%".$lname."%' ".
						"and s.username like '%".$uname."%' and s.domain='".$domain."' limit 0,".$config->max_showed_rows;
			else
				$q=	"select timezone, first_name, last_name, username from ".$config->data_sql->table_subscriber.
					" where allow_find='1' and first_name like '%".$fname."%' and last_name like '%".$lname."%' ".
					"and username like '%".$uname."%' and domain='".$domain."' limit 0,".$config->max_showed_rows;
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
				$out[$row->username]=new Cuser($row->first_name, $row->last_name, $row->username, $row->timezone);

				if (!$aliases = $this->get_aliases("sip:".$row->username."@".$domain, $errors)) continue;
				foreach($aliases as $val) $out[$row->username]->aliases[] = $val->username;
			}
			$res->free();
	
			return $out;			
		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	
	}
}

?>