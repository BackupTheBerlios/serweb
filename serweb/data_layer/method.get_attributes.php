<?
/*
 * $Id: method.get_attributes.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class Cattrib{
	var $att_name;
	var $att_value;
	var $att_rich_type;
	var $att_type_spec;
	var $default_value;

	function Cattrib($att_name, $att_value, $att_rich_type, $att_type_spec){
		$this->att_name=$att_name;
		$this->att_value=$att_value;
		$this->att_rich_type=$att_rich_type;
		$this->att_type_spec=$att_type_spec;
		$this->default_value=$att_value;
	}
}

class CData_Layer_get_attributes {
	var $required_methods = array();
	
	/* 
	 * return list of all attributes without atribute namad as $att_edit
	 * $att_edit may be null
	 */
	 
	function get_attributes($att_edit, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		if (! is_null($att_edit)) $qw=" att_name != '$att_edit' "; else $qw="1";

		$q="select att_name, att_rich_type, att_type_spec, default_value from ".$config->data_sql->table_user_preferences_types.
			" where ".$qw.
			" order by att_name";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
	
		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
			$out[$row->att_name]=new Cattrib($row->att_name, $row->default_value, $row->att_rich_type, $row->att_type_spec);
		}
		$res->free();
		return $out;

	}
	
}
?>
