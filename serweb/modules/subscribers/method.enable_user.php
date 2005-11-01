<?php
/*
 * $Id: method.enable_user.php,v 1.1 2005/11/01 17:58:40 kozlik Exp $
 */

class CData_Layer_enable_user {
	var $required_methods = array('update_attribute_of_user');
	
	/**
	 *  Enable or disable user account
	 *
	 *  Possible options:
	 *
	 *    user		(Cserweb_auth)   	default: null
	 *      account of user which should be enabled/disabled
	 *		this option is required
	 *      
	 *    disable	(bool)  	default: false
	 *      if true user will be disabled, otherwise wil be enabled
	 *      
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function enable_user($opt, &$errors){
		global $config, $data;

		if (!$this->connect_to_db($errors)) return false;

	    $o_user = (isset($opt['user'])) ? $opt['user'] : null;
	    $o_disable = (isset($opt['disable'])) ? $opt['disable'] : false;

		if (is_null($o_user)) {
			log_errors(PEAR::raiseError('subscriber which should be enabled or disabled is not specified'), $errors); 
			return false;
		}
		
		if (false === $data->update_attribute_of_user($o_user, "disabled", $o_disable?1:0, $errors)) return false;

		return true;
	}
	
}

?>
