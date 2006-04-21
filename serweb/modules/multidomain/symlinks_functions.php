<?php
/**
 * Functions for creating and deleting domains
 * 
 * @author    Karel Kozlik
 * @version   $Id: symlinks_functions.php,v 1.3 2006/04/21 07:55:11 kozlik Exp $
 * @package   serweb
 */ 

class FileJournal {
	var $files_created = array();
	var $files_deleted = array();
	
    /**
     * Return a reference to a FileJournal instance, only creating a new instance 
	 * if no FileJournal instance currently exists.
     *
     * You should use this if there are multiple places you might create a
     * FileJournal, you don't want to create multiple instances, and you don't 
	 * want to check for the existance of one each time. The singleton pattern 
	 * does all the checking work for you.
     *
     * <b>You MUST call this method with the $var = &FileJournal::singleton() 
	 * syntax. Without the ampersand (&) in front of the method name, you will 
	 * not get a reference, you will get a copy.</b>
     *
     * @access public
     */

    function &singleton() {
        static $instance = null;

		if (is_null($instance)) {
			$instance = new FileJournal();
		}
        return $instance;
    }
	
    /**
     *	Clear journal
     *
     *	This method may be called staticaly e.g.: FileJournal::clear();
     *	or dynamicaly e.g. $e = &FileJournal::singleton(); $e->clear();
     *
     *	@return	none
     */
	function clear(){
		
		if (isset($this) and is_a($this, 'FileJournal')) $in = &$this;
		else $in = &FileJournal::singleton();

		$in->files_created = array();
		$in->files_deleted = array();
	}

    /**
     *	Add name of deleted file to the journal
     *
     *	This method may be called staticaly e.g.: FileJournal::add_deleted_file($file);
     *	or dynamicaly e.g. $e = &FileJournal::singleton(); $e->add_deleted_file($file);
     *
     *	@param	string	$file	name of deleted file
     *	@return	none
     */
     
	function add_deleted_file($file){
		
		if (isset($this) and is_a($this, 'FileJournal')) $in = &$this;
		else $in = &FileJournal::singleton();

		$in->files_deleted[] = $file;
	}


    /**
     *	Add name of created file to the journal
     *
     *	This method may be called staticaly e.g.: FileJournal::add_created_file($file);
     *	or dynamicaly e.g. $e = &FileJournal::singleton(); $e->add_created_file($file);
     *
     *	@param	string	$file	name of deleted file
     *	@return	none
     */
     
	function add_created_file($file){
		
		if (isset($this) and is_a($this, 'FileJournal')) $in = &$this;
		else $in = &FileJournal::singleton();

		$in->files_created[] = $file;
	}


    /**
     *	Rollback changes
     *
     *	This method for now only deleting created files - it don't rollback
     *	deleted files.
     *
     *	This method may be called staticaly e.g.: FileJournal::rollback();
     *	or dynamicaly e.g. $e = &FileJournal::singleton(); $e->rollback();
     *
     *	@return	none
     */
	function rollback(){
		
		if (isset($this) and is_a($this, 'FileJournal')) $in = &$this;
		else $in = &FileJournal::singleton();

		foreach ($in->files_created as $file){
			sw_log ("FileJournal::rollback() - deleting file: ".$file, PEAR_LOG_DEBUG);
			if (false === rm ($file)){
				sw_log ("Can't rollback created files. Can't delete file: ".$file, PEAR_LOG_ERR);
			}
		}
	}
}

/**
 *  Method create directory with domain specific config 
 *	Function create the directory by copy the html/domains/_default
 *
 *	@param string $domainname	name of directory with domain config files
 *	@param array  $errors		array with error messages
 *	@return bool				return TRUE on success, FALSE on failure
 */
 
function create_domain_config_dir($domainname, &$errors){
	$serweb_root = dirname(dirname(dirname(__FILE__)))."/";

	$target = $serweb_root."html/domains/".$domainname;

	if (file_exists($target)) return true;

	FileJournal::add_created_file($target);

	if (false === copyr ($serweb_root."html/domains/_default", $target)){
		log_errors(PEAR::raiseError("Can't create domain specific config", NULL, NULL, 
		           NULL, "Can't create dicetory with domain config. Directory:".$target), $errors);
		return false;
	}
	
	return true;
}


/**
 *  Method remove directory with domain specific config 
 *
 *	@param string $domainname	name of directory with domain config files
 *	@param array  $errors		array with error messages
 *	@return bool				return TRUE on success, FALSE on failure
 */

function remove_domain_config_dir($domainname, &$errors){
	$serweb_root = dirname(dirname(dirname(__FILE__)))."/";

	$target = $serweb_root."html/domains/".$domainname;

	if (!file_exists($target)) return true;

	if (false === rm ($target)){
		log_errors(PEAR::raiseError("Can't remove domain specific config", NULL, NULL, 
		           NULL, "Can't remove dicetory with domain config. Directory:".$target), $errors);
		return false;
	}
	FileJournal::add_deleted_file($target);
	
	return true;
}


/**
 *	Method create symlinks for new domain name (alias)
 *
 *	Method create symlinks into directory with domain specific config and 
 *	into directory with virtual hosts (for purpose of apache)
 *
 *	@param string $domain_name	name of domain (alias)
 *	@param array  $errors		array with error messages
 *	@return bool				return TRUE on success, FALSE on failure
 */

function domain_create_symlinks($domain_id, $domain_name, &$errors){
	global $config;
	
	$serweb_root = dirname(dirname(dirname(__FILE__)))."/";

	if (false === create_domain_config_dir($domain_id, $errors)) return false;

	if ($config->apache_vhosts_dir){
		$target = $serweb_root."html/";
		$file = $config->apache_vhosts_dir.$domain_name;

		FileJournal::add_created_file($file);
	
		if (false === symlink($target, $file)){
			log_errors(PEAR::raiseError("Can't create virtual server", NULL, NULL, 
			           NULL, "Can't create symlink. Link:".$file." target:".$target), $errors);
			return false;
		}
	}
	
	return true;
}

/**
 *	Method remove symlinks for domain name (alias)
 *
 *	Method remove symlinks from directory with domain specific config and 
 *	from directory with virtual hosts (for purpose of apache)
 *
 *	@param string $domain_name	name of domain (alias)
 *	@param array  $errors		array with error messages
 *	@return bool				return TRUE on success, FALSE on failure
 */

function domain_remove_symlinks($domain_name, &$errors){
	global $config;
	
	if ($config->apache_vhosts_dir){

		$success = TRUE;
		$file = $config->apache_vhosts_dir.$domain_name;

		if(file_exists($file)) $success = @unlink($file);
//		$success = @system('rm  "'.$file.'"');		// substitute of previous line for windows cygwim

		if (false === $success) {
			log_errors(PEAR::raiseError("Can't delete file", NULL, NULL, 
			           NULL, "Filename:".$file), $errors);
			return false;
		}

		FileJournal::add_deleted_file($file);
	}
	
	return true;
}

?>
