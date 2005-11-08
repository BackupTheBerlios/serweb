<?php
/*
 * $Id: method.get_sql_user_flags.php,v 1.2 2005/11/08 15:29:20 kozlik Exp $
 */

class CData_Layer_get_sql_user_flags {
	var $required_methods = array();
	
	/**
	 *  return array containing sql phrases for obtain deleted and disabled flags of subscriber
	 *
	 *  Possible options:
	 *	  none
	 *      
	 *	@param array $opt		associative array of options
	 *	@return array			sql phrases
	 */ 
	function get_sql_user_flags($opt){
		global $config;

		$cup = &$config->data_sql->usr_pref;
		$cs  = &$config->data_sql->subscriber;

	    $opt_deleted_before = (isset($opt['deleted_before'])) ? $opt['deleted_before'] : false;

		$o = array();

		$o['disabled']['cols'] = ", upd.".$cup->att_value." as user_disabled ";
		$o['disabled']['from'] = 
				" left outer join ".$config->data_sql->table_user_preferences." upd on ".
				(($config->users_indexed_by=='uuid')?
					"(s.".$cs->uuid."=upd.".$cup->uuid." and upd.".$cup->att_name."='disabled') ":
					"(s.".$cs->username."=upd.".$cup->username." and s.".$cs->domain."=upd.".$cup->domain." and upd.".$cup->att_name."='disabled') ");
		$o['disabled']['where'] = " not (COALESCE(upd.".$cup->att_value.", 0) > 0) and ";

		$o['deleted']['cols'] = ", upx.".$cup->att_value." as user_deleted ";
		$o['deleted']['from'] = 
				" left outer join ".$config->data_sql->table_user_preferences." upx on ".
				(($config->users_indexed_by=='uuid')?
					"(s.".$cs->uuid."=upx.".$cup->uuid." and upx.".$cup->att_name."='deleted') ":
					"(s.".$cs->username."=upx.".$cup->username." and s.".$cs->domain."=upx.".$cup->domain." and upx.".$cup->att_name."='deleted') ");
		$o['deleted']['where'] = 
			$opt_deleted_before ? 
				" upx.".$cup->att_value." < ".$opt_deleted_before." and upx.".$cup->att_value." > 0 and " : 
				" not (COALESCE(upx.".$cup->att_value.", 0) > 0) and ";
							
		return $o;					
	}
	
}
?>
