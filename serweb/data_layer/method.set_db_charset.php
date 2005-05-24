<?php
/*
 * $Id: method.set_db_charset.php,v 1.1 2005/05/24 12:22:37 kozlik Exp $
 */

class CData_Layer_set_db_charset {
	var $required_methods = array();
	
	/*
	 * set charset for comunication with DB
	 */

	function set_db_charset($charset, $opt, &$errors){
	 	global $config;
	 	
	 	static $charset_mapping = array ('utf-8' => 'utf8',
		                                 'iso-8859-1' => 'latin1',
		                                 'iso-8859-2' => 'latin2',
		                                 'windows-1250' => 'cp1250',
		                                 'iso-8859-7' => 'greek',
		                                 'iso-8859-8' => 'hebrew',
		                                 'iso-8859-9' => 'latin5',
		                                 'iso-8859-13' => 'latin7',
		                                 'windows-1251' => 'cp1251');

		$this->db_charset = $charset;

		/* if connection to db is estabilished run sql query setting the charset */		
		if ($this->db){

			$ch = isset($charset_mapping[$this->db_charset]) ?
			            $charset_mapping[$this->db_charset] :
			            $this->db_charset;

			$q="set NAMES ".$ch;
	
			$res=$this->db->query($q);
			if (DB::isError($res)) {
				log_errors($res, $errors); return false;
			}
		}
		
		/* otherwise do nothing, charset will be set after connect to DB */

		return true;
	}
}
?>
