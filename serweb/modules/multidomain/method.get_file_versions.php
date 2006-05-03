<?php
/*
 * $Id: method.get_file_versions.php,v 1.2 2006/05/03 12:27:01 kozlik Exp $
 */

class CData_Layer_get_file_versions {
	var $required_methods = array();
	
	/**
	 *	get all versions of file specific for domain
	 *
	 *  Possible options parameters:
	 *		none
	 *
	 *	@param string $did		domain id
	 *	@param string $file		filename (with path)
	 *	@param array $opt		associative array of options
	 *	@return array			array of versions of file or FALSE on failure
	 */
	function get_file_versions($did, $file, $opt){
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
		/* flags */
		$fd = &$config->data_sql->domain_settings->flag_values;


		$q = "select ".$cd->version.", ".$cd->timestamp.", ".$cd->flags." 
		      from ".$td_name." 
			  where ".$cd->did." = ".$this->sql_format($did, "s")." and
			        ".$cd->filename." = ".$this->sql_format($file, "s");

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res);
			return false;
		}

		$out = array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out[$row[$cd->version]]['timestamp'] = $row[$cd->timestamp];
			$out[$row[$cd->version]]['deleted'] = (bool)($row[$cd->flags] & $fd["DB_DELETED"]);
			$out[$row[$cd->version]]['dir']     = (bool)($row[$cd->flags] & $fd["DB_DIR"]);
		}

		$res->free();

		return $out;
	}
	
}

?>
