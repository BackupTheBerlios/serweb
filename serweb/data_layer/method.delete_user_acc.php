<?
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.delete_user_acc.php,v 1.5 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for delete all acc records of user
 * 
 *	@package    serweb
 */ 
class CData_Layer_delete_user_acc {
	var $required_methods = array();

	/**
	 *  Function mark all acc records of $user as deleted
	 *
	 *  Possible options parameters:
	 *	 - timestamp (int) - if timestamp is set, is deleted only records 
	 *	                     older than timestamp (default: none)
	 *	 - del_incoming	 (bool) - delete incoming calls (default: true)
	 *	 - del_outgoing	 (bool) - delete outgoing calls (default: true)
	 *
	 */ 
	function delete_user_acc($uid, $opt, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		/* table's name */
		$t_acc  = &$config->data_sql->acc->table_name;
		/* flags */
		$f_acc = &$config->data_sql->acc->flag_values;

	    $opt_timestamp = (isset($opt['timestamp'])) ? $opt['timestamp'] : null;
	    $opt_del_incoming = (isset($opt['del_incoming'])) ? $opt['del_incoming'] : true;
	    $opt_del_outgoing = (isset($opt['del_outgoing'])) ? $opt['del_outgoing'] : true;

		if ($opt_del_outgoing){
			$q="update ".$t_acc."
			    set flags = ( flags | ".$f_acc['DB_CALLER_DELETED']." )
				where from_uid='".$uid."'";

			if (!is_null($opt_timestamp)) 
				$q.=" and request_timestamp < '".gmdate("Y-m-d H:i:s", $opt_timestamp)."'";

			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		}

		if ($opt_del_incoming){
			$q="update ".$t_acc."
			    set flags = ( flags | ".$f_acc['DB_CALLEE_DELETED']." )
				where to_uid='".$uid."'";

			if (!is_null($opt_timestamp)) 
				$q.=" and request_timestamp < '".gmdate("Y-m-d H:i:s", $opt_timestamp)."'";

			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		}

		return true;
	}
	
}
?>
