<?php
/*
 * $Id: method.get_admins_of_domain.php,v 1.1 2005/10/27 13:34:31 kozlik Exp $
 */

class CData_Layer_get_admins_of_domain {
	var $required_methods = array('get_domain');
	
	/**
	 *  return array of associtive arrays containig admins of domain
	 *
	 *  Keys of associative arrays:
	 *    uuid
	 *    username
	 *    domain
	 *
	 *  Possible options:
	 *		none
	 *
	 *	@param string $d_id		domain id
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return array			array of domains or FALSE on error
	 */ 
	function get_admins_of_domain($d_id, $opt, &$errors){
		global $config, $serweb_auth, $sess;

		if (!$this->connect_to_db($errors)) return false;

		$cp = &$config->data_sql->dom_pref;
		$cs = &$config->data_sql->subscriber;

		/* prepare SQL query */

		$q_join = ($config->users_indexed_by=='uuid') ?
					"s.".$cs->uuid :
					"concat(s.".$cs->username.", '@', s.".$cs->domain.")";


		$q="select s.".$cs->uuid.", s.".$cs->username.", s.".$cs->domain."
		    from ".$config->data_sql->table_dom_preferences." dp join ".$config->data_sql->table_subscriber." s 
			       on (dp.".$cp->att_value." = ".$q_join." and dp.".$cp->att_name." = 'admin')
			where dp.".$cp->id."='".$d_id."'
			order by s.".$cs->domain.", s.".$cs->username;

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]['uuid']	   = $row[$cs->uuid];
			$out[$i]['username']   = $row[$cs->username];
			$out[$i]['domain']     = $row[$cs->domain];
		}
		$res->free();

		return $out;			
	}
	
}
?>
