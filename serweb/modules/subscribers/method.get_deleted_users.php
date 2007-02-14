<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_deleted_users.php,v 1.2 2007/02/14 16:46:31 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_subscribers
 */ 

/**
 *	Data layer container holding the method for return list of users marked as deleted
 * 
 *	@package    serweb
 *	@subpackage mod_subscribers
 */ 
class CData_Layer_get_deleted_users {
	var $required_methods = array('get_attr_by_val');
	

	/**
	 *  Function return IDs of users marked as deleted
	 *
	 *
	 *  Possible options parameters:
	 *	  - deleted_before (int) - if is set, only users marked as deleted 
	 *	    before given timestamp are returned (default: null)
	 *  
	 *	@return array	array of users or FALSE on error
	 */ 
	 
	function get_deleted_users($opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$ta_name = &$config->data_sql->user_attrs->table_name;
		$tc_name = &$config->data_sql->credentials->table_name;
		/* col names */
		$ca = &$config->data_sql->user_attrs->cols;
		$cc = &$config->data_sql->credentials->cols;
		/* flags */
		$fa = &$config->data_sql->user_attrs->flag_values;
		$fc = &$config->data_sql->credentials->flag_values;

		$an = &$config->attr_names;

	    $opt_deleted_before = (isset($opt['deleted_before'])) ? $opt['deleted_before'] : null;


		$q1_w = $q2_w = "";
		if (!is_null($opt_deleted_before)){
			/* get users deleted before given timestamp */
			$o = array("name" => $an['deleted_ts']);
			
			if (false === $attrs = $this->get_attr_by_val('user', $o)) return false;

			$uids = array();
			foreach ($attrs as $v){
				if ((int)$v['value'] < (int)$opt_deleted_before) $uids[] = $v['id'];
			}

			$q1_w = " and ".$this->get_sql_in("cr.".$cc->uid, $uids, true);
			$q2_w = " and ".$this->get_sql_in("at.".$ca->uid, $uids, true);
		}


		$q1 = "select cr.".$cc->uid." as uid
			  from ".$tc_name." cr 
			  where (cr.".$cc->flags." & ".$fc['DB_DELETED'].") = ".$fc['DB_DELETED'].
			        $q1_w." 
			  group by cr.".$cc->uid;

		$q2 = "select at.".$ca->uid." as uid
			  from ".$ta_name." at 
			  where (at.".$ca->flags." & ".$fa['DB_DELETED'].") = ".$fa['DB_DELETED'].
			        $q2_w." 
			  group by at.".$ca->uid;

		$q = "(".$q1.") union (".$q2.")";


		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }


		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out[] = $row['uid'];
		}
		$res->free();
	
		return $out;
	}
}
?>
