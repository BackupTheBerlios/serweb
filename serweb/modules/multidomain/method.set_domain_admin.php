<?php
/*
 * $Id: method.set_domain_admin.php,v 1.1 2005/10/04 10:03:42 kozlik Exp $
 */

class CData_Layer_set_domain_admin {
	var $required_methods = array();
	
	/**
	 *  set or unset admin of domain
	 *
	 *  Possible options:
	 *    add_domain  	(bool) default:true
	 *      if true function append domain to admin, otherwise remove it
	 *      
	 *	@param object $user		user - instance of class Cserweb_auth
	 *	@param string $domain	id of domain
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function set_domain_admin($user, $domain, $opt, &$errors){
		global $config, $serweb_auth, $sess;

		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->dom_pref;

		$u = ($config->users_indexed_by=='uuid') ?
				$user->uuid :
				($user->uname."@".$user->domain);

	    $o_add = (isset($opt['add_domain'])) ? (bool)$opt['add_domain'] : true;

		if ($o_add)
			$q = "insert into ".$config->data_sql->table_dom_preferences."
				        (".$c->id.", ".$c->att_name.", ".$c->att_value.")
				  values ('".$domain."', 'admin', '".$u."')";
		else
			$q = "delete from ".$config->data_sql->table_dom_preferences."
			      where ".$c->id." = '".$domain."' and 
				        ".$c->att_name."='admin' and
			            ".$c->att_value." = '".$u."'";
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		return true;
	}
	
}


?>
