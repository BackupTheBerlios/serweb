<?
/*
 * $Id: caller_screening.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class CData_Layer extends CDL_common{

	function del_CS_caller($user, $domain, $uri, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="delete from ".$config->data_sql->table_calls_forwarding." where ".
				"username='".$user."' and domain='".$domain."' and purpose='screening' and uri_re='".$uri."'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			return true;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}


	function get_CS_caller($user, $domain, $uri, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="select uri_re, action, param1, param2 from ".$config->data_sql->table_calls_forwarding.
				" where domain='".$domain."' and username='".$user."' and purpose='screening' and uri_re='".$uri."'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
			$res->free();
			return $row;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}

	function get_CS_callers($user, $domain, $uri, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			if ($uri) $qw=" and uri_re!='$uri' "; else $qw="";
	
			$q="select uri_re, action, param1, param2 from ".$config->data_sql->table_calls_forwarding.
				" where domain='".$domain."' and username='".$user."' and purpose='screening'".$qw." order by uri_re";
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
	
	function update_CS_caller($user, $domain, $uri, $uri_re, $action_key, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			if ($uri) $q="update ".$config->data_sql->table_calls_forwarding." set ".
										"uri_re='$uri_re', ".
										"action='".$config->calls_forwarding["screening"][$action_key]->action."', ".
										"param1='".$config->calls_forwarding["screening"][$action_key]->param1."', ".
										"param2='".$config->calls_forwarding["screening"][$action_key]->param2."' ".
				"where uri_re='$uri' and purpose='screening' and domain='".$domain."' and username='".$user."'";
	
			else $q="insert into ".$config->data_sql->table_calls_forwarding." (username, domain, uri_re, purpose, action, param1, param2) ".
				"values ('".$user."',
						'".$domain."',
						'$uri_re',
						'screening',
						'".$config->calls_forwarding["screening"][$action_key]->action."',
						'".$config->calls_forwarding["screening"][$action_key]->param1."',
						'".$config->calls_forwarding["screening"][$action_key]->param2."')";
	
			$res=$this->db->query($q);
			if (DB::isError($res)) {
				if ($res->getCode()==DB_ERROR_ALREADY_EXISTS)
					$errors[]="Record with this caller uri already exists";
				else log_errors($res, $errors); 
				return false;
			}
			return true;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}
}

?>