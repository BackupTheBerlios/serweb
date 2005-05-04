<?php

	/**
	 * Include file "include.php" from each loaded module
	 */
	function include_modules(){
		global $_SERWEB, $config;
		
		$loaded_modules = getLoadedModules();
	
		foreach($loaded_modules as $module){
			if (file_exists($_SERWEB["serwebdir"] . "../modules/".$module."/include.php")){ 
				require_once ($_SERWEB["serwebdir"] . "../modules/".$module."/include.php");
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
			if (function_exists($module."_init"))
				call_user_func($module."_init");
		}
		
		unset($loaded_modules);
	}
	
	include_modules();
?>
