<?php
/*
 * $Id: method.set_db_charset.php,v 1.2 2005/08/29 14:27:06 kozlik Exp $
 */

class CData_Layer_set_db_charset {
	var $required_methods = array();
	
	/*
	 * set charset for comunication with DB
	 */

	function set_db_charset($charset, $opt, &$errors){
	 	global $config;

		$this->db_charset = $charset;
	 	
		/* if connection to db is estabilished run sql query setting the charset */		
		if ($this->db){
			if ($this->db_host['parsed']['phptype'] == 'mysql'){
			 	static $charset_mapping = array ('utf-8' => 'utf8',
				                                 'iso-8859-1' => 'latin1',
				                                 'iso-8859-2' => 'latin2',
				                                 'windows-1250' => 'cp1250',
				                                 'iso-8859-7' => 'greek',
				                                 'iso-8859-8' => 'hebrew',
				                                 'iso-8859-9' => 'latin5',
				                                 'iso-8859-13' => 'latin7',
				                                 'windows-1251' => 'cp1251');
			}
			else{
			 	static $charset_mapping = array ('utf-8' => 'utf8',
				                                 'iso-8859-1' => 'latin1',
				                                 'iso-8859-2' => 'latin2',
				                                 'iso-8859-3' => 'latin3',
				                                 'iso-8859-4' => 'latin4',
				                                 'iso-8859-5' => 'ISO_8859_5',
				                                 'iso-8859-6' => 'ISO_8859_6',
				                                 'iso-8859-7' => 'ISO_8859_7',
				                                 'iso-8859-8' => 'ISO_8859_8',
				                                 'iso-8859-9' => 'latin5',
				                                 'iso-8859-10' => 'latin6',
				                                 'iso-8859-13' => 'latin7',
				                                 'iso-8859-14' => 'latin8',
				                                 'iso-8859-15' => 'latin9',
				                                 'iso-8859-16' => 'latin10',
				                                 'windows-1250' => 'win1250',
				                                 'windows-1251' => 'win1251');
		
			}
	
			$ch = isset($charset_mapping[$this->db_charset]) ?
			            $charset_mapping[$this->db_charset] :
			            $this->db_charset;

			$q="set NAMES '".$ch."'";
	
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
