<?
/*
 * $Id: admin_privileges.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class CData_Layer extends CDL_common{

	/* add privilege to user, parameter $update mean that record in DB should be updated, not inserted */
	function add_privilege_to_user($user, $domain, $priv_name, $priv_value, $update, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':

			if ($update){ /* if privilege is in db we must update its value */
				$q="update ".$config->data_sql->table_admin_privileges." set priv_value='".$priv_value."' ".
					"where domain='".$domain."' and username='".$user."' and priv_name='".$priv_name."'";
			} else { /* otherwise we insert privilege with right value */
				$q="insert into ".$config->data_sql->table_admin_privileges." (username, domain, priv_name, priv_value) ".
					"values ('".$user."', '".$domain."', '".$priv_name."', '".$priv_value."')";
			}

			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			return true;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}

	
	/* dele privilege of user, if priv_value is NULL then all values of multivalue privilege are deleted */
	function del_privilege_of_user($user, $domain, $priv_name, $priv_value, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
			$q="delete from ".$config->data_sql->table_admin_privileges." where ".
				"domain='".$domain."' and username='".$user."' and priv_name='".$priv_name."'".(is_null($priv_value)?"":"and priv_value='".$priv_value."'");

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