<?
/*
 * $Id: method.get_events.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_events {
	var $required_methods = array();
	
	function get_events($user, &$errors){
		global $config, $sess;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="select id, uri, description ".
			"from ".$config->data_sql->table_event." ".
			"where ".$this->get_indexing_sql_where_phrase($user);
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_OBJECT); $i++){
			$out[$i]['uri']         = $row->uri;
			$out[$i]['description'] = $row->description;
			$out[$i]['url_unsubsc'] = $sess->url("notification_subscription.php?kvrk=".uniqid("")."&dele_id=".$row->id);
		} 
		$res->free();
		return $out;
	}
	
}
?>
