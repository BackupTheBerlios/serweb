<?
/*
 * $Id: method.check_admin_perms_to_user.php,v 1.4 2005/12/22 13:17:46 kozlik Exp $
 */

class CData_Layer_check_admin_perms_to_user {

	function _get_required_methods(){
		return array();
	}
	
	/**
	 *  check if admin have permissions to change user's setting
	 *
	 *  Possible options parameters:
	 *	 none
	 *
	 *	@param object $admin		admin - instance of class Auth
	 *	@param object $user			admin - instance of class SerwebUser
	 *	@param array $opt			associative array of options
	 *	@return bool				TRUE on permit, FALSE on forbid, -1 on failure
	 */ 
	function check_admin_perms_to_user(&$admin, &$user, $opt){
		global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return -1;
		}

		/* table name */
		$t_name = &$config->data_sql->uri->table_name;
		/* col names */
		$c = &$config->data_sql->uri->cols;
		/* flags */
		$f = &$config->data_sql->uri->flag_values;


		if (false === $adm_domains = $admin->get_administrated_domains()) return -1;

		$uid = $user->get_uid();

		$q = "select count(*) 
		      from ".$t_name."
			  where ".$c->uid." = '".$uid."' and 
			        ".$this->get_sql_in($c->did, $adm_domains, true)." and 
					".$c->flags." & ".$f['DB_DELETED']." = 0";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return -1;}

		$row = $res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();

		return $row[0] ? true : false;
		
	}
	
}
?>
