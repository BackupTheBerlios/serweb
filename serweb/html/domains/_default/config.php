<?
/*
 * $Id: config.php,v 1.10 2005/05/20 10:08:20 kozlik Exp $
 */

		/* ------------------------------------------------------------*/
		/*      basic local configuration options                      */
		/* ------------------------------------------------------------*/
		/* you need to align these values to your local server settings */

		/* serweb will send confirmation emails and SIP IMs -- what sender 
		   address should it claim ?
		   should appear in them ?
		*/
		$this->mail_header_from="registrar@mydomain.org";			
		$this->web_contact="sip:daemon@mydomain.org";

		/* info email address */
		$this->infomail	=	"info@mydomain.org";
		
		/* email address for questions concerning registration */
		$this->regmail	=	"registrar@mydomain.org";

		/* content of html <title> tag */
		$this->html_title="SIP Express Router - web interface";

		/* true if should be displayed heading like 'domain.org user management' or 'domain.org admin interface' at all pages */
		$this->display_page_heading=true;

		/* DOCTYPE of html pages. The default value is 'strict' for XHTML 1.0 Strict. If your prolog.html and epilog.html
			is not coresponding with this, use 'transitional' for HTML 4.0 Transitional or empty string for none DOCTYPE  */		
		$this->html_doctype="strict";

		/* user content of <head> tag. There can be some linked CSS or javascript or <meta> tags
		   for example CSS styles used in prolog.html
		      $this->html_headers[]='<link REL="StyleSheet" HREF="http://www.mydomain.org/styles/my_styles.css" TYPE="text/css">';
		   or some javascript
		      $this->html_headers[]='<script language="JavaScript" src="http://www.mydomain.org/js/main.js"></script>';
		   uncoment following lines if you want add something
		*/	
 
//		$this->html_headers[]='';
//		$this->html_headers[]='';
//		$this->html_headers[]='';

		/* ------------------------------------------------------------*/
		/* Language settings                                           */
		/* ------------------------------------------------------------*/

		/* Default language to use, if not browser-defined or user-defined
		*/
		
		$this->lang['default_lang'] = 'en-utf-8';
		
		/* Force: always use this language - must be defined in
		   lang/config_lang.php

		   $this->lang['lang'] = 'en-iso-8859-1';
		*/
		$this->lang['lang'] = '';

		/* ------------------------------------------------------------*/
		/* aliases generation                                          */
		/* ------------------------------------------------------------*/
		/* Ddon't forget to align your SER routing script to it !      */

		/* If true, serweb will create numeric alias for new subscribers
		*/
		$this->create_numeric_alias_to_new_users = true;

		/* Nummerical aliases can be generated randomly or incrementaly.
		   Values 'rand' or 'inc' on the next line.
	   
		   notice: if xxl module is loaded always 'rand' is used 
		*/

		$this->alias_generation='inc';

		/* initial nummerical alias for new subscriber - only if aliases
		   are generated incrementaly
		*/
		$this->first_alias_number=82000;
		
		/* next lines are only for randomly generated aliases */
		
		/* prefix of generated alias */
		$this->alias_prefix="8";
		/* postfix of generated alias */
		$this->alias_postfix="";
		/* length of random part of alias */
		$this->alias_lenght=5;
		/* how many times will serweb try find unused alias number until error will occured */
		$this->alias_generation_retries=10;		
 
?>
