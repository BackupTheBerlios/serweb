<?
/*
 * $Id: phonebook.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class Cphonebook{
	var $id;
	var $fname;
	var $lname;
	var $sip_uri;
	var $status;
	var $aliases;

	function Cphonebook($id, $fname, $lname, $sip_uri, $status='unknown'){
		$this->id=$id;
		$this->fname=$fname;
		$this->lname=$lname;
		$this->sip_uri=$sip_uri;
		$this->status=$status;
		
		$this->aliases=array();
	}
}

class CData_Layer extends CDL_common{

	function del_phonebook_entry($user, $domain, $pbid, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
			$q="delete from ".$config->data_sql->table_phonebook." where ".
				"username='".$user."' and domain='".$domain."' and id=".$pbid;
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
	
			return true;			
		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}


	function get_phonebook_entry($user, $domain, $pbid, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
			$q="select fname, lname, sip_uri from ".$config->data_sql->table_phonebook.
				" where domain='".$domain."' and username='".$user."' and id=".$pbid;
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
			$res->free();
	
			return $row;			
		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}

	function get_phonebook_entries($user, $domain, $pbid, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
			if (!is_null($pbid)) $qw=" and id!=".$pbid." "; else $qw="";
		
			$q="select id, fname, lname, sip_uri from ".$config->data_sql->table_phonebook.
				" where domain='".$domain."' and username='".$user."'".$qw." order by lname";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			
			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
				$out[$row->id] = new Cphonebook($row->id, $row->fname, $row->lname, $row->sip_uri, $this->get_status($row->sip_uri, $errors));

				if (!$aliases = $this->get_aliases($row->sip_uri, $errors)) continue;
				foreach($aliases as $val) $out[$row->id]->aliases[] = $val->username;
			}
			$res->free();
	
			return $out;			
		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}
	
	function update_phonebook_entry($user, $domain, $pbid, $fname, $lname, $sip_uri, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
			if ($pbid) $q="update ".$config->data_sql->table_phonebook." set fname='".$fname."', lname='".$lname."', sip_uri='".$sip_uri."' ".
				"where id=".$pbid." and domain='".$domain."' and username='".$user."'";
			else $q="insert into ".$config->data_sql->table_phonebook." (fname, lname, sip_uri, username, domain) ".
				"values ('".$fname."', '".$lname."', '".$sip_uri."', '".$user."', '".$domain."')";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
	
			return true;			
		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}
	
}

?>