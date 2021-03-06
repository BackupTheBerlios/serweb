<?php
/*
 * $Id: method.get_customers.php,v 1.6 2007/02/06 10:15:34 kozlik Exp $
 */

class CData_Layer_get_customers {
	var $required_methods = array();
	
	/**
	 *  return array (indexed by customer ID) of customers
	 *
	 *  Keys of associative arrays:
	 *    id	-	id of customer
	 *    name	-	name of customer
	 *    address  
	 *    email
	 *    phone
	 *
	 *  Possible options:
	 *
	 *    exclude	(string)	default: null
	 *      exclude customer with this id from result
	 *      
	 *    single    (string)    default: null  
	 *      return only customer with this id
	 *      
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return array			array of customers or FALSE on error
	 */ 
	function get_customers($opt, &$errors){
		global $config, $sess;

		if (!$this->connect_to_db($errors)) return false;

		/* table's name */
		$tc_name = &$config->data_sql->customers->table_name;
		/* col names */
		$cc = &$config->data_sql->customers->cols;

	    $o_exclude = (isset($opt['exclude'])) ? $opt['exclude'] : null;
	    $o_single  = (isset($opt['single']))  ? $opt['single']  : null;

	    $o_order_by = (isset($opt['order_by']) and isset($cc->$opt['order_by'])) ? $cc->$opt['order_by'] : "";
	    $o_order_desc = (!empty($opt['order_desc'])) ? "desc" : "";

		if (!is_null($o_single))      $qw=" where ".$cc->cid."  = ".$this->sql_format($o_single,  "n")." "; 
		elseif (!is_null($o_exclude)) $qw=" where ".$cc->cid." != ".$this->sql_format($o_exclude, "n")." "; 
		else $qw="";

		if (is_null($o_single)){
			/* get num rows */		
			$q="select count(*) from ".$tc_name.$qw;
		
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
			$this->set_num_rows($row[0]);
			$res->free();
		}
		else{
			$this->set_num_rows(1);
		}

		/* if act_row is bigger then num_rows, correct it */
		$this->correct_act_row();
	
		$q="select ".$cc->cid.", ".$cc->name.", ".$cc->phone.", ".$cc->address.", ".$cc->email." 
		    from ".$tc_name.
			$qw;

		if ($o_order_by) {
			$q .= " order by ".$o_order_by." ".$o_order_desc;
		}

		$q .= $this->get_sql_limit_phrase();

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out[$row[$cc->cid]]['cid']      	= $row[$cc->cid];
			$out[$row[$cc->cid]]['name']    	= $row[$cc->name];
			$out[$row[$cc->cid]]['phone']   	= $row[$cc->phone];
			$out[$row[$cc->cid]]['address'] 	= $row[$cc->address];
			$out[$row[$cc->cid]]['email']   	= $row[$cc->email];
			$out[$row[$cc->cid]]['primary_key']	= array('cid' => &$out[$row[$cc->cid]]['cid']);
		}
		$res->free();

		return $out;			
	}
	
}
?>
