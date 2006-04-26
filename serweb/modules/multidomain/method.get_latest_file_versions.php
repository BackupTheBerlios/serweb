<?php
/*
 * $Id: method.get_latest_file_versions.php,v 1.1 2006/04/26 10:58:22 kozlik Exp $
 */

class CData_Layer_get_latest_file_versions {
	var $required_methods = array();
	
	/**
	 *	get latest versions of files specific for domain
	 *
	 *  Possible options parameters:
	 *		none
	 *
	 *	@param string $did		domain id
	 *	@param array $opt		associative array of options
	 *	@return array			array of versions of file or FALSE on failure
	 */
	function get_latest_file_versions($did, $opt){
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


/* 
	clean solution but unfortunately not working in mysql 4.0
	
			$q = "select ds.".$cd->filename.", ds.".$cd->version." as ver, ds.".$cd->flags." 
			      from ".$td_name." ds join (
				      select ".$cd->filename.", max(".$cd->version.") as ver
				      from ".$td_name."
				      where ".$cd->did." = ".$this->sql_format($did, "s")."
				      group by ".$cd->filename."
				  ) v on (ds.".$cd->filename." = v.".$cd->filename." and
				          ds.".$cd->version." = v.ver)
				  where ds.".$cd->did." = ".$this->sql_format($did, "s")." 
				  group by ds.".$cd->filename;
*/

		/* This query return multiple rows for each file. Ordering row by 
		   version is important because older versions are replaced by newest
		   in output array.
		 */
		$q = "select ".$cd->filename.", ".$cd->flags.", max(".$cd->version.") as ver 
		      from ".$td_name." 
			  where ".$cd->did." = ".$this->sql_format($did, "s")." 
			  group by ".$cd->filename.", ".$cd->flags."
			  order by ver";


		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res, $errors);
			return false;
		}

		$out = array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out[$row[$cd->filename]]['version'] = $row['ver'];
			$out[$row[$cd->filename]]['deleted'] = (bool)($row[$cd->flags] & $fd["DB_DELETED"]);
			$out[$row[$cd->filename]]['dir']     = (bool)($row[$cd->flags] & $fd["DB_DIR"]);
		}

		$res->free();

		return $out;
	}
}

?>
