<?
/**
 *	Class for loading domain configuration
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: config_domain.php,v 1.5 2007/10/04 21:34:16 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Loads config parameters depending on domain
 *
 *	@package    serweb
 */
class CDomain_config{
	var $cfg = array();

	/**
	 * Loads config parameters depending on domain
	 *
	 * @todo	syntax errors in INI files are not reported
	 */
	function CDomain_config(){
		global $config;

        $config_file = multidomain_get_file("config.ini.php", false);

        if ($config_file){
			@$this->cfg = parse_ini_file($config_file);
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
