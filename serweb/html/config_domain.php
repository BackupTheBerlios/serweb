<?
/*
 * $Id: config_domain.php,v 1.1 2004/03/24 21:39:46 kozlik Exp $
 */

/*
	This class loads domain depending config parameters
*/

class CDomain_config{

	//loads domain depending config parameters
	function CDomain_config(){
		global $config;
		
		$dir=dirname(__FILE__)."/domains/";

		if (file_exists($dir.$config->domain."/config.php")){
			require($dir.$config->domain."/config.php");
		}
		else {
			require($dir."_default/config.php");
		}
	} //end constructor

	/*
		copy domain depending config parameters to global config object
	*/

	function activate_domain_config(){
		global $config;
	
		if (is_array($config->domain_depend_config)){
			foreach ($config->domain_depend_config as $param){
				if (isset($this->$param))	
					$config->$param = $this->$param;
			}
		}
	} // end function activate_domain_config()

} 

?>