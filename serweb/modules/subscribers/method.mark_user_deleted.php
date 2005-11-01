<?php
/*
 * $Id: method.mark_user_deleted.php,v 1.1 2005/11/01 17:58:40 kozlik Exp $
 */

class CData_Layer_mark_user_deleted {
	var $required_methods = array('update_attribute_of_user');
	
	/**
	 *  Mark user account as deleted
	 *
	 *  Possible options:
	 *
	 *    user		(Cserweb_auth)   	default: null
	 *      account of user which should be enabled/disabled
	 *		this option is required
	 *      
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function mark_user_deleted($opt, &$errors){
		global $config, $data;

		if (!$this->connect_to_db($errors)) return false;
		
	    $o_user = (isset($opt['user'])) ? $opt['user'] : null;

		if (is_null($o_user)) {
			log_errors(PEAR::raiseError('subscriber which should be marked as deleted is not specified'), $errors); 
			return false;
		}

		if (false === $data->update_attribute_of_user($o_user, "deleted", 1, $errors)) return false;

		return true;
	}
}

?>
