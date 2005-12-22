<?php
/*
 * $Id: attributes.php,v 1.1 2005/12/22 13:51:23 kozlik Exp $
 */

class Attributes{


	function get_attribute($name, $opt){

		/* set default values for options */
		$opt_uid = isset($opt["uid"]) ? $opt["uid"] : null;
		$opt_did = isset($opt["did"]) ? $opt["did"] : null;

		if (!is_null($opt_uid)){
			$attrs = &User_Attrs::singleton($opt_uid);
			if (false === $attr = $attrs->get_attribute($name)) return false;
			
			if (!is_null($attr)) return $attr;
		}

		if (!is_null($opt_did)){
			$attrs = &Domain_Attrs::singleton($opt_did);
			if (false === $attr = $attrs->get_attribute($name)) return false;
			
			if (!is_null($attr)) return $attr;
		}

		$attrs = &Global_Attrs::singleton();
		if (false === $attr = $attrs->get_attribute($name)) return false;
			
		if (!is_null($attr)) return $attr;
		
		/* attribute not found */
		return null;
	}

}
?>
