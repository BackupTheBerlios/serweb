<?
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_missed_calls_of_yesterday.php,v 1.4 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for get missed calls of user
 * 
 *	@package    serweb
 */ 
class CData_Layer_get_missed_calls_of_yesterday {
	var $required_methods = array();
	
	function get_missed_calls_of_yesterday($uid, $opt){
		global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}
	
		if ($this->db_host['parsed']['phptype'] == 'mysql') {
			$q_date = " date_format(request_timestamp, '%Y-%m-%d')=date_format(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d') ";
		}
		else {
			$q_date = " date_trunc('day', request_timestamp)=date_trunc('day', (CURRENT_DATE - INTERVAL '1 DAY')) ";
		}

		$q="SELECT from_uri, sip_from, request_timestamp, sip_status  ".
			"FROM ".$config->data_sql->table_missed_calls." ".
			"WHERE to_uid='".$uid."' and ".$q_date.
			"ORDER BY request_timestamp DESC ";


		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=$row;
		$res->free();
	
		return $out;
	}
	
}
?>
