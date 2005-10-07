<?php
/*
 * $Id: method.get_domain_preferences.php,v 1.1 2005/10/07 07:28:00 kozlik Exp $
 */

class CData_Layer_get_domain_preferences {
	var $required_methods = array();
	
	/**
	 *  return associtive array containig domain preferences. 
	 *  Keys of associative array are names of domain preferences. 
	 *
	 *  Possible options:
	 *	  none
	 *      
	 *	@param string $id		id of domain
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return array			array of domain preferences or FALSE on error
	 */ 
	function get_domain_preferences($id, $opt, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->dom_pref;


		$q="select ".$c->att_name.", ".$c->att_value."
		    from ".$config->data_sql->table_dom_preferences."
			where ".$c->id." = '".$id."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			if (isset($out[$row[$c->att_name]])){
				if (is_array($out[$row[$c->att_name]])) $out[$row[$c->att_name]][] = $row[$c->att_value];
				else{
					$tmp = $out[$row[$c->att_name]];
					$out[$row[$c->att_name]] = array($tmp, $row[$c->att_value]);
				}
			}
			else{
				$out[$row[$c->att_name]] = $row[$c->att_value];
			}
		}
		$res->free();

		return $out;			
	}
	
}
?>
