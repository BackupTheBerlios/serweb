<?
/*
 * $Id: method.get_location.php,v 1.2 2006/11/28 14:48:55 kozlik Exp $
 */

class CData_Layer_get_location {
	var $required_methods = array();
	
	/*
	 * get location of domainname in sip_adr
	 */

	function get_location($sip_adr, &$errors){
		global $config;

		$reg = &Creg::singleton();
		$domainname=$reg->get_domainname($sip_adr);

		if (!$this->connect_to_db($errors)) return false;

		$q="select location from ".$config->data_sql->table_netgeo_cache.
			" where domainname='".$domainname."'";
		$res=$this->db->query($q);
		/* if this query failed netgeo is probably not installed -- ignore */
		if (DB::isError($res)) {return "n/a";}
		$row = $res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();

		if (!$row) return "n/a";
		return $row->location;
	}
	
}
?>
