<?
/*
 * $Id: config_domain.php,v 1.3 2005/12/22 12:58:28 kozlik Exp $
 */

/**
 *	Loads config parameters depending on domain
 */

class CDomain_config{
	var $cfg = array();

	/**
	 * Loads config parameters depending on domain
	 *
	 * @todo: syntax errors in INI files are not reported
	 */
	function CDomain_config(){
		global $config;
		
		$dir=dirname(__FILE__)."/domains/";

		if (file_exists($dir.$config->domain."/config.php")){
			@$this->cfg = parse_ini_file($dir.$config->domain."/config.ini.php");
		}
		else {
			$this->cfg = parse_ini_file($dir."_default/config.ini.php");
		}
	} //end constructor

	/**
	 *	Copy config parameters loaded by constructor into global config object
	 */
	function activate_domain_config(){
		global $config;

		if (!is_array($config->domain_depend_config)) $config->domain_depend_config = array();

		foreach($this->cfg as $k => $v){
			if ((substr($k, 0, 13) == "html_headers_") and in_array("html_headers", $config->domain_depend_config)){
				$config->html_headers[] = $v;
			}
			
			if (in_array($k, $config->domain_depend_config)){
				$config->$k = $v;
			}
		
		}
		unset ($this->cfg);

	} // end function activate_domain_config()
} 

?>
