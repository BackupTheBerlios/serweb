<?php
/*
 * $Id: method.write_file_content.php,v 1.1 2006/04/26 10:58:22 kozlik Exp $
 */

class CData_Layer_write_file_content {
	var $required_methods = array('set_db_charset');
	
	/**
	 *	store content of file specific for a domain to db
	 *
	 *  Possible options parameters:
	 *	  deleted		(bool)
	 *		set to true if the file has been deleted
	 *
	 *	  dir			(bool)
	 *		set to true if the file is directory
	 *
	 *	  new_version	(int)
	 *		In this option is returned next version of file 
	 *
	 *
	 *	@param string $did		domain id
	 *	@param string $file		filename (with path)
	 *	@param string $content	content of file
	 *	@param array $opt		associative array of options
	 *	@return bool			TRUE on success, FALSE on failure
	 */
	function write_file_content($did, $file, $content, &$opt){
		global $config, $lang_set;

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


	    $o_deleted	= (isset($opt['deleted'])) ? (bool)$opt['deleted'] : false;
	    $o_dir		= (isset($opt['dir']))     ? (bool)$opt['dir']     : false;


		$sem = new Shm_Semaphore(__FILE__, "s", 1, 0600);
		/* set semaphore to be sure there will not be generated same versions */
		if (!$sem->acquire())	return false;


		$q = "select max(".$cd->version.") 
		      from ".$td_name." 
			  where ".$cd->did." = ".$this->sql_format($did, "s")." and
			        ".$cd->filename." = ".$this->sql_format($file, "s");

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res, $errors);
			$sem->release();
			return false;
		}

		$version = 1;
		if ($row=$res->fetchRow(DB_FETCHMODE_ORDERED)){
			if (!is_null($row[0])) $version = $row[0] + 1;
		}

		/* set default charset for sql query */
		if (!empty($config->data_sql->set_charset)){
			if (false === $this->set_db_charset('default', null, $errors)){
				ErrorHandler::add_error($errors);
				$sem->release();
				return false;
			}
		}

		$flags = 0;
		if ($o_deleted)	$flags |= $fd["DB_DELETED"];
		if ($o_dir)		$flags |= $fd["DB_DIR"];

		$q="insert into ".$td_name." (
				   ".$cd->did.",
				   ".$cd->filename.",
				   ".$cd->version.",
				   ".$cd->timestamp.",
				   ".$cd->content.",
				   ".$cd->flags."
		    ) 
			values (
				   ".$this->sql_format($did, "s").", 
				   ".$this->sql_format($file, "s").", 
				   ".$this->sql_format($version, "n").",
				   ".$this->sql_format(time(), "n").",
				   ".$this->sql_format($content, "I").",
				   ".$this->sql_format($flags, "n")."
			 )";

		$opt['new_version'] = $version;

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			$sem->release();
			return false;
		}

		$sem->release();

		/* restore previous charset for sql queries */
		if (!empty($config->data_sql->set_charset)){
			if (false === $this->set_db_charset($lang_set['charset'], null, $errors)){
				ErrorHandler::add_error($errors);
				return false;
			}
		}

		return true;
	}
	
}

?>
