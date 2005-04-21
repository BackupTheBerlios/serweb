<?php
/*
 * $Id: method.get_proxies_xxl.php,v 1.2 2005/04/21 15:09:45 kozlik Exp $
 */

/*
 *  Function return array of associtive arrays containig all proxies of XXL cluster
 *
 *  Keys of associative arrays:
 *    proxy					sip uro of proxy
 *
 *  Possible options parameters:
 *    sort	  	(bool) default: false
 *      Should be output array sorted?
 *
 */ 

class CData_Layer_get_proxies_xxl {
	var $required_methods = array();

	function get_proxies_xxl($opt, &$errors){
		if (!$this->connect_to_xml_rpc(null, $errors)) return false;

	    $opt_sort = (isset($opt['sort'])) ? (bool)$opt['sort'] : false;


//	return array(array('proxy' => 'sip:localhost'),
//	             array('proxy' => 'sip:127.0.0.1'));

		$msg = new XML_RPC_Message_patched('get_all_proxies');
		$res = $this->rpc->send($msg);

		if ($this->rpc_is_error($res)){
			log_errors($res, $errors); return false;
		}
		
	    $val = $res->value();
		$proxies = explode("\n", $val->scalarval());

		if ($opt_sort) sort($proxies);

		$out = array();
		foreach($proxies as $v){
			if (trim($v)) $out[] = array("proxy" => trim($v));
		}

		return $out;
	}
}

?>
