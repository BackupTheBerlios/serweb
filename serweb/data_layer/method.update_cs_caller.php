<?
/*
 * $Id: method.update_cs_caller.php,v 1.2 2005/04/21 15:09:46 kozlik Exp $
 */

class CData_Layer_update_cs_caller {
	var $required_methods = array();
	
	function update_CS_caller($user, $uri, $uri_re, $action, $param1, $param2, &$errors){
		global $config, $lang_str;
		
		if (!$this->connect_to_db($errors)) return false;

		if ($uri) $q="update ".$config->data_sql->table_calls_forwarding." set ".
									"uri_re='$uri_re', ".
									"action='".$action."', ".
									"param1='".$param1."', ".
									"param2='".$param2."' ".
			"where uri_re='$uri' and purpose='screening' and ".$this->get_indexing_sql_where_phrase($user);

		else {
			$att=$this->get_indexing_sql_insert_attribs($user);
			
			$q="insert into ".$config->data_sql->table_calls_forwarding." (".$att['attributes'].", uri_re, purpose, action, param1, param2) ".
				"values (".$att['values'].",
						'$uri_re',
						'screening',
						'".$action."',
						'".$param1."',
						'".$param2."')";
		}

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_ALREADY_EXISTS)
				$errors[]=$lang_str['err_screening_already_exists'];
			else log_errors($res, $errors); 
			return false;
		}
		return true;
	}
	
}
?>
