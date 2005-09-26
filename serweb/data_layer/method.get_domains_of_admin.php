<?php
/*
 * $Id: method.get_domains_of_admin.php,v 1.1 2005/09/26 10:56:54 kozlik Exp $
 */

class CData_Layer_get_domains_of_admin {
	var $required_methods = array();
	
	/**
	 *  return array of domain ids which can administer given user
	 *
	 *
	 *  Possible options:
	 *		none
	 *      
	 *	@param object $user		user - instance of class Cserweb_auth
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return array			array of domain ids or FALSE on error
	 */ 
	function get_domains_of_admin($user, $opt, &$errors){
		global $config, $serweb_auth, $sess;

		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->dom_pref;

		$u = ($config->users_indexed_by=='uuid') ?
				$user->uuid :
				($user->uname."@".$user->domain);
		
		$q="select ".$c->id."
		    from ".$config->data_sql->table_dom_preferences."
			where ".$c->att_name."='admin' and
			      ".$c->att_value." = '".$u."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]   = $row[$c->id];
		}
		$res->free();

		return $out;			
	}
	
}

?>
