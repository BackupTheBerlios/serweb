<?php
/**
 * Functions for creating and deleting domains
 * 
 * @author    Karel Kozlik
 * @version   $Id: symlinks_functions.php,v 1.1 2005/10/26 12:49:50 kozlik Exp $
 * @package   serweb
 */ 

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

	$target = $serweb_root."html/domains/".$domain_id;
	$file = $serweb_root."html/domains/".$domain_name;

	if (false === create_domain_config_dir($domain_id, $errors)) return false;

	if (false === symlink($target, $file)){
		log_errors(PEAR::raiseError("Can't create domain specific config", NULL, NULL, 
		           NULL, "Can't create symlink. Link:".$file." target:".$target), $errors);
		return false;
	}
	
	if ($config->apache_vhosts_dir){
		$target = $serweb_root."html/";
		$file = $config->apache_vhosts_dir.$domain_name;
	
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
	
	$serweb_root = dirname(dirname(dirname(__FILE__)))."/";

	$success = TRUE;
	$file = $serweb_root."html/domains/".$domain_name;
	if(file_exists($file)) $success = @unlink($file);
//	$success = @system('rm  "'.$file.'"');		// substitute of previous line for windows cygwim
	if (false === $success) {
		log_errors(PEAR::raiseError("Can't delete file", NULL, NULL, 
		           NULL, "Filename:".$file), $errors);
		return false;
	}
	
	if ($config->apache_vhosts_dir){
		$file = $config->apache_vhosts_dir.$domain_name;
		if(file_exists($file)) $success = @unlink($file);
//		$success = @system('rm  "'.$file.'"');		// substitute of previous line for windows cygwim
		if (false === $success) {
			log_errors(PEAR::raiseError("Can't delete file", NULL, NULL, 
			           NULL, "Filename:".$file), $errors);
			return false;
		}
	}
	
	return true;
}

?>
