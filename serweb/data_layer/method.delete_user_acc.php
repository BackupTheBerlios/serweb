<?
/*
 * $Id: method.delete_user_acc.php,v 1.2 2005/04/21 15:09:45 kozlik Exp $
 */

/**
 *  Function mark all acc records of $user as deleted
 *
 *  Possible options parameters:
 *
 *	timestamp			(int) default: none
 *		if timestamp is set, is deleted only records older than timestamp
 *
 *	del_incoming		(bool) default: true
 *		delete incoming calls
 *
 *	del_outgoing		(bool) default: true
 *		delete outgoing calls
 *
 */ 

class CData_Layer_delete_user_acc {
	var $required_methods = array('get_aliases_by_uri');

	function delete_user_acc($user, $opt, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

	    $opt_timestamp = (isset($opt['timestamp'])) ? $opt['timestamp'] : null;
	    $opt_del_incoming = (isset($opt['del_incoming'])) ? $opt['del_incoming'] : true;
	    $opt_del_outgoing = (isset($opt['del_outgoing'])) ? $opt['del_outgoing'] : true;

		if ($config->users_indexed_by=='uuid'){
			if ($opt_del_outgoing){
				$q="update ".$config->data_sql->table_accounting."
				    set caller_deleted='1' where caller_uuid='".$user->uuid."'";
	
				if (!is_null($opt_timestamp)) 
					$q.=" and time<'".gmdate("Y-m-d H:i:s", $opt_timestamp)."'";
	
				$res=$this->db->query($q);
				if (DB::isError($res)) {log_errors($res, $errors); return false;}
			}

			if ($opt_del_incoming){
				$q="update ".$config->data_sql->table_accounting."
				    set callee_deleted='1' where callee_uuid='".$user->uuid."'";
	
				if (!is_null($opt_timestamp)) 
					$q.=" and time<'".gmdate("Y-m-d H:i:s", $opt_timestamp)."'";
	
				$res=$this->db->query($q);
				if (DB::isError($res)) {log_errors($res, $errors); return false;}
			}
		}
		else{
			if ($opt_del_outgoing){
				if (!is_array($aliases = $this->get_aliases_by_uri("sip:".$user->uname."@".$user->domain, $errors))) return false;
	
				$a=new stdClass();
				$a->username=$user->uname;
				$a->domain=$user->domain;
	
				$aliases[]=$a;
	
				/* delete outgoing calls of $user and all him aliases */
				foreach($aliases as $row){
					$q="update ".$config->data_sql->table_accounting."
					    set caller_deleted='1' where username='".$row->username."' and domain='".$row->domain."'";
		
					if (!is_null($opt_timestamp)) 
						$q.=" and time<'".gmdate("Y-m-d H:i:s", $opt_timestamp)."'";
	
					$res=$this->db->query($q);
					if (DB::isError($res)) {log_errors($res, $errors); return false;}
				}
			}
		}

		return true;
	}
	
}
?>
