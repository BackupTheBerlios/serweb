<?
/*
 * $Id: acl.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class CData_Layer extends CDL_common{

	function get_admin_ACL_privileges($user, $domain, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="select priv_value from ".$config->data_sql->table_admin_privileges.
				" where domain='".$domain."' and username='".$user."'".
						" and priv_name='acl_control'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
			$ACL_control=array();
			while ($row = $res->fetchRow(DB_FETCHMODE_OBJECT)) $ACL_control[]=$row->priv_value;
			$res->free();
			return $ACL_control;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}


	function get_ACL_of_user($user, $domain, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="select grp from ".$config->data_sql->table_grp." where domain='".$domain."' and username='".$user."'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
			$out=array();
			while ($row = $res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=$row->grp;
			$res->free();
			return $out;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}

	function update_ACL_of_user($user, $domain, $grp, $act, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
				if ($act=='set')
					$q="insert into ".$config->data_sql->table_grp." (username, domain, grp, last_modified) ".
						"values ('".$user."', '".$domain."', '".$grp."', now())";
				else
					$q="delete from ".$config->data_sql->table_grp." where ".
						"domain='".$domain."' and username='".$user."' and grp='".$grp."'";

			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			return true;
			
		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}
	
	
}

?>