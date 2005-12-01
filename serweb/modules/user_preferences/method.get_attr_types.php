<?
/*
 * $Id: method.get_attr_types.php,v 1.1 2005/12/01 16:11:29 kozlik Exp $
 */

class Cattrib{
	var $att_name;
	var $att_value;
	var $att_rich_type;
	var $att_type_spec;

	function Cattrib($att_name, $att_rich_type, $att_type_spec){
		$this->att_name=$att_name;
		$this->att_rich_type=$att_rich_type;
		$this->att_type_spec=$att_type_spec;
	}
}

class CData_Layer_get_attr_types{
	var $required_methods = array();
	
	/* 
	 * return list of all attributes without atribute named as $att_edit
	 * $att_edit may be null
	 */
	 
	function get_attr_types($att_edit, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		/* table's name */
		$t_at = &$config->data_sql->attr_types->table_name;
		/* col names */
		$c = &$config->data_sql->attr_types->cols;


		if (! is_null($att_edit)) $qw=" ".$c->name." != '".$att_edit."' "; 
		else $qw = $this->get_sql_bool(true);

		$q="select ".$c->name.", ".$c->rich_type.", ".$c->type_spec." 
		    from ".$t_at." 
			where ".$qw." 
			order by ".$c->name;
			
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
	
		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out[$row->att_name]=new Cattrib($row[$c->name], 
											 $row[$c->rich_type], 
											 $row[$c->type_spec]);
		}
		$res->free();
		return $out;

	}
	
}
?>
