<?
/*
 * $Id: set_domain.php,v 1.3 2004/08/09 13:04:28 kozlik Exp $
 */ 

/* set domain name */

/* if set_domain.developer is present, require this instead of setting domain by server name */
$set_domain_developer = dirname(__FILE__) . "/set_domain.developer.php";
if (file_exists($set_domain_developer)){
	require_once ($set_domain_developer);
}
else{

/* 	if automatical setting domain by server name form http request doesn't satisfy to you, 
	comment next line, uncomment the next one and replace 'mydomain.org' string.
*/

	#$config->domain = ereg_replace( "(www\.|sip\.)?(.*)", "\\2",  $_SERVER['SERVER_NAME']);
	$config->domain = "earthlink.net";

}
unset($set_domain_developer);

?>
