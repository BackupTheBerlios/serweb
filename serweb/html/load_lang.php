<?
/**
 * Functions for corect pick language file and load it
 * 
 * @author    Karel Kozlik
 * @version   $Id: load_lang.php,v 1.13 2007/02/14 16:36:39 kozlik Exp $
 * @package   serweb
 * @subpackage framework
 */ 

/**
 *	Include configuration
 */
require_once($_SERWEB["configdir"]."config_lang.php");

/**
 * Class holding various methods for internationalization
 *
 * @package    serweb
 * @subpackage framework
 */ 
class Lang {

	function internationalize($str){
		global $lang_str;
		
		if (substr($str, 0, 1) == '@' and 
		    isset($lang_str[substr($str, 1)])){
		
			return $lang_str[substr($str, 1)];
		}
		
		return $str;
	}
}

/**
 * Analyzes some PHP environment variables to find the most probable language
 * that should be used
 *
 * @param   string   string to analyze
 * @param   integer  type of the PHP environment variable which value is $str
 *
 * @global  array    the list of available translations
 * @global  string   the retained translation keyword
 *
 * @access  private
 */
 
function lang_detect($str = '', $envType = ''){
	global $available_languages;
	
	foreach($available_languages AS $key => $value) {
		// $envType =  1 for the 'HTTP_ACCEPT_LANGUAGE' environment variable,
		//             2 for the 'HTTP_USER_AGENT' one
		//             3 for the user/domain/global attribute
		if (($envType == 1 && eregi('^(' . $value[0] . ')(;q=[0-9]\\.[0-9])?$', $str))
			|| ($envType == 2 && eregi('(\(|\[|;[[:space:]])(' . $value[0] . ')(;|\]|\))', $str))
			|| ($envType == 3 && ($value[2] == substr($str, 0, 2)))) {
			return $key;
		}
	}
	return false;
} 



function determine_lang(){
	global $config, $data, $available_languages;
	$an = &$config->attr_names;
	$did = null;


	// Lang forced
	if (!empty($config->force_lang) && isset($available_languages[$config->force_lang])) {
    	$_SESSION['lang'] = $config->force_lang;
	}

	
	// If session variable is set, obtain language from it
	if (isset($_SESSION['lang'])){
		if (isset($available_languages[$_SESSION['lang']])) return $_SESSION['lang'];
		else unset($_SESSION['lang']);
	}

	// Lang is not know yet
	// try to findout user's language by checking user attribute

	if (isset($_SESSION['auth']) and 
	    is_a($_SESSION['auth'], 'Auth') and
	    $_SESSION['auth']->is_authenticated()){

		$uid = $_SESSION['auth']->get_uid();
		$did = $_SESSION['auth']->get_did(); //for checking domain attribute later

		$attrs = &User_Attrs::singleton($uid);
		$lang = lang_detect($attrs->get_attribute($an['lang']), 3);
		if (false != $lang) return $lang;

	}
	

	// try to findout user's language by checking cookie

	if (!empty($_COOKIE['serweb_lang']) and isset($available_languages[$_COOKIE['serweb_lang']])){
		return $_COOKIE['serweb_lang'];
	}

	// try to findout user's language by checking its HTTP_ACCEPT_LANGUAGE variable
	
	if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$accepted    = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$acceptedCnt = count($accepted);
		for ($i = 0; $i < $acceptedCnt; $i++) {
			$lang = lang_detect($accepted[$i], 1);
			if (false != $lang) return $lang;
		}
	}
	
	// try to findout user's language by checking its HTTP_USER_AGENT variable

	if (!empty($_SERVER['HTTP_USER_AGENT'])) {
		$lang = lang_detect($_SERVER['HTTP_USER_AGENT'], 2);
		if (false != $lang) return $lang;
	}

	// try to findout user's language by checking domain or global attribute

	if (empty($config->do_not_set_lang_by_domain)){
		if (is_null($did)){ // if user is not authenticated yet
		                    // get did of domain from http request
			$data->add_method('get_did_by_realm');
			$did = $data->get_did_by_realm($config->domain, null);
			if (false === $did) $did = null;
		}
	}
	else{
		$did = null;
	}

	$o = array();
	if (!is_null($did)) $o['did'] = $did;
	$lang = lang_detect(Attributes::get_attribute($an['lang'], $o), 3);
	if (false != $lang) return $lang;


	if (!is_null($lang) and isset($available_languages[$lang])) return $lang;


	// Didn't catch any valid lang : we use the default settings
	
	return $config->default_lang;

}

/**
 *	Function load additional language file
 *	
 *	This function may be used for example to loading modules purpose 
 *	
 *	@param	string	$ldir	path to directory which is scanned for language files
 *	@return	bool			TRUE on success, FALSE when file is not found
 */
function load_another_lang($ldir){
	global $_SERWEB, $reference_language, $available_languages, $lang_str, $lang_set;

	$ldir = $_SERWEB["langdir"].$ldir."/";

	$primary_lang_file   = $ldir.$available_languages[$_SESSION['lang']][1].".php";
	$secondary_lang_file = $ldir.$available_languages[$reference_language][1].".php";
	
	if (file_exists($primary_lang_file)){
		require_once($primary_lang_file);
	}
	elseif(file_exists($secondary_lang_file)){
		require_once($secondary_lang_file);
	}
	else{
		ErrorHandler::log_errors(PEAR::RaiseError("Can't find requested language file", 
		                         NULL, NULL, NULL, 
								 "Nor requested(".$primary_lang_file.") neither default(".$secondary_lang_file.") language file not exists"));
		
		return false;
	}

	return true;
}

$_SESSION['lang'] = determine_lang();

// store language to $sess_lang variable for backward compatibility
$sess_lang = &$_SESSION['lang']; 

//set cookie containing selected lang
//cookie expires in one year
setcookie('serweb_lang', $_SESSION['lang'], time()+31536000, $config->root_path);



/** load strings of selected language */
require_once($_SERWEB["langdir"].$available_languages[$_SESSION['lang']][1].".php");

/* set value of $lang_set[ldir] by avaiable_languages array */
$lang_set['ldir'] = $available_languages[$_SESSION['lang']][2];

global $data;

if (!empty($config->data_sql->set_charset)){
	$data->set_db_charset($lang_set['charset'], null, $errors);
}

if (!empty($config->data_sql->collation)){
	$data->set_db_collation($config->data_sql->collation, null, $errors);
}

?>
