<?
/*
 * $Id: ser_moni.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class CData_Layer extends CDL_common{

	function get_ser_moni_values(&$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
		
			$q="select param, lv, av, mv, ad, min_val, max_val, min_inc, max_inc  from ".$config->data_sql->table_ser_mon_agg;
	
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[$row->param]=$row;
			$res->free();
		
			return $out;
		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}
}

?>