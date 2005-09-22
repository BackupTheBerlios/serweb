<?php
/*
 * $Id: method.update_owner_of_domain.php,v 1.1 2005/09/22 14:29:16 kozlik Exp $
 */

class CData_Layer_update_owner_of_domain {
	var $required_methods = array();
	
	/**
	 *  update owner of domain fith given $d_id
	 *
	 *  Possible options:
	 *    insert  	(bool) default:true
	 *      if true, function insert new record, otherwise update old record
	 *      
	 *	@param int   $d_id  	domain id
	 *	@param int   $c_id  	customer (owner) id
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function update_owner_of_domain($d_id, $c_id, $opt, &$errors){
		global $config, $serweb_auth, $sess;

		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->dom_pref;

		$o_insert = isset($opt['insert']) ? (bool)$opt['insert'] : false;

		if ($o_insert){
			$q="insert into ".$config->data_sql->table_dom_preferences." (
					   ".$c->id.",
					   ".$c->att_name.", 
					   ".$c->att_value."
			    ) 
				values (
					   '".$d_id."',
					   'owner',
					   '".$c_id."'
				 )";
		}
		else{
			$q="update ".$config->data_sql->table_dom_preferences." 
			    set ".$c->att_value."='".$c_id."' 
				where ".$c->id."='".$d_id."' and 
				      ".$c->att_name."='owner'";
		}

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			return false;
		}
		return true;
	}
}
?>
