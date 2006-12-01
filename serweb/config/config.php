<?
/*
 * $Id: config.php,v 1.48 2006/12/01 16:41:20 kozlik Exp $
 */

/*****************************************************************************
 * 	                     DOMAIN INDEPENDING OPTIONS                          *
 *****************************************************************************/

	/* this array contain list of config parameter which can be modified
	   by admins of particular domains */

	$config->domain_depend_config=array("web_contact", 
		"html_title", "html_doctype", "html_headers", "display_page_heading", 
		"alias_generation",	"first_alias_number", "alias_prefix", 
		"alias_postfix", "alias_lenght", "alias_generation_retries");


	/* This setting enables testing facility which may help you witch configuration
	   of serweb and PHP interpreter. It is recommended to disable it after you finish 
	   configuration.
	*/
	$config->testing_facility = true;

	/* ------------------------------------------------------------*/
	/* serweb modules                                              */
	/* ------------------------------------------------------------*/

	/* modules which are loaded on every page
	*/
	
	$config->modules = array();
	$config->modules['auth'] = true;
	$config->modules['attributes'] = true;
		
	/* ------------------------------------------------------------*/
	/* Language settings                                           */
	/* ------------------------------------------------------------*/

	/* Default language to use, if not browser-defined or user-defined
	*/
	
	$config->default_lang = 'en-utf-8';
	
	/* Force: always use this language - must be defined in
	   config/config_lang.php

	   $config->force_lang = 'en-iso-8859-1';
	*/
	$config->force_lang = '';

	/* ------------------------------------------------------------*/
	/* serweb appearance                                           */
	/* ------------------------------------------------------------*/

	/* use fully qualified name on user login like 'username@domain'
	   instead of 'username' only
	*/

	$config->fully_qualified_name_on_login=true;

	/* Use serweb in multidomain setup 
	 * Serweb can work in two diferent modes. 
	 * - singledomain - Use if your SER control only one domain.
	 *     In this case the name of the apache host under which the serweb is 
	 *     running is always used as domain. If the serweb is running on a 
	 *     virtual host, this will be the value defined for that virtual host.
	 *     The name of domain may be a bit changed (for example initial 'sip.'
	 *     may be stripped). See file set_domain.php in config directory.
	 *     
	 * - multidomain - Use if your SER control more domains.
	 *     In this case the domain is checked against the 'domain' sql table.
	 *     Also tabs for manage domains are enabled in admin interface.
	 */
	$config->multidomain = true;
	
	/* Default id of domains in single domain setups 
	 * Probably you does not need change this value.
	 */
 	$config->default_did = "_default";
  	
 	/* DID used for global tel uri */
 	$config->global_tel_uri_did = $config->default_did; 


	/* Serweb sends email messages. This is email address which appear in 'from'
	   header if the address is not specified in domain attributes. 
	 */
	$config->mail_header_from="info@mydomain.org";
	

	/* set to true if should be displayed confirmation page on deleting anything.
	   This option is INCOPLETE and need to be supported in templates.
	*/
	$config->require_delete_confirmation_page=false;


	/* Regular expressions for check if username (username part of sip address) 
	   entered by user is valid.

	   By default username may be either a numerical address starting with 
	   '8' (e.g., '8910') or a lower-case alphanumerical address starting 
	   with an alphabetical character (e.g., john.doe01).

	   $config->username_regex = '^((8[0-9]*)|([a-zA-Z][a-zA-Z0-9.]*))$';
	*/
	
	$config->username_regex = '^((8[0-9]*)|([a-zA-Z][a-zA-Z0-9.]*))$';

	/* Regular expressions for check if phonenumber entered by user is valid
	   (is used only if serweb is workong with phonenumbers instead of sip addresses)
	   The diferent between phonenumber_regex and strict_phonenumber_regex is that 
	   phonenumber_regex can contain chars as '-' '/' ' ' (which will be removed
	   after form submition)
	*/
	
 	$config->phonenumber_regex = "\\+?[-/ ()0-9]+";
 	$config->strict_phonenumber_regex = "\\+?[0-9]+"; 
	
	/* which tabs should show in user's profile ? those set to false
	   by default are experimental features which have not been tested
	   yet
	*/

	/* user tabs definitions
		Ctab (enabled, name_of_tab, php_script)
		
		Tab Send IM is temporary disabled because sending instant messages 
		with xml-rpc is not curently possible
	*/

	$config->user_tabs=array();
	$config->user_tabs[]=new Ctab (true, "@tab_my_account", "my_account.php");					// $lang_str['tab_my_account']
	$config->user_tabs[]=new Ctab (true, "@tab_phonebook", "phonebook.php");						// $lang_str['tab_phonebook']
	$config->user_tabs[]=new Ctab (true, "@tab_missed_calls", "missed_calls.php");				// $lang_str['tab_missed_calls']
	$config->user_tabs[]=new Ctab (true, "@tab_accounting", "accounting.php");					// $lang_str['tab_accounting']
	$config->user_tabs[]=new Ctab (false, "@tab_send_im", "send_im.php");							// $lang_str['tab_send_im']
	$config->user_tabs[]=new Ctab (true, "@tab_message_store", "message_store.php");				// $lang_str['tab_message_store']
	$config->user_tabs[]=new Ctab (false, "@tab_voicemail", "voicemail.php");					// $lang_str['tab_voicemail']
	$config->user_tabs[]=new Ctab (false, "@tab_user_preferences", "user_preferences.php");		// $lang_str['tab_user_preferences']
	$config->user_tabs[]=new Ctab (true, "@tab_speed_dial", "speed_dial.php");					// $lang_str['tab_speed_dial']

	/* admin tabs definitions
		Ctab (enabled, name_of_tab, php_script)
		
		The $config->multidomain has to be true to display tabs 
		'list_of_domains' and 'customers'. Also some other tabs are not 
		displayed if logged user doesn't have the hostmaster privilege.
	*/
	$config->admin_tabs=array();
	$config->admin_tabs[]=new Ctab (true, "@tab_users", "users.php");							// $lang_str['tab_users']
	$config->admin_tabs[]=new Ctab (true, "@tab_admin_privileges", "list_of_admins.php");		// $lang_str['tab_admin_privileges']
	$config->admin_tabs[]=new Ctab (true, "@tab_domains", "list_of_domains.php");				// $lang_str['tab_domains']
	$config->admin_tabs[]=new Ctab (true, "@tab_customers", "customers.php");					// $lang_str['tab_customers']
	$config->admin_tabs[]=new Ctab (true, "@tab_global_attributes", "global_attributes.php");	// $lang_str['tab_global_attributes']
	$config->admin_tabs[]=new Ctab (true, "@tab_attr_types", "attr_types.php");	// $lang_str['tab_global_attributes']

	/* this is list of tabs which are accessible only for hostmaster
	 */
	$config->hostmaster_only_tabs=array();
	$config->hostmaster_only_tabs[] = "list_of_admins.php";
	$config->hostmaster_only_tabs[] = "global_attributes.php";
	$config->hostmaster_only_tabs[] = "attr_types.php";


	$config->num_of_showed_items=20; 	/* num of showed items in the list of users */
	$config->max_showed_rows=50;		/* maximum of showed items in "user find" */

	
	/* experimental/incomplete features turned off: voicemail
	   and set up a jabber account for each new SIP user too
	*/
// currently disabled in serweb:	$config->show_voice_silo=false; /* show voice messages in silo too */
	$config->enable_dial_voicemail=false;
	$config->setup_jabber_account=false;


	/* allow change language of serweb on login page
	   this require enable more lanaguages in config_lang.php and do 
	   the translations
	*/
	$config->allow_change_language_on_login = true;


	$config->jserver = "localhost";   		# Jabber server hostname
	$config->jport = "5222";     			# Jabber server port
	$config->jcid  = 0;      				# Jabber communication ID

	# Jabber module database
	$config->jab_db_type="mysql";           # type of db host, enter "mysql" for MySQL or "pgsql" for PostgreSQL
	$config->jab_db_srv="localhost";        # database server
	$config->jab_db_port="";                # database port - leave empty for default
	$config->jab_db_usr="ser";              # database user
	$config->jab_db_pas="heslo";            # database user's password
	$config->jab_db_db="sip_jab";           # database name


	/* ------------------------------------------------------------*/
	/* My account TAB                                              */
	/* ------------------------------------------------------------*/
	
	$config->allow_change_email=true;
	$config->allow_change_password=true;
	$config->allow_change_usrloc=false;


	/* ------------------------------------------------------------*/
	/* Phonebook TAB                                              */
	/* ------------------------------------------------------------*/

	/* Enable whitepages - searching for other subscribers.
	   
	   Please note that whitepages script require quite complex SQL query.
	   So it may be slow in high loaded environment and may be better disable
	   it.
	 */
	$config->enable_whitepages = true;
	

	/* ------------------------------------------------------------*/
	/* accounting TAB                                              */
	/* ------------------------------------------------------------*/
	
	/* Leave true to display corelated entries from cdr instead of 
	   raw entries from acc table
	*/
	$config->acc_use_cdr_table = false;


	/* ------------------------------------------------------------*/
	/* Logging                                                      */
	/* ------------------------------------------------------------*/

	/* When you enable logging be sure if you have instaleld PEAR package
	   Log. See http://pear.php.net/manual/en/installation.getting.php 
	   for more information
	*/

	$config->enable_logging = false;
	$config->log_file = "/var/log/serweb";

	/* Log messages up to and including this level. Possible values:
		  PEAR_LOG_EMERG, PEAR_LOG_ALERT, PEAR_LOG_CRIT, PEAR_LOG_ERR, 
		  PEAR_LOG_WARNING, PEAR_LOG_NOTICE, PEAR_LOG_INFO, PEAR_LOG_DEBUG
	   see http://www.indelible.org/pear/Log/guide.php#log-levels for more info
	 */
	$config->log_level = "PEAR_LOG_WARNING";
	
	/* If location where error was occured should be returned to user
	   html output, set this to true
	 */
	$config->log_error_return_location_of_error_to_html = false;


	/* ------------------------------------------------------------*/
	/* Usrloc                                                      */
	/* ------------------------------------------------------------*/

	/* there may be SIP contacts which you wish to prevent from being added
	   through serweb to avoid loops, forwarding to unfriendly domains, etc.
	   use these REGexs  to specify which contacts you do not wish;
	   the first value includes banned REs, the second displays error message
	   displayed to users if they attempt to introduce a banned contact
	   Second parameter is index into $lang_str array (see files in lang directory)
	*/
	$config->denny_reg=array();
	$config->denny_reg[]=new CREG_list_item("iptel\.org$", "acl_err_local_forward");
	$config->denny_reg[]=new CREG_list_item("gateway", "acl_err_gateway_forward");

	/* ------------------------------------------------------------*/
	/* ACLs                                                        */
	/* ------------------------------------------------------------*/

	/* SER configuration script may check for group membership of users
	   identified using digest authentication; e.g., it may only allow
	   international calls to callers who are members of 'int' group;
	   this is a list of groups that serweb allows to set -- they need to
	   correspond to names of groups used in SER's membership checks
	*/
	$config->grp_values=array();
	$config->grp_values[]="ld";
	$config->grp_values[]="local";
	$config->grp_values[]="int";



	/* =========================================================== */
    /* ADVANCED SETTINGS                                           */
	/* =========================================================== */

	/* Maximum allowed idle time before the authentication expires. 
	   If set to 0, The authentication never expires 
	 */
	$config->auth_lifetime = 20;
	
	/* List of domain names which are prohibited 
	 */
	$config->prohibited_domains = array('anonymous.invalid');

	/* ------------------------------------------------------------*/
	/* applications (experimental)                                 */
	/* ------------------------------------------------------------*/

	/* metar wheather application */
	//this is an identificator in event table for sending METAR data
	$config->metar_event_uri="sip:weather@iptel.org;type=metar";
	//from header in sip message
	$config->metar_from_sip_uri="sip:daemon@iptel.org";
	// N/A message - is sended to user when we can't get his location or METAR data
	$config->metar_na_message="sorry we can't get your location or METAR data for you";


	/* ------------------------------------------------------------*/
	/*            configure FW/NAT detection applet                */
	/* ------------------------------------------------------------*/

	/* the applet is used to detect whether user is behind firewall or NAT 
	   to enable FW/NAT detection must be installed STUN server */

	// show test firewall/NAT button at my account tab
	$config->enable_test_firewall=false;

	//width of NAT detection applet
	$config->stun_applet_width=350;				
	//height of NAT detection applet
	$config->stun_applet_height=100;				
	//starting class of NAT detection applet
	$config->stun_class="STUNClientApplet.class"; 
	//jar archive with NAT detection applet - optional - you can comment 
	// it if you don't use jar archive
    $config->stun_archive="STUNClientApplet.jar";             

	/* applet parameters: */

	/* STUN server address - must be same as web server address because 
	   the java security manager allows only this one
	*/
	$config->stun_applet_param=array();
	$config->stun_applet_param[]=new Capplet_params("server", "www.iptel.org");

	/* STUN server port. The Default value is 1221 - optional - you can comment 
		it if you want use default value
	*/
	$config->stun_applet_param[]=new Capplet_params("port", 1221);
	/* destination port for the first probing attempt -- just set up a simple
	   tcp echo server there; we use the first TCP connection to determine
	   local IP address, which can't be learned from systems setting due to
       security manager ; default is 5060
	*/
	$config->stun_applet_param[]=new Capplet_params("tcp_dummyport", 5061);

	/* Number of times to resend a STUN message to a STUN server. The 
		Default is 9 times - optional - you can comment it if you want 
		use default value
	*/
	// $config->stun_applet_param[]=new Capplet_params("retransmit", 9);

	/* Specify source port of UDP packet to be sent from. The Default value 
	   is 5000 - optional - you can comment it if you want use default value
	*/
	// $config->stun_applet_param[]=new Capplet_params("sourceport", 5000);



	/* ------------------------------------------------------------*/
	/*            click to dial                                    */
	/* ------------------------------------------------------------*/

	/* enable click to dial */
	/* temporary disabled because CTD not working with xml-rpc */
	$config->enable_ctd = false;

	/* address of the final destination to which we want to transfer
	   initial CSeq and CallId */
	$config->ctd_target="sip:23@192.168.2.16";

	/* address of user wishing to initiate conversation */
	$config->ctd_uri="sip:44@192.168.2.16";
	
	/* from header for click-to-dial request */
	$config->ctd_from	=	"sip:controller@mydomain.org";
	
	/* sip address of outbound proxy - leave empty for no proxy*/
	$config->ctd_outbound_proxy	=	"sip:proxy.domain.org:5060";

	/* header field 'secret' for CTD request - leave empty for no use this field
	   
	   in SER script should be something like this:

			if (search("Secret: heslo")) { 
			    remove_hf("Secret");
			    setflag(xy)l
			};

		and in authentication block:
		
			if is)flagset(xz) breakl;		   
	   
	 */
	$config->ctd_secret	=	"heslo";
	

	/* ------------------------------------------------------------*/
	/* Values you typically do NOT want to change unless you know  *
    /* well what you are doing                                     *
	/* ------------------------------------------------------------*/


	/* these are table names as reffered from script and via FIFO */
	$config->ul_table="location";

	/* serweb version */
	$config->psignature="Web_interface_Karel_Kozlik-0.9";

	/* IM paging configuration */
	$config->im_length=1300;

	/* priority for usrloc contacts */
	$config->ul_priority="1.00";

	/* is the sql database query for user authentication formed
	   with clear text password or a hashed one; the former is less
	   secure the latter works even if password hash is incorrect,
	   which sometimes happens, when it is calculated from an
	   incorrect domain during installation process
	*/
	$config->clear_text_pw=true;
 				
?>
