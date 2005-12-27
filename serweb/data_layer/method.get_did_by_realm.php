<?php
/*
 * $Id: method.get_did_by_realm.php,v 1.1 2005/12/22 13:44:42 kozlik Exp $
 */

class CData_Layer_get_did_by_realm {
	var $required_methods = array();
	
	/**
	 *  Look for domain with same realm (or domainname) as given parameter
	 *
	 *	On error this method returning FALSE. I domian is not found return NULL
	 *
	 *  Possible options:
	 *		none
	 *
	 *	@return string		domain id
	 */ 
	 
	function get_did_by_realm($realm, $opt){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		/* table's name */
		$t_d  = &$config->data_sql->domain->table_name;
		$t_da = &$config->data_sql->domain_attrs->table_name;
		/* col names */
		$c_d  = &$config->data_sql->domain->cols;
		$c_da = &$config->data_sql->domain_attrs->cols;
		/* flags */
		$f_d  = &$config->data_sql->domain->flag_values;
		$f_da = &$config->data_sql->domain_attrs->flag_values;

		$out = array();
		$errors = array();

		/*
		 *	look for domain with digest_realm same as $realm
		 */

		$flags_set   = $f_da['DB_FOR_SERWEB'];
		$flags_clear = $f_da['DB_DISABLED'] | $f_da['DB_DELETED'];

		$q="select ".$c_da->did."
		    from ".$t_da."
			where  ".$c_da->name." = '".$config->attr_names['digest_realm']."' and 
			       ".$c_da->value." = '".$realm."' and
				   ".$c_da->flags." & ".$flags_set." = ".$flags_set." and
				   ".$c_da->flags." & ".$flags_clear." = 0 ";
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			ErrorHandler::add_error($errors);
			return false;
		}

		if ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$res->free();
			return $row[$c_da->did];
		}

		$res->free();

		/*
		 *	look for domain with name same as $realm
		 */

		$flags_set   = $f_d['DB_FOR_SERWEB'];
		$flags_clear = $f_d['DB_DISABLED'] | $f_d['DB_DELETED'];
		
		$q="select ".$c_d->did."
		    from ".$t_d."
			where ".$c_d->name." = '".$realm."' and 
			      ".$c_d->flags." & ".$flags_set." = ".$flags_set." and
				  ".$c_d->flags." & ".$flags_clear." = 0 ";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			ErrorHandler::add_error($errors);
			return false;
		}

		if ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$res->free();
			return $row[$c_d->did];
		}

		$res->free();

		return null;
	}
}
?>