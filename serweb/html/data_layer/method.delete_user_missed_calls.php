<?
/*
 * $Id: method.delete_user_missed_calls.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_delete_user_missed_calls {
	var $required_methods = array('get_aliases_by_uri');

	/*
	 * delete all missed calls of user
	 * if $timestamp is not null delete only calls older than $timestamp
	 */
		
	function delete_user_missed_calls($user, $timestamp, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		if ($config->users_indexed_by=='uuid'){
			$q="delete from ".$config->data_sql->table_missed_calls.
                                       " where callee_uuid='".$user->uuid."'";
			if (!is_null($timestamp)) 
				$q.=" and time<'".gmdate("Y-m-d H:i:s", $timestamp)."'";
			
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		}
		else{
			if (!is_array($aliases = $this->get_aliases_by_uri("sip:".$user->uname."@".$user->domain, $errors))) return false;

			if (!$this->connect_to_db($errors)) return false;

			$a=new stdClass();
			$a->username=$user->uname;
			$a->domain=$user->domain;

			$aliases[]=$a;

			/* delete missed calls of $user and all him aliases */
			foreach($aliases as $row){
				$q="delete from ".$config->data_sql->table_missed_calls.
					" where username='".$row->username."' and domain='".$row->domain."' ";

				if (!is_null($timestamp)) 
					$q.=" and time<'".gmdate("Y-m-d H:i:s", $timestamp)."'";

				$res=$this->db->query($q);
				if (DB::isError($res)) {log_errors($res, $errors); return false;}
			}
		}

		return true;
	}
	
}
?>
