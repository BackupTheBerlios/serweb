<?php
/*
 * $Id: method.mark_domain_deleted.php,v 1.4 2008/03/07 15:20:02 kozlik Exp $
 */

class CData_Layer_mark_domain_deleted {
	var $required_methods = array();
	
	/**
	 *  Mark domain as deleted
	 *
	 *  Possible options:
	 *
	 *    - did		(int)   - REQUIRED - id of domain which will be deleted 
     *                        (default: null)
 	 *	  - undelete (bool) - undelete domain (default: false)
	 *      
	 *	@param array $opt		associative array of options
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function mark_domain_deleted($opt){
		global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$td_name = &$config->data_sql->domain->table_name;
		$ta_name = &$config->data_sql->domain_attrs->table_name;
		$tu_name = &$config->data_sql->uri->table_name;
		/* col names */
		$cd = &$config->data_sql->domain->cols;
		$ca = &$config->data_sql->domain_attrs->cols;
		$cu = &$config->data_sql->uri->cols;
		/* flags */
		$fd = &$config->data_sql->domain->flag_values;
		$fa = &$config->data_sql->domain_attrs->flag_values;
		$fu = &$config->data_sql->uri->flag_values;

		$an = &$config->attr_names;

	    $o_did = (isset($opt['did'])) ? $opt['did'] : null;
 	    $o_undelete = (isset($opt['undelete'])) ? (bool)$opt['undelete'] : false;

		if (is_null($o_did)) {
			ErrorHandler::log_errors(PEAR::raiseError('domain for mark as deleted is not specified')); 
			return false;
		}

		if (false === $this->transaction_start()) return false;

        $domain_attrs = &Domain_Attrs::singleton($o_did);
        if ($o_undelete){
            if (false === $domain_attrs->unset_attribute($an['deleted_ts'])) {
                $this->transaction_rollback();
                return false;
            }
        }
        else{
            if (false === $domain_attrs->set_attribute($an['deleted_ts'], time())) {
                $this->transaction_rollback();
                return false;
            }
        }


		$q = "update ".$td_name." set ";
        if ($o_undelete)  $q .= $cd->flags." = ".$cd->flags." & ~".$fd['DB_DELETED'];
        else              $q .= $cd->flags." = ".$cd->flags." | ".$fd['DB_DELETED'];
		$q .= " where ".$cd->did." = ".$this->sql_format($o_did, "s");

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res); 
			$this->transaction_rollback();
			return false;
		}

		$q = "update ".$ta_name." set ";
        if ($o_undelete)  $q .= $ca->flags." = ".$ca->flags." & ~".$fa['DB_DELETED'];
        else              $q .= $ca->flags." = ".$ca->flags." | ".$fa['DB_DELETED'];
		$q .= " where ".$ca->did." = ".$this->sql_format($o_did, "s");

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res); 
			$this->transaction_rollback();
			return false;
		}


		$q = "update ".$tu_name." set ";
        if ($o_undelete)  $q .= $cu->flags." = ".$cu->flags." & ~".$fu['DB_DELETED'];
        else              $q .= $cu->flags." = ".$cu->flags." | ".$fu['DB_DELETED'];
		$q .= " where ".$cu->did." = ".$this->sql_format($o_did, "s");

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res);
			$this->transaction_rollback();
			return false;
		}

		if (false === $this->transaction_commit()) return false;

		return true;
	}
	
}

?>
