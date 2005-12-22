<?
/*
 * $Id: config_domain_defaults.php,v 1.8 2005/12/22 13:55:15 kozlik Exp $
 */

/*****************************************************************************
 * 	                      DOMAIN DEPENDING OPTIONS                           *
 *****************************************************************************
 * This file contains default values for domain depending config options.    *
 * Changes in this file have effect only if corresponding option in config   *
 * file for some domain is missing. Please DON'T CHANGE THIS FILE until you  *
 * don't know what are you doing. Instead of changing this file do changes   *
 * in files /html/domains/__name_of_domain__/config.ini.php                  *
 *****************************************************************************/
 
	/* ------------------------------------------------------------*/
	/*      basic local configuration options                      */
	/* ------------------------------------------------------------*/
	/* you need to align these values to your local server settings */

	/* serweb will send confirmation emails and SIP IMs -- what sender
	   address should it claim ?
	   should appear in them ?
	*/
	$config->mail_header_from="registrar@mydomain.org";
	$config->web_contact="sip:daemon@mydomain.org";

	/* info email address */
	$config->infomail	=	"info@mydomain.org";

	/* email address for questions concerning registration */
	$config->regmail	=	"registrar@mydomain.org";

	
	/* content of html <title> tag */
	$config->html_title="SIP Express Router - web interface";

	/* true if should be displayed heading like 'domain.org user management' or 'domain.org admin interface' at all pages */
	$config->display_page_heading=true;

	/* user content of <head> tag. There can be some linked CSS or javascript or <meta> tags
	   for example CSS styles used in prolog.html
	      $this->html_headers[]='<link REL="StyleSheet" HREF="http://www.mydomain.org/styles/my_styles.css" TYPE="text/css">';
	   or some javascript
	      $this->html_headers[]='<script language="JavaScript" src="http://www.mydomain.org/js/main.js"></script>';
	   uncoment following lines if you want add something
	*/	
	$config->html_headers=array();
//	$config->html_headers[]="";
//	$config->html_headers[]="";
//	$config->html_headers[]="";

	/* DOCTYPE of html pages. The default value is 'strict' for XHTML 1.0 Strict. If your prolog.html and epilog.html
		is not coresponding with this, use 'transitional' for HTML 4.0 Transitional or empty string for none DOCTYPE  */		
	$config->html_doctype="strict";


	/* ------------------------------------------------------------*/
	/* aliases generation                                          */
	/* ------------------------------------------------------------*/
	/* Ddon't forget to align your SER routing script to it !      */

	/* If true, serweb will create numeric alias for new subscribers
	*/
	$config->create_numeric_alias_to_new_users = true;

	/* Nummerical aliases can be generated randomly or incrementaly.
	   Values 'rand' or 'inc' on the next line.
	   
	   notice: if xxl module is loaded always 'rand' is used 
	*/

	$config->alias_generation='inc';

	/* initial nummerical alias for new subscriber - only if aliases
	   are generated incrementaly
	*/
	$config->first_alias_number=82000;
	
	/* next lines are only for randomly generated aliases */
	
	/* prefix of generated alias */
	$config->alias_prefix="8";
	/* postfix of generated alias */
	$config->alias_postfix="";
	/* length of random part of alias */
	$config->alias_lenght=5;
	/* how many times will serweb try find unused alias number until error will occured */
	$config->alias_generation_retries=10;		

				
?>
