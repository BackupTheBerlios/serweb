<?php
/**
 *	Functions needed for load module
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: load_modules.php,v 1.6 2008/06/26 09:51:14 kozlik Exp $
 *	@package    serweb
 *	@subpackage framework
 */ 

	/**
	 * Include file "include.php" from each loaded module
	 */
	function include_modules(){
		global $_SERWEB, $config;
		
		$loaded_modules = getLoadedModules();
	
		foreach($loaded_modules as $module){
			if (file_exists($_SERWEB["modulesdir"] . $module."/include.php")){ 
				require_once ($_SERWEB["modulesdir"] . $module."/include.php");
			}
		}
		
		unset($loaded_modules);
	}
	
	/**
	 * Call function <module_name>_init() for each loaded module
	 */
	function init_modules(){
		$loaded_modules = getLoadedModules();
	
		foreach($loaded_modules as $module){
		    echo $module = str_replace("-", "_", $module);
			if (function_exists($module."_init"))
				call_user_func($module."_init");
		}
		
		unset($loaded_modules);
	}
	

	/**
	 * Include additional module
	 * This function should be called before function init_modules()
	 *
	 * @param string $mod	name of module
	 * @access private
	 */	
	function include_module($mod){
		global $_SERWEB, $config;

		$config->modules[$mod] = true;

		if (file_exists($_SERWEB["modulesdir"] . $mod."/include.php")){ 
			require_once ($_SERWEB["modulesdir"] . $mod."/include.php");
		}
	}
	

	/* enable required modules */	
	if (isset($_required_modules)){
		foreach((array) $_required_modules as $m){
			$config->modules[$m] = true;
		}
	}
	
	
	include_modules();
?>
