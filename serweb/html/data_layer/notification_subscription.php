<?
/*
 * $Id: notification_subscription.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class CData_Layer extends CDL_common{

	function subscribe_event($user, $domain, $uri, $desc, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="insert into ".$config->data_sql->table_event." (uri, description, username, domain) ".
				"values ('".$uri."', '".$desc."', '".$user."' , '".$domain."')";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			return true;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}

	function unsubscribe_event($user, $domain, $id, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="delete from ".$config->data_sql->table_event.
				" where username='".$user."' and domain='".$domain."'  and id=".$id;
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			return true;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}
	
	function get_events($user, $domain, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="select id, uri, description ".
				"from ".$config->data_sql->table_event." ".
				"where username='".$user."' and domain='".$domain."'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=$row;
			$res->free();
			return $out;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}
	
}

?>