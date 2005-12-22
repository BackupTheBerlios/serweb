<?php
/*
 * $Id: method.get_domain.php,v 1.5 2005/12/22 13:21:21 kozlik Exp $
 */

class CData_Layer_get_domain {
	var $required_methods = array();
	
	/**
	 *  return array of associtive arrays containig domain names
	 *
	 *  Keys of associative arrays:
	 *    id
	 *    name
	 *
	 *  Possible options:
	 *
	 *	  order_by	(string)	default: 'name'
	 *		name of column for sorting. If false or empty, result is not sorted
	 *
	 *	  order_desc	(bool)	default: false
	 *		order descending
	 *
	 *    filter	(array)     default: array()
	 *      associative array of pairs (column, value) which should be returned
	 *      
	 *	  check_deleted_flag	(bool)	default:true
	 *		If true, domains marked as deleted are not returned
	 *
	 *	@param array $opt		associative array of options
	 *	@return array			array of domain names or FALSE on error
	 */ 
	function get_domain($opt){
		global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$td_name = &$config->data_sql->domain->table_name;
		/* col names */
		$cd = &$config->data_sql->domain->cols;
		/* flags */
		$fd = &$config->data_sql->domain->flag_values;

	    $o_filter  = (isset($opt['filter'])) ? $opt['filter'] : array();
	    $o_order_by = (isset($opt['order_by'])) ? $opt['order_by'] : "name";
	    $o_order_desc = (isset($opt['order_desc'])) ? "desc" : "";
	    $o_check_deleted =  (isset($opt['check_deleted_flag'])) ? $opt['check_deleted_flag'] : true;

		$qw=" ".$this->get_sql_bool(true)." ";
		foreach($o_filter as $k=>$v){
			$qw .= "and ".$cd->$k." = '".$v."' ";
		}

		$q_deleted = "";
		if ($o_check_deleted){
			$q_deleted = " and (".$cd->flags." & ".$fd['DB_DELETED'].") = 0 ";
		}


		$q="select ".$cd->did.", 
		           ".$cd->name.", 
			       ".$cd->flags." & ".$fd['DB_DISABLED']." as disabled,
			       ".$cd->flags." & ".$fd['DB_CANON']." as canon
		    from ".$td_name."
			where ".$qw.$q_deleted; 

		if ($o_order_by) {
			if (isset($cd->$o_order_by)) $q .= " order by ".$cd->$o_order_by." ".$o_order_desc;
			else $q .= " order by ".$o_order_by." ".$o_order_desc;
		}
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
		
		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]['did']       = $row[$cd->did];
			$out[$i]['name']     = $row[$cd->name];
			$out[$i]['disabled'] = $row['disabled'];
			$out[$i]['canon']    = $row['canon'];
			$out[$i]['primary_key']  = array('did' => &$out[$i]['did'], 
			                                 'name' => &$out[$i]['name']);
		}
		$res->free();

		return $out;			
	}
	
}
?>
