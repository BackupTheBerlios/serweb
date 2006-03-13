<?
/*
 * $Id: load_apu.php,v 1.4 2006/03/13 15:30:47 kozlik Exp $
 */ 

function _apu_require($_required_apu, $add_controler_dl = true){
	global $data, $_SERWEB;
	static $_loaded_apu = array();

	$required_data_layer = array();

	$loaded_modules = getLoadedModules();

	
	foreach($_required_apu as $item){
		if (false ===  array_search($item, $_loaded_apu)){ //if required apu isn't loaded yet, load it

			$file_found = false;

			//try found apu in loaded modules
			foreach($loaded_modules as $module){
				if (file_exists($_SERWEB["serwebdir"] . "../modules/".$module."/".$item.".php")){ 
					require_once ($_SERWEB["serwebdir"] . "../modules/".$module."/".$item.".php");
					$file_found = true;
					break;
				}
			}

			// if apu was not found in modules, requere the one from 'application_layer' directory
			if (!$file_found){
				//require application unit
				require_once ($_SERWEB["serwebdir"] . "../application_layer/".$item.".php");	
			}
				
			$_loaded_apu[] = $item;
			$required_data_layer = array_merge($required_data_layer, call_user_func(array($item, 'get_required_data_layer_methods')));	
		}
	}
	
	if ($add_controler_dl){
		$required_data_layer = array_merge($required_data_layer, page_conroler::get_required_data_layer_methods());	
	}

	$data->add_method($required_data_layer);
} 

function load_apu($apu){
	$apu = array($apu);
	_apu_require($apu, false);
}

require_once ($_SERWEB["serwebdir"] . "../application_layer/oohform_ext.php");
require_once ($_SERWEB["serwebdir"] . "../application_layer/apu_base_class.php");
require_once ($_SERWEB["serwebdir"] . "../application_layer/page_controler.php");


if (!isset($_required_apu) or !is_array($_required_apu)) 
	$_required_apu = array();
_apu_require($_required_apu);

$controler = new page_conroler();

?>
