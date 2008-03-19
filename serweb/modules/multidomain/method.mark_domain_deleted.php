<?php
/*
 * $Id: method.mark_domain_deleted.php,v 1.5 2008/03/19 12:10:03 kozlik Exp $
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
 	 *	  - undelete (bool) - undelete domain, setting this to true will 
     *                        undelete only domain names and domain attrs. Not
     *                        URIs and credentials within the domain
     *                        (default: false)
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
		$tc_name = &$config->data_sql->credentials->table_name;
		/* col names */
		$cd = &$config->data_sql->domain->cols;
		$ca = &$config->data_sql->domain_attrs->cols;
		$cu = &$config->data_sql->uri->cols;
		$cc = &$config->data_sql->credentials->cols;
		/* flags */
		$fd = &$config->data_sql->domain->flag_values;
		$fa = &$config->data_sql->domain_attrs->flag_values;
		$fu = &$config->data_sql->uri->flag_values;
		$fc = &$config->data_sql->credentials->flag_values;

		$an = &$config->attr_names;

	    $o_did = (isset($opt['did'])) ? $opt['did'] : null;
 	    $o_undelete = (isset($opt['undelete'])) ? (bool)$opt['undelete'] : false;

		if (is_null($o_did)) {
			ErrorHandler::log_errors(PEAR::raiseError('domain for mark as deleted is not specified')); 
			return false;
		}


        /* if 'did' column in credentials table is not used, make list of all
           realms matching this domain
         */
        if (!$config->auth['use_did']){
            $dh = &Domains::singleton();
            if (false === $dom_names = $dh->get_domain_names($o_did)) return false;

            $da = &Domain_Attrs::singleton($o_did);
            if (false === $realm = $da->get_attribute($config->attr_names['digest_realm'])) return false;
            
            $realms_w = array();
            
            if (!is_null($realm)){
                $realms_w[] = $cc->realm." = ".$this->sql_format($realm, "s");
            }

            foreach ($dom_names as $v){
                $realms_w[] = $cc->realm." = ".$this->sql_format($v, "s");
            }
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


        if (!$o_undelete){

    		$q = "update ".$tu_name." set ";
            $q .= $cu->flags." = ".$cu->flags." | ".$fu['DB_DELETED'];
    		$q .= " where ".$cu->did." = ".$this->sql_format($o_did, "s");
    
    		$res=$this->db->query($q);
    		if (DB::isError($res)) {
    			ErrorHandler::log_errors($res);
    			$this->transaction_rollback();
    			return false;
    		}
    
    		$q = "update ".$tc_name." set ";
            $q .= $cc->flags." = ".$cc->flags." | ".$fc['DB_DELETED'];
    
            if ($config->auth['use_did']){
        		$q .= " where ".$cc->did." = ".$this->sql_format($o_did, "s");
        	}
        	else{
                if (!$realms_w) $q .= " where ".$this-sql_format(false, "b");
                else            $q .= " where ".implode($realms_w, " or ");
            }
    
    		$res=$this->db->query($q);
    		if (DB::isError($res)) {
    			ErrorHandler::log_errors($res);
    			$this->transaction_rollback();
    			return false;
    		}
        
        }


		if (false === $this->transaction_commit()) return false;

		return true;
	}
	
}

?>
