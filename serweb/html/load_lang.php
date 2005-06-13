<?
/**
 * Functions for corect pick language file and load it
 * 
 * @author    Karel Kozlik
 * @version   $Id: load_lang.php,v 1.6 2005/06/13 13:15:37 kozlik Exp $
 * @package   serweb
 */ 

require_once($_SERWEB["serwebdir"]."../lang/config_lang.php");

/**
 *	Change names of tabs according to $lang_str
 *  @access  private
 */

function internationalize_tabs(){
	global $config, $lang_str;

	foreach ($config->user_tabs as $k=>$v){
		$config->user_tabs[$k]->name = $lang_str[$v->lang_str];
	}

	foreach ($config->admin_tabs as $k=>$v){
		$config->admin_tabs[$k]->name = $lang_str[$v->lang_str];
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
	global $sess_lang;
	
	foreach($available_languages AS $key => $value) {
		// $envType =  1 for the 'HTTP_ACCEPT_LANGUAGE' environment variable,
		//             2 for the 'HTTP_USER_AGENT' one
		if (($envType == 1 && eregi('^(' . $value[0] . ')(;q=[0-9]\\.[0-9])?$', $str))
			|| ($envType == 2 && eregi('(\(|\[|;[[:space:]])(' . $value[0] . ')(;|\]|\))', $str))) {
			$sess_lang = $key;
			break;
		}
	}
} 


// Register session variable 
if (!$sess->is_registered("sess_lang")) $sess->register("sess_lang");

// Lang forced
if (!empty($config->lang['lang'])) {
    $sess_lang = $config->lang['lang'];
}

// If '$sess_lang' is defined, ensure this is a valid translation
if (!empty($sess_lang) && empty($available_languages[$sess_lang])) {
    $sess_lang = '';
}


// Language is not defined yet :
// try to findout user's language by checking its HTTP_ACCEPT_LANGUAGE variable

if (empty($sess_lang) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
	$accepted    = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	$acceptedCnt = count($accepted);
	for ($i = 0; $i < $acceptedCnt && empty($sess_lang); $i++) {
		lang_detect($accepted[$i], 1);
	}
}

// try to findout user's language by checking its HTTP_USER_AGENT variable
if (empty($sess_lang) && !empty($_SERVER['HTTP_USER_AGENT'])) {
	lang_detect($_SERVER['HTTP_USER_AGENT'], 2);
}

// Didn't catch any valid lang : we use the default settings
if (empty($sess_lang)) {
	$sess_lang = $config->lang['default_lang'];
}

/** load strings of selected language */
require_once($_SERWEB["serwebdir"]."../lang/".$available_languages[$sess_lang][1].".php");

internationalize_tabs();

/* set value of $lang_set[ldir] by avaiable_languages array */
$lang_set['ldir'] = $available_languages[$sess_lang][2];

global $data;

if (!empty($config->data_sql->set_charset)){
	$data->set_db_charset($lang_set['charset'], null, $errors);
}

if (!empty($config->data_sql->collation)){
	$data->set_db_collation($config->data_sql->collation, null, $errors);
}

?>
