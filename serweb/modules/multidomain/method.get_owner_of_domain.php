<?php
/*
 * $Id: method.get_owner_of_domain.php,v 1.1 2005/09/22 14:29:16 kozlik Exp $
 */

class CData_Layer_get_owner_of_domain {
	var $required_methods = array();
	
	/**
	 *  return associtive array containig owner of domain fith given $id
	 *
	 *  Keys of associative arrays:
	 *    id
	 *    name
	 *
	 *  Possible options:
	 *	  none
	 *      
	 *	@param int $id			id of domain
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return array			owner or FALSE on error
	 */ 
	function get_owner_of_domain($id, $opt, &$errors){
		global $config, $serweb_auth, $sess;

		if (!$this->connect_to_db($errors)) return false;

		$cp = &$config->data_sql->dom_pref;
		$cc = &$config->data_sql->customer;

		$q="select dp.".$cp->att_value.", c.".$cc->name."
		    from ".$config->data_sql->table_dom_preferences." dp left outer join ".$config->data_sql->table_customer." c
				on (dp.".$cp->att_value." = c.".$cc->id." and dp.".$cp->att_name." = 'owner')
			where dp.".$cp->id." = ".$id;

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		$row=$res->fetchRow(DB_FETCHMODE_ASSOC);
		
		$out['id']       = $row[$cp->att_value];
		$out['name']     = $row[$cc->name];

		$res->free();

		return $out;			
	}
}
?>
