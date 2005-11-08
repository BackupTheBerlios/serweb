<?php
/*
 * $Id: method.mark_user_deleted.php,v 1.2 2005/11/08 15:43:14 kozlik Exp $
 */

class CData_Layer_mark_user_deleted {
	var $required_methods = array('update_attribute_of_user');
	
	/**
	 *  Mark user account as deleted
	 *
	 *  Possible options:
	 *
	 *    user			(Cserweb_auth)	default: null
	 *      account of user which should be enabled/disabled
	 *		this option is required
	 *      
	 *	  delete_asap 	(bool)			defult: false
	 *      if is true, user will be deleted as soon as possible (on next
	 *		cleaning of database)
	 *      
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function mark_user_deleted($opt, &$errors){
		global $config, $data;

		if (!$this->connect_to_db($errors)) return false;
		
	    $o_user     = (isset($opt['user'])) ? $opt['user'] : null;
	    $o_del_asap = (isset($opt['delete_asap'])) ? (bool)$opt['delete_asap'] : false;

		if (is_null($o_user)) {
			log_errors(PEAR::raiseError('subscriber which should be marked as deleted is not specified'), $errors); 
			return false;
		}

		$val = $o_del_asap ? 1 : time();
		if (false === $data->update_attribute_of_user($o_user, "deleted", $val, $errors)) return false;

		return true;
	}
}

?>
