<?
/*
 * $Id: load_lang.php,v 1.1 2004/08/09 23:04:57 kozlik Exp $
 */ 

require_once($_SERWEB["serwebdir"]."../lang/config_lang.php");

 
if (!$sess->is_registered("sess_lang")) $sess->register("sess_lang");

// Lang forced
if (!empty($config->lang['lang'])) {
    $sess_lang = $config->lang;
}

// If '$sess_lang' is defined, ensure this is a valid translation
if (!empty($sess_lang) && empty($available_languages[$sess_lang])) {
    $sess_lang = '';
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

// Language is not defined yet :
// try to findout user's language by checking its HTTP_ACCEPT_LANGUAGE variable

if (empty($sess_lang) && !empty($HTTP_ACCEPT_LANGUAGE)) {
	$accepted    = explode(',', $HTTP_ACCEPT_LANGUAGE);
	$acceptedCnt = count($accepted);
	for ($i = 0; $i < $acceptedCnt && empty($sess_lang); $i++) {
		lang_detect($accepted[$i], 1);
	}
}

// try to findout user's language by checking its HTTP_USER_AGENT variable
if (empty($sess_lang) && !empty($HTTP_USER_AGENT)) {
	lang_detect($HTTP_USER_AGENT, 2);
}

// Didn't catch any valid lang : we use the default settings
if (empty($sess_lang)) {
	$sess_lang = $config->lang['default_lang'];
}

require_once($_SERWEB["serwebdir"]."../lang/".$available_languages[$sess_lang][1].".php");

?>