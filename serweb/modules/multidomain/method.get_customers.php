<?php
/*
 * $Id: method.get_customers.php,v 1.1 2005/09/22 14:29:16 kozlik Exp $
 */

class CData_Layer_get_customers {
	var $required_methods = array();
	
	/**
	 *  return array of associtive arrays containing customers
	 *
	 *  Keys of associative arrays:
	 *    id	-	id of customer
	 *    name	-	name of customer
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
		global $config, $serweb_auth, $sess;

		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->customer;

	    $o_exclude = (isset($opt['exclude'])) ? $opt['exclude'] : null;
	    $o_single  = (isset($opt['single']))  ? $opt['single']  : null;

		if (!is_null($o_single)) $qw=" where ".$c->id." = ".$o_single." "; 
		elseif (!is_null($o_exclude)) $qw=" where ".$c->id." != ".$o_exclude." "; 
		else $qw="";

		if (is_null($o_single)){
			/* get num rows */		
			$q="select count(*) from ".$config->data_sql->table_customer.$qw;
		
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
	
		$q="select ".$c->id.", ".$c->name." 
		    from ".$config->data_sql->table_customer.
			$qw." 
			order by name".
			$this->get_sql_limit_phrase();

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]['id']   = $row[$c->id];
			$out[$i]['name'] = $row[$c->name];
			$out[$i]['primary_key']  = array('id' => &$out[$i]['id']);
		}
		$res->free();

		return $out;			
	}
	
}
?>
