<?
/*
 * $Id: speed_dial.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class CData_Layer extends CDL_common{

	function del_SD_request($user, $domain, $sd, $sd_dom, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="delete from ".$config->data_sql->table_speed_dial." where ".
				"username='".$user."' and domain='".$domain."' and username_from_req_uri='".$sd."' and domain_from_req_uri='".$sd_dom."'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			return true;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}
	
	function get_SD_request($user, $domain, $sd, $sd_dom, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="select username_from_req_uri, domain_from_req_uri, new_request_uri from ".$config->data_sql->table_speed_dial.
				" where domain='".$domain."' and username='".$user."' and username_from_req_uri='".$sd."' and domain_from_req_uri='".$sd_dom."'";
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

	function get_SD_requests($user, $domain, $sd, $sd_dom, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			if (!is_null($sd)) $qw=" and (username_from_req_uri!='$sd' or domain_from_req_uri!='$sd_dom') "; else $qw="";
	
			$q="select username_from_req_uri, domain_from_req_uri, new_request_uri from ".$config->data_sql->table_speed_dial.
				" where domain='".$domain."' and username='".$user."'".$qw." order by domain_from_req_uri, username_from_req_uri";
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
	
	function update_SD_request($user, $domain, $sd, $new_uri, $usrnm_from_uri, $domain_from_uri, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			if ($sd) $q="update ".$config->data_sql->table_speed_dial." set new_request_uri='$new_uri', username_from_req_uri='$usrnm_from_uri', domain_from_req_uri='$domain_from_uri' ".
				"where username_from_req_uri='$sd' and domain='".$domain."' and username='".$user."'";
			else $q="insert into ".$config->data_sql->table_speed_dial." (username, domain, username_from_req_uri, domain_from_req_uri, new_request_uri) ".
				"values ('".$user."', '".$domain."', '$usrnm_from_uri', '$domain_from_uri', '$new_uri')";

			$res=$this->db->query($q);
			if (DB::isError($res)) {
				if ($res->getCode()==DB_ERROR_ALREADY_EXISTS)
					$errors[]="Record with this username and domain already exists";
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