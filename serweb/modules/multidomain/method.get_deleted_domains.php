<?php
/*
 * $Id: method.get_deleted_domains.php,v 1.1 2005/12/22 13:47:28 kozlik Exp $
 */

class CData_Layer_get_deleted_domains {
	var $required_methods = array('get_attr_by_val');
	

	/**
	 *  Function return IDs of domains marked as deleted
	 *
	 *
	 *  Possible options parameters:
	 *
	 *	  deleted_before (int)	default:null
	 *		if is set, only domains marked as deleted before given timestamp are returned
	 *  
	 *	@return array	array of domain IDs or FALSE on error
	 */ 
	 
	function get_deleted_domains($opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$ta_name = &$config->data_sql->domain_attrs->table_name;
		$td_name = &$config->data_sql->domain->table_name;
		/* col names */
		$ca = &$config->data_sql->domain_attrs->cols;
		$cd = &$config->data_sql->domain->cols;
		/* flags */
		$fa = &$config->data_sql->domain_attrs->flag_values;
		$fd = &$config->data_sql->domain->flag_values;

		$an = &$config->attr_names;

	    $opt_deleted_before = (isset($opt['deleted_before'])) ? $opt['deleted_before'] : null;


		$q1_w = $q2_w = "";
		if (!is_null($opt_deleted_before)){
			/* get users deleted before given timestamp */
			$o = array("name" => $an['deleted_ts']);
			
			if (false === $attrs = $this->get_attr_by_val('domain', $o)) return false;

			$dids = array();
			foreach ($attrs as $v){
				if ((int)$v['value'] < (int)$opt_deleted_before) $dids[]=$v['id'];
			}

			$q1_w = " and ".$this->get_sql_in("do.".$cd->did, $dids, true);
			$q2_w = " and ".$this->get_sql_in("at.".$ca->did, $dids, true);
		}


		$q1 = "select do.".$cd->did." as did
			  from ".$td_name." do 
			  where (do.".$cd->flags." & ".$fd['DB_DELETED'].") = ".$fd['DB_DELETED'].
			        $q1_w." 
			  group by do.".$cd->did;

		$q2 = "select at.".$ca->did." as did
			  from ".$ta_name." at 
			  where (at.".$ca->flags." & ".$fa['DB_DELETED'].") = ".$fa['DB_DELETED'].
			        $q2_w." 
			  group by at.".$ca->did;

		$q = "(".$q1.") union (".$q2.")";


		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }


		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out[] = $row['did'];
		}
		$res->free();
	
		return $out;
	}
}
?>
