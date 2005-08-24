<?
/*
 * $Id: method.get_cs_callers.php,v 1.1 2005/08/24 11:44:11 kozlik Exp $
 */

/*
 *  Function return array of associtive arrays containig caller screening entries of $user
 *
 *  Keys of associative arrays:
 *	  uri_re
 *	  action
 *	  param1
 *	  param2
 *    id
 *
 *  Possible options parameters:
 *
 *    csid	(string)	default: null
 *  
 */ 

class CData_Layer_get_cs_callers {
	var $required_methods = array();
	
	function get_CS_callers($user, $opt, &$errors){
		global $config, $sess;
		
		if (!$this->connect_to_db($errors)) return false;

	    $csid = (isset($opt['csid'])) ? $opt['csid'] : null;

		if (!is_null($csid)) $qw=" and uri_re!='".$csid."' "; 
		else $qw="";

		$q="select count(*) from ".$config->data_sql->table_calls_forwarding.
			" where ".$this->get_indexing_sql_where_phrase($user)." and purpose='screening'".$qw;

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$this->set_num_rows($row[0]);
		$res->free();

		/* if act_row is bigger then num_rows, correct it */
		$this->correct_act_row();



		$q="select uri_re, action, param1, param2 from ".$config->data_sql->table_calls_forwarding.
			" where ".$this->get_indexing_sql_where_phrase($user)." and purpose='screening'".$qw.
			" order by uri_re".
			" limit ".$this->get_act_row().", ".$this->get_showed_rows();
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]   = $row;
			$out[$i]['id']        = $row['uri_re'];
		}
		$res->free();
		return $out;

		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=$row;
		$res->free();
		return $out;
	}
	
}
?>
