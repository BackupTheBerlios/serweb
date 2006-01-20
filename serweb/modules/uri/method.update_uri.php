<?php
/*
 * $Id: method.update_uri.php,v 1.1 2006/01/20 14:43:58 kozlik Exp $
 */

class CData_Layer_update_uri {
	var $required_methods = array();
	
	/**
	 *  Update URI table
	 *
	 *	
	 *
	 *  Possible options:
	 *		none
	 *	
	 *	@param	array	$values
	 *	@param	array	$filter
	 *	@param	array	$opt
	 *	@return bool			FALSE on error, TRUE otherwise
	 */ 
	 
	function update_uri($values, $filter, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table name */
		$t_name = &$config->data_sql->uri->table_name;
		/* col names */
		$c = &$config->data_sql->uri->cols;
		/* flags */
		$f = &$config->data_sql->uri->flag_values;

		$an = &$config->attr_names;

		$qw = "";
		if (isset($filter['uid']))      $qw .= $c->uid." = '".$filter['uid']."' and ";
		if (isset($filter['did']))      $qw .= $c->did." = '".$filter['did']."' and ";
		if (isset($filter['username'])) $qw .= $c->username." = '".$filter['username']."' and ";
		if (isset($filter['flags']))    $qw .= $c->flags." = '".$filter['flags']."' and ";
		$qw .= $this->get_sql_bool(true);

		$qs = array();
		if (isset($values['uid']))      $qs[] = $c->uid." = '".$values['uid']."'";
		if (isset($values['did']))      $qs[] = $c->did." = '".$values['did']."'";
		if (isset($values['username'])) $qs[] = $c->username." = '".$values['username']."'";
		if (isset($values['flags']))    $qs[] = $c->flags." = '".$values['flags']."'";

		$qs = implode(", ", $qs);


		$q = "update ".$t_name." 
		      set ".$qs."
		      where ".$qw;

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>
