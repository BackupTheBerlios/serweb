<?
/*
 * $Id: my_account.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class Cusrloc {
	var $uri, $q, $expires, $geo_loc;

	function Cusrloc ($uri, $q, $expires, $geo_loc){
		$this->uri=$uri;
		$this->q=$q;
		$this->expires=$expires;
		$this->geo_loc=$geo_loc;
	}
}

class CData_Layer extends CDL_common{

	function get_sip_user_details($user, $domain, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':

			$q="select email_address, allow_find, timezone from ".$config->data_sql->table_subscriber.
				" where username='".$user."' and domain='".$domain."'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
			$res->free();
			return $row;

		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	
	}
	
	function update_sip_user_details($user, $domain, $passwd, $email, $allow_find, $timezone, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
			$qpass="";
			if (!is_null($passwd)){
	
				$ha1=md5($user.":".$config->realm.":".$passwd);
				$ha1b=md5($user."@".$domain.":".$config->realm.":".$passwd);
	
				$qpass=", password='$passwd', ha1='$ha1', ha1b='$ha1b'";
			}
	
	 		$q="update ".$config->data_sql->table_subscriber.
				" set email_address='".$email."', allow_find='".($allow_find?1:0)."', timezone='".$timezone."', datetime_modified=now()".$qpass.
				" where username='".$user."' and domain='".$domain."'";
	
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			
			return true;

		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}

	
	function del_contact($user, $domain, $contact, &$errors){
		global $config;

		/* construct FIFO command */
		$ul_name=$user."@".$domain."\n";
		$fifo_cmd=":ul_rm_contact:".$config->reply_fifo_filename."\n".
			$config->ul_table."\n".		//table
			$ul_name.
			$contact."\n\n";			//contact

		$message=write2fifo($fifo_cmd, $errors, $status);
		if ($errors) return false;
		/* we accept any 2xx as ok */
		if (substr($status,0,1)!="2") {$errors[]=$status; return false; }
		
		return $status;
	}
	
	function add_contact($user, $domain, $contact, $expires, &$errors){
		global $config;
		
		if ($config->ul_replication) $replication="0\n";
		else $replication="";

		/* construct FIFO command */
		$ul_name=$user."@".$domain."\n";
		$fifo_cmd=":ul_add:".$config->reply_fifo_filename."\n".
			$config->ul_table."\n".			//table
			$ul_name.
			$contact."\n".				//contact
			$expires."\n".					//expires
			$config->ul_priority."\n".	// priority
			$replication."\n";

		$message=write2fifo($fifo_cmd, $errors, $status);
		if ($errors) return false;
		/* we accept any 2xx as ok */
		if (substr($status,0,1)!="2") {$errors[]=$status; return false; }
		
		return $status;
	}	
	
	function get_acl($user, $domain, &$errors){
		global $config;
	
		switch($this->container_type){
		case 'sql':
			$q="select grp from ".$config->data_sql->table_grp." where domain='".$domain.
				"' and username='".$user."' order by grp";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
				$out[]=$row;
			} //while
			$res->free();			

			return $out;

		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}

	function get_usrloc($user, $domain, &$errors){
		global $config;

		$ul_name=$user."@".$domain."\n";
		$fifo_cmd=":ul_show_contact:".$config->reply_fifo_filename."\n".
		$config->ul_table."\n".		//table
		$ul_name."\n";				//username

		$fifo_out=write2fifo($fifo_cmd, $err, $status);
		if ($err or !$fifo_out) {
			$errors=array_merge($errors, $err); // No!
			return false;
		}
		if (!$fifo_out) return false;

		if (substr($status,0,1)!="2" and substr($status,0,3)!="404") {$errors[]=$status; return false; }

		$out=array();	
		$out_arr=explode("\n", $out);

		foreach($out_arr as $val){
			if (!ereg("^[[:space:]]*$", $val)){
				if (ereg("<([^>]*)>;q=([0-9.]*);expires=([0-9]*)", $val, $regs))
					$usrloc[]=new Cusrloc($regs[1], $regs[2], $regs[3], $this->get_location($regs[1], $errors));
				else { $errors[]="sorry error -- invalid output from fifo"; return false; }
			}
		}
		
		return $out;
	}
	
}

?>