<?
/*
 * $Id: set_domain.php,v 1.1 2004/03/24 21:39:46 kozlik Exp $
 */ 

/* set domain name */
$config->domain = ereg_replace( "(www\.|sip\.)?(.*)", "\\2",  $_SERVER['SERVER_NAME']);

?>