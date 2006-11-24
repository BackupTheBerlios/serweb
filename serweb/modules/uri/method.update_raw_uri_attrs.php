<?php
/*
 * $Id: method.update_raw_uri_attrs.php,v 1.2 2006/11/24 13:33:03 kozlik Exp $
 */

class CData_Layer_update_raw_uri_attrs {
	var $required_methods = array();
	
	/**
	 *  Update uri_attrs table
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
	 
	function update_raw_uri_attrs($values, $filter, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table name */
		$t_name = &$config->data_sql->uri_attrs->table_name;
		/* col names */
		$c = &$config->data_sql->uri_attrs->cols;
		/* flags */
		$f = &$config->data_sql->uri_attrs->flag_values;

		$qw = "";
		if (isset($filter['scheme']))   $qw .= $c->scheme."   = ".$this->sql_format($filter['scheme'],   "s")." and ";
		if (isset($filter['did']))      $qw .= $c->did."      = ".$this->sql_format($filter['did'],      "s")." and ";
		if (isset($filter['username'])) $qw .= $c->username." = ".$this->sql_format($filter['username'], "s")." and ";
		if (isset($filter['name']))     $qw .= $c->name."     = ".$this->sql_format($filter['name'],     "s")." and ";
		if (isset($filter['value']))    $qw .= $c->value."    = ".$this->sql_format($filter['value'],    "s")." and ";
		if (isset($filter['flags']))    $qw .= $c->flags."    = ".$this->sql_format($filter['flags'],    "s")." and ";
		$qw .= $this->get_sql_bool(true);

		$qs = array();
		if (isset($values['scheme']))   $qs[] = $c->scheme."   = ".$this->sql_format($values['scheme'],   "s");
		if (isset($values['name']))     $qs[] = $c->name."     = ".$this->sql_format($values['name'],     "s");
		if (isset($values['value']))    $qs[] = $c->value."    = ".$this->sql_format($values['value'],    "s");
		if (isset($values['did']))      $qs[] = $c->did."      = ".$this->sql_format($values['did'],      "s");
		if (isset($values['username'])) $qs[] = $c->username." = ".$this->sql_format($values['username'], "s");
		if (isset($values['flags']))    $qs[] = $c->flags."    = ".$this->sql_format($values['flags'],    "n");

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
