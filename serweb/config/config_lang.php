<?
/*
 * $Id: config_lang.php,v 1.3 2006/07/11 12:13:01 kozlik Exp $
 */

/**
 * All the supported languages have to be listed in the array below.
 * 1. The key must be the "official" ISO 639 language code and, if required,
 *    the dialect code. It can also contain some informations about the
 *    charset (see the Russian case).
 * 2. The first of the values associated to the key is used in a regular
 *    expression to find some keywords corresponding to the language inside two
 *    environment variables.
 *    These values contains:
 *    - the "official" ISO language code and, if required, the dialect code
 *      also ('bu' for Bulgarian, 'fr([-_][[:alpha:]]{2})?' for all French
 *      dialects, 'zh[-_]tw' for Chinese traditional...);
 *    - the '|' character (it means 'OR');
 *    - the full language name.
 * 3. The second values associated to the key is the name of the file to load
 *    without the '.php' extension.
 * 4. The last values associated to the key is the language code as defined by
 *    the RFC1766.
 *
 * Beware that the sorting order (first values associated to keys by
 * alphabetical reverse order in the array) is important: 'zh-tw' (chinese
 * traditional) must be detected before 'zh' (chinese simplified) for
 * example.
 *
 * When there are more than one charset for a language, we put the -utf-8
 * first.
 *
 * For Russian, we put 1251 first, because MSIE does not accept 866
 * and users would not see anything.
 */

global $available_languages;

$available_languages = array(
    'cs-utf-8'     => array('cs|czech',                         'czech-utf-8', 'cs'),
    'cs-iso-8859-2'=> array('cs|czech',                         'czech-iso-8859-2', 'cs'),
    'cs-win1250'   => array('cs|czech',                         'czech-windows-1250', 'cs'),
    'en-utf-8'     => array('en([-_][[:alpha:]]{2})?|english',  'english-utf-8', 'en'),
    'en-iso-8859-1'=> array('en([-_][[:alpha:]]{2})?|english',  'english-iso-8859-1', 'en'),
    'nl-utf-8'     => array('nl([-_][[:alpha:]]{2})?|dutch',    'dutch-utf-8', 'nl'),
    'nl-iso-8859-1'=> array('nl([-_][[:alpha:]]{2})?|dutch',    'dutch-iso-8859-1', 'nl'), 
//    'de-iso-8859-1'=> array('de([-_][[:alpha:]]{2})?|german', 'german-iso-8859-1', 'de')
);

$reference_language = 'en-utf-8';
?>
