<?php
/*
 * $Id: method.del_domain_alias.php,v 1.2 2005/12/22 12:38:54 kozlik Exp $
 */

class CData_Layer_del_domain_alias {
	var $required_methods = array();
	
	/**
	 *  Delete domain
	 *
	 *  Possible options:
	 *
	 *    did		(int)   	default: null
	 *      id of domain which will be deleted
	 *      either id or name is required
	 *      
	 *    name		(string)	default: null
	 *      name of domain which will be deleted
	 *      either id or name is required
	 *      
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function del_domain_alias($opt, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		/* table's name */
		$td_name = &$config->data_sql->domain->table_name;
		/* col names */
		$cd = &$config->data_sql->domain->cols;
		/* flags */
		$fd = &$config->data_sql->domain->flag_values;


	    $o_did  = (isset($opt['did']))  ? $opt['did']  : null;
	    $o_name = (isset($opt['name'])) ? $opt['name'] : null;

		if (is_null($o_did) and is_null($o_name)) {
			log_errors(PEAR::raiseError('domain for delete is not specified'), $errors); 
			return false;
		}

		$qw="";
		if ($o_did)          $qw .= $cd->did.  "= '".$o_did."'";
		if ($qw and $o_name) $qw .= " and ";
		if ($o_name)         $qw .= $cd->name. "= '".$o_name."'";
		

		$q="delete from ".$td_name."
			where ".$qw;

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			return false;
		}

		return true;
	}
	
}

?>
