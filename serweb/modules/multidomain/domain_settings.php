<?php
/*
 * $Id: domain_settings.php,v 1.1 2006/04/26 10:58:22 kozlik Exp $
 */

/**
 *	Class for manipulation with files specific for domains and for distribution 
 *	changes to other serwebs in cluster.
 */
class Domain_settings{
	var $filename, $did;
	var $versions = null;
	var $last_version = null;
	/** number of versions to keep in db */
	var $keep_ver;

    /**
     * Return a reference to a Domain_settings instance, only creating a new instance 
	 * if no Domain_settings instance currently exists.
     *
     * You should use this if there are multiple places you might create a
     * Domain_settings, you don't want to create multiple instances, and you don't 
	 * want to check for the existance of one each time. The singleton pattern 
	 * does all the checking work for you.
     *
     * <b>You MUST call this method with the $var = &Domain_settings::singleton($did, $filename) 
	 * syntax. Without the ampersand (&) in front of the method name, you will 
	 * not get a reference, you will get a copy.</b>
     *
     * @param	string	$did		domain id
     * @param	string	$filename	name of file to manipulate with (with path within domain directory)
     * @param	string	$versions	number of versions of this file to keep in DB
     * @access public
     */

    function &singleton($did, $filename, $versions=null) {
        static $instances = array();

		$key = $did.'|'.$filename;

		if (!isset($instances[$key])) $instances[$key] = new Domain_settings($did, $filename, $versions);
        return $instances[$key];
    }

	/**
	 *	Constructor
	 *	
     *	@param	string	$did		domain id
     *	@param	string	$filename	name of file to manipulate with (with path within domain directory)
     *	@param	string	$versions	number of versions of this file to keep in DB
     *	@access private
	 */

	function Domain_settings($did, $filename, $versions=null){
		$this->did = $did;
		$this->filename = $filename;
		$this->domain_dir = realpath(dirname(__FILE__)."/../../html/domains/");
		$this->keep_ver = $versions;
	}
	
	/**
	 *	Return all versions of file
	 *	
	 *	On error this function returning FALSE
	 *	
	 *	@return	array	indexed by version number, containing: timestamp, deleted flag, dir flag
	 */
	function get_versions(){
		global $data;
		
		if (!is_null($this->versions)) return $this->versions;
		
		$data->add_method('get_file_versions');
		if (false === $v = $data->get_file_versions($this->did, $this->filename, null)) return false;
		
		$this->versions = $v;
		return $this->versions;
	}
	
	/**
	 *	Callback for array_walk function
	 *	
	 *	@access private
	 */
	function get_last_version_aw($item, $key){
		if ($key > $this->last_version) $this->last_version = $key;
	}
	
	/**
	 *	Return last version of file
	 *	
	 *	@return	int		last version or FALSE on error
	 */
	function get_last_version(){
		if (!is_null($this->last_version)) return $this->last_version;
		
		if (false === $this->get_versions()) return false;
		
		$this->last_version = 0;
		array_walk($this->versions, array(&$this, 'get_last_version_aw'));
 		
		return $this->last_version;
	}
	
	/**
	 *	Return content of file (with given version)
	 *	
	 *	If $ver is 'initial' get content from filesystem. Otherwise get it from DB.
	 *	
	 *	@param	int		$ver	version
	 *	@return	string			content of file or FALSE on error
	 */
	function get_file_content($ver){
		global $data;

		if ($ver == 0 or $ver == 'initial'){

			$filename = $this->domain_dir."/_default/".$this->filename;
			if (!file_exists($filename)) return null;
		
			$fp = fopen($filename, "r");
			$file_content = fread($fp, 65536);
			fclose($fp);
		}
		else{
			$data->add_method('get_file_content');
			if (false === $file_content = $data->get_file_content($this->did, $this->filename, $ver, null)) return false;
		}
		return $file_content;
	}


	/**
	 *	Store given string to file and into DB
	 *	
	 *	@param	string	$content	
	 *	@return	string			TRUE on success or FALSE on error
	 */
	function save_file_content($content){
		global $data;

		$filename = $this->domain_dir."/".$this->did."/".$this->filename;
		$dirname = dirname($filename);

		if (!file_exists($dirname)) RecursiveMkdir($dirname, 0770);

		$data->add_method('write_file_content');
		$opt = array();
		if (false === $data->write_file_content($this->did, $this->filename, $content, $opt)) return false;

		if (isset($opt['new_version'])) {
			/* update info about versions of files stored on disc */
			$this->update_version_file($opt['new_version']);
			
			/* update array containing versions */
			if (!is_null($this->versions)) $this->versions[] = $opt['new_version'];
			$this->last_version = $opt['new_version'];
		}
		
		$fp = fopen($filename, "w");
		fwrite($fp, $content);
		fclose($fp);
		
		$this->purge_old_versions();
		
		return true;
	}

	/**
	 *	Same as function {@see save_file_content} but read the content from given file
	 *	
	 *	@param	string	$filename	
	 *	@return	string			TRUE on success or FALSE on error
	 */
	function save_file($filename){
		global $data;

		$fp = fopen($filename, "r");
		$file_content = fread($fp, 262144);
		fclose($fp);
		
		return $this->save_file_content($file_content);
	}

	/**
	 *	Update local file from DB to given version
	 *	
	 *	@param	int		$ver	version
	 *	@return	string			TRUE on success or FALSE on error
	 */
	function update_local_file($ver){
		global $data;

		$filename = $this->domain_dir."/".$this->did."/".$this->filename;
		$dirname = dirname($filename);

		if (!file_exists($dirname)) RecursiveMkdir($dirname, 0770);

		$data->add_method('get_file_content');
		if (false === $content = $data->get_file_content($this->did, $this->filename, $ver, null)) return false;

		$fp = fopen($filename, "w");
		fwrite($fp, $content);
		fclose($fp);

		return true;
	}

	/**
	 *	Delete file and wrote info to DB that file should be deleted on other servers
	 *	
	 *	@return	string			TRUE on success or FALSE on error
	 */
	function delete_file(){
		global $data;

		$filename = $this->domain_dir."/".$this->did."/".$this->filename;

		$data->add_method('write_file_content');
		$opt = array('deleted' => true);
		if (false === $data->write_file_content($this->did, $this->filename, null, $opt)) return false;

		$this->update_version_file(null);

		rm ($filename);

		$this->purge_old_versions();
		
		return true;
	}

	/**
	 *	Create directory and wrote info to DB that directory should be created on other servers
	 *	
	 *	@return	string			TRUE on success or FALSE on error
	 */
	function create_directory(){
		global $data;

		$filename = $this->domain_dir."/".$this->did."/".$this->filename;

		$data->add_method('write_file_content');
		$opt = array('dir' => true);
		if (false === $data->write_file_content($this->did, $this->filename, null, $opt)) return false;

		RecursiveMkdir($filename, 0770);

		$this->purge_old_versions();
		
		return true;
	}

	/**
	 *	Delete old versions of file from DB
	 *	
	 *	@return	string			TRUE on success or FALSE on error
	 */
	function purge_old_versions(){
		global $data;
		
		/* do nothing id $this->keep_ver is not set */
		if (is_null($this->keep_ver)) return true;
		
		if (false === $this->get_versions()) return false;
		
		if (count($this->versions) < $this->keep_ver) return true;

		/* sort the array of backup files by timestamp */
		ksort($this->versions, SORT_NUMERIC);

		$data->add_method('del_file_version');
		
		/* remove excess files */
		while (count($this->versions) > $this->keep_ver){
			/* get first version in array - the oldest version */
			reset($this->versions);
			$v = key($this->versions);
			
			/* delete the version */
			if (false === $data->del_file_version($this->did, $this->filename, $v, null)) return false;
			
			/* unset the version */
			unset($this->versions[$v]);
		}

		return true;
	}

	/**
	 *	Update version of file in file containing versions of local files
	 *	
	 *	@param	int		$ver	version
	 *	@return	bool			TRUE on success or FALSE on error
	 */
	function update_version_file($ver){

		$vf = new Version_file($this->domain_dir."/".$this->did."/versions.ini.php");
		if (false === $vf->open()) return false;

		if (is_null($ver)){
			//if file has been deleted, remove it from version file
			$vf->remove_file($this->filename);
		}
		else{
			$vf->set_version($this->filename, $ver);
		}
		
		if (false === $vf->close()) return false;
		return true;
	}

}

/**
 *	Class for read and store info about versions of local files
 */
class Version_file{
	var $filename;
	var $versions = array();
	var $error_in_ini_file;
	
	/**
	 *	Constructor
	 *	
	 *	@param	string	$filename	name of file where info is stored
	 */
	function Version_file($filename){
		$this->filename = $filename;
	}

	/**
	 *	Error handler for functin parse_ini_file 
	 *	
	 *	@access	private
	 */
	function ini_file_error_handler($errno, $errstr){
		$this->error_in_ini_file = $errstr;		
	}
	
	/**
	 *	Read versions file and store info in internal structure
	 *	
	 *	@return	bool			TRUE on success or FALSE on error
	 */
	function open(){
		$this->versions = array();
		if (file_exists($this->filename)){
			$this->error_in_ini_file = false;
			set_error_handler(array(&$this, "ini_file_error_handler"));
			$this->versions = parse_ini_file($this->filename);
			restore_error_handler();

			if ($this->error_in_ini_file) {
				sw_log("Can not read file ".$this->filename."; ".$this->error_in_ini_file, PEAR_LOG_ERR);
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 *	Set version for file
	 *	
	 *	@param	string	$file	filename
	 *	@param	int		$ver	version
	 */
	function set_version($file, $ver){
		$this->versions[$file] = $ver;
	}

	/**
	 *	Remove file
	 *	
	 *	@param	string	$file	filename
	 */
	function remove_file($file){
		if (isset($this->versions[$file])) unset($this->versions[$file]);
	}

	/**
	 *	Return versions of local files
	 *	
	 *	@return	array	array indexed by filename, containing version of file
	 */
	function get_all_files(){
		return $this->versions;
	}
	
	/**
	 *	Write changes back to versions file
	 *	
	 *	@return	bool			TRUE on success or FALSE on error
	 */
	function close(){

		$dirname = dirname($this->filename);
		if (!file_exists($dirname)) RecursiveMkdir($dirname, 0770);

		$fp = fopen($this->filename, "w");
		/* protect file from reading throught the web */
		fwrite($fp, "; <?php die( 'Please do not access this page directly.' ); ?".">\n");

		foreach($this->versions as $file => $version){
			fwrite($fp, $file." = ".$version."\n");
		}

		fclose($fp);
		
		return true;
	}
}
?>
