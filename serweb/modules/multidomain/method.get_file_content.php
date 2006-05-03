<?php
/*
 * $Id: method.get_file_content.php,v 1.2 2006/05/03 12:27:01 kozlik Exp $
 */

class CData_Layer_get_file_content {
	var $required_methods = array();
	
	/**
	 *	get content of file specific for a domain from db
	 *
	 *  Possible options parameters:
	 *		none
	 *
	 *	@param string $did		domain id
	 *	@param string $file		filename (with path)
	 *	@param string $version	version of file
	 *	@param array $opt		associative array of options
	 *	@return string			content of file or FALSE on failure
	 */
	function get_file_content($did, $file, $version, $opt){
		global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$td_name = &$config->data_sql->domain_settings->table_name;
		/* col names */
		$cd = &$config->data_sql->domain_settings->cols;


		$q = "select ".$cd->content." 
		      from ".$td_name." 
			  where ".$cd->did." = ".$this->sql_format($did, "s")." and
			        ".$cd->filename." = ".$this->sql_format($file, "s")." and
			        ".$cd->version." = ".$this->sql_format($version, "n");

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res);
			return false;
		}

		$out = null;
		if ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out = $this->sql_unescape_binary($row[$cd->content]);
		}

		$res->free();

		return $out;
	}
	
}

?>
