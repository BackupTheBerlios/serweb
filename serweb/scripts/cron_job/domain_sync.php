<?php
/**
 * Cron job maintenances database - should be run after midnight
 *
 * $Id: domain_sync.php,v 1.2 2006/05/02 14:58:07 kozlik Exp $
 */

$_data_layer_required_methods=array('get_latest_file_versions');

$_required_modules = array('multidomain');

require "prepend.php";
require $_SERWEB["serwebdir"]."../modules/multidomain/domain_settings.php";

/**
 *	Update domain directory for soecified domain 
 *
 *	@param string $did	domain id
 *	@return bool		return TRUE on success, FALSE on failure
 */
function update_domain_dir($did){
	global $data, $_SERWEB;
	
	$domain_dir = $_SERWEB["serwebdir"]."domains/".$did."/";
	$vf = new Version_file($domain_dir."versions.ini.php");
	
	/* Open file containing versions of other files
	   Do not check for errors here - if there will be error in version file, 
	   the file will be recreated by values in DB
	 */
	$vf->open(); 
	$local_versions = $vf->get_all_files();

	/* get versions of files in DB */
	if (false === $files = $data->get_latest_file_versions($did, null)) return false;

	/* synchronize directory with DB */
	foreach($files as $file => $f_prop){
		if ($f_prop['deleted']){
			/* file should be deleted */
			if (isset($local_versions[$file]))  $vf->remove_file($file);
			if (file_exists($domain_dir.$file)) rm ($domain_dir.$file);
		}
		elseif ($f_prop['dir']){
			/* directory */
			if (!file_exists($domain_dir.$file)) RecursiveMkdir($domain_dir.$file, 0770);
		}
		elseif(!isset($local_versions[$file]) or 
		       !file_exists($domain_dir.$file) or
		       $local_versions[$file] < $f_prop['version']){

			/* file should be updated/created */
			$ds = &Domain_settings::singleton($did, $file);
			if (false === $ds->update_local_file($f_prop['version'])) return false;

			$vf->set_version($file, $f_prop['version']);
		}
	}		

	$vf->close(); 
	
	return true;
}


function main(&$errors){
	global $config, $_SERWEB;

	$dh = &Domains::singleton();
	if (false === $domains = $dh->get_all_dids()) return false;

	foreach($domains as $did){
		if (false === update_domain_dir($did)) return false;
	}


	/* update vhosts dir */
	if ($config->apache_vhosts_dir){
		$target = realpath($_SERWEB["serwebdir"]);

		$local_links = array();

		/* get list of local symlinks */
		$d = dir($config->apache_vhosts_dir);
		while (false !== ($entry = $d->read())) {
  			if (is_link($config->apache_vhosts_dir.$entry) and 
			    realpath(readlink($config->apache_vhosts_dir.$entry)) == $target){

				$local_links[] = $entry;
			}

/* this is need for testing on windows
  			if ($entry != "." and $entry != ".."){
				$local_links[] = substr($entry, 0, -4);
			}
 */
		}
		$d->close();

		/* get list of domain names */
		$dom_names = array();
		if (false === $domains = $dh->get_domains()) return false;
		foreach ($domains as $k => $v){
			$dom_names[] = $k;
		}		

		/* synchronize links in vhosts dir with DB */
		$d_lnk = array_diff($local_links, $dom_names);
		$c_lnk = array_diff($dom_names, $local_links);
		
		foreach($d_lnk as $v) remove_vhost_symlink($v);
		foreach($c_lnk as $v) create_vhost_symlink($v);
	}

	return true;
}

$errors = array();

$eh = &ErrorHandler::singleton();
$eh->set_errors_ref($errors);


main($errors);

$errors = &$eh->get_errors_array();

if (is_array($errors) and count($errors)) {
	echo "There were errors during scripts run.\n";
	foreach($errors as $val) {
		fwrite(STDERR, $val."\n");
		sw_log("cron job: domain sync - ".$val, PEAR_LOG_ERR);
	}
	exit (1);
}

exit (0);

?>
