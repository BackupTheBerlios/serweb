<?php
/*
 * $Id: method.set_domain_admin.php,v 1.2 2005/12/22 12:38:54 kozlik Exp $
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
	 *	@param string $uid		id of admin
	 *	@param string $did		id of domain
	 *	@param array $opt		associative array of options
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function set_domain_admin($uid, $did, $opt){
		global $config, $serweb_auth, $sess;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		/* table's name */
		$t_name = &$config->data_sql->domain_attrs->table_name;
		/* col names */
		$c = &$config->data_sql->domain_attrs->cols;
		/* flags */
		$f = &$config->data_sql->domain_attrs->flag_values;

		$an = &$config->attr_names;

	    $o_add = (isset($opt['add_domain'])) ? (bool)$opt['add_domain'] : true;

		if ($o_add)
			$q = "insert into ".$t_name."
				        (".$c->did.", ".$c->name.", ".$c->value.")
				  values ('".$did."', '".$an['admin']."', '".$uid."')";
		else
			$q = "delete from ".$t_name."
			      where ".$c->did." = '".$did."' and 
				        ".$c->name."='".$an['admin']."' and
			            ".$c->value." = '".$uid."'";
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		return true;
	}
	
}


?>
