<?
/*
 * $Id: config.php,v 1.11 2005/03/03 11:38:21 kozlik Exp $
 */

/*****************************************************************************
 * 	                     DOMAIN INDEPENDING OPTIONS                          *
 *****************************************************************************/

/* There are values common for all domains */

	/* this array contain list of config parameter which can be modified
	   by admins of particular domains */

	$config->domain_depend_config=array("mail_header_from", "web_contact", 
		"html_title", "html_doctype", "html_headers", "display_page_heading", 
		"alias_generation",	"first_alias_number", "alias_prefix", 
		"alias_postfix", "alias_lenght", "alias_generation_retries",
		"infomail", "regmail", "forgot_pass_subj", "mail_forgot_pass", 
		"register_subj", "mail_register", "terms_and_conditions", "lang");

		
	/* ------------------------------------------------------------*/
	/* serweb appearance                                           */
	/* ------------------------------------------------------------*/

	/* use fully qualified name on user login like 'username@domain'
	   instead of 'username' only
	*/

	$config->fully_qualified_name_on_login=false;
	
	/* if previous option is true, you can check if given domain is supported
	   (is in domain table)
	*/
	
	$config->check_supported_domain_on_login=true;
	
	/* when is user firsttime authenticated, create alias to him, and
	   create record in subscriber table
	*/
	
	$config->register_user_after_first_login=false;

	/* the default timezone which is assigned to user on first login - 
	   only if register_user_after_first_login is true
	*/		
	
	$config->default_timezone='America/New_York';

	/* set to true if should be displayed confirmation page on deleting anything.
	   This option is INCOPLETE and need to be supported in templates.
	*/
	$config->require_delete_confirmation_page=false;

	/* Regular expressions for check if phonenumber entered by user is valid
	   (is used only if serweb is workong with phonenumbers instead of sip addresses)
	   The diferent between phonenumber_regex and strict_phonenumber_regex is that 
	   phonenumber_regex can contain chars as '-' '/' ' ' (which will be removed
	   after form submition)
	*/
	
	$config->phonenumber_regex = "\\+?[-/ ()1-9]+";
	$config->strict_phonenumber_regex = "\\+?[1-9]+";
	
	/* which tabs should show in user's profile ? those set to false
	   by default are experimental features which have not been tested
	   yet
	*/

	/* user tabs definitions
		Ctab (enabled, name_of_tab, php_script)
	*/

	$config->user_tabs=array();
	$config->user_tabs[]=new Ctab (true, "tab_my_account", "my_account.php");									// $lang_str['tab_my_account']
	$config->user_tabs[]=new Ctab (true, "tab_phonebook", "phonebook.php");										// $lang_str['tab_phonebook']
	$config->user_tabs[]=new Ctab (true, "tab_missed_calls", "missed_calls.php");								// $lang_str['tab_missed_calls']
	$config->user_tabs[]=new Ctab (true, "tab_accounting", "accounting.php");									// $lang_str['tab_accounting']
	$config->user_tabs[]=new Ctab (true, "tab_send_im", "send_im.php");											// $lang_str['tab_send_im']
	$config->user_tabs[]=new Ctab (false, "tab_notification_subscription", "notification_subscription.php");	// $lang_str['tab_notification_subscription']
	$config->user_tabs[]=new Ctab (true, "tab_message_store", "message_store.php");								// $lang_str['tab_message_store']
	$config->user_tabs[]=new Ctab (false, "tab_voicemail", "voicemail.php");									// $lang_str['tab_voicemail']
	$config->user_tabs[]=new Ctab (true, "tab_user_preferences", "user_preferences.php");						// $lang_str['tab_user_preferences']
	$config->user_tabs[]=new Ctab (false, "tab_speed_dial", "speed_dial.php");									// $lang_str['tab_speed_dial']
	$config->user_tabs[]=new Ctab (false, "tab_caller_screening", "caller_screening.php");						// $lang_str['tab_caller_screening']

	/* admin tabs definitions
		Ctab (enabled, name_of_tab, php_script)
	*/
	$config->admin_tabs=array();
	$config->admin_tabs[]=new Ctab (true, "tab_users", "users.php");											// $lang_str['tab_users']
	$config->admin_tabs[]=new Ctab (true, "tab_admin_privileges", "list_of_admins.php");						// $lang_str['tab_admin_privileges']
	$config->admin_tabs[]=new Ctab (true, "tab_ser_moni", "ser_moni.php");										// $lang_str['tab_ser_moni']
	$config->admin_tabs[]=new Ctab (true, "tab_user_preferences", "user_preferences.php");						// $lang_str['tab_user_preferences']

	$config->num_of_showed_items=20; 	/* num of showed items in the list of users */
	$config->max_showed_rows=50;		/* maximum of showed items in "user find" */

	
	/* experimental/incomplete features turned off: voicemail
	   and set up a jabber account for each new SIP user too
	*/
	$config->show_voice_silo=false; /* show voice messages in silo too */
	$config->enable_dial_voicemail=false;
	$config->setup_jabber_account=false;

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

	/* This option is enabling checkbox 'Allow others to see whether 
	   or not I'm online'
	*/		
	$config->allow_change_status_visibility=false;

	/* Forwarding to voicemail by group membership. If set to false,
	   is forwarding to voicemail done through admin privileges
	*/		
	$config->forwarding_to_voicemail_by_group=false;

	/* This option is enabling usrloc table and form for adding contacts
	   at my account tab
	*/
	$config->enable_usrloc=true;


	/* ------------------------------------------------------------*/
	/* accounting TAB                                              */
	/* ------------------------------------------------------------*/
	
	/* Leave true to display corelated entries from cdr instead of 
	   raw entries from acc table
	*/
	$config->acc_use_cdr_table = false;

	/* display outgoing calls at accounting TAB 
	*/
	$config->acc_display_outgoing_calls = true;

	/* display incoming calls at accounting TAB - working only 
	   if users are indexed by UUID 
	   '$config->users_indexed_by' option in config_data_layer.php
	*/
	$config->acc_display_incoming_calls = false;

	/* display missed calls at accounting TAB - only when 
	   $config->acc_use_cdr_table is false 
	 */
	$config->acc_display_missed_calls = false;

	/* ------------------------------------------------------------*/
	/* Loging                                                      */
	/* ------------------------------------------------------------*/

	/* I think that loging is currently useful only for developers.
	   When you enable loging be sure if you have instaleld PEAR package
	   Log. See http://pear.php.net/manual/en/installation.getting.php 
	   for more information
	*/

	$config->enable_loging = false;
	$config->log_file = "/var/log/serweb";

	/* Log messages up to and including this level. Possible values:
		  PEAR_LOG_EMERG, PEAR_LOG_ALERT, PEAR_LOG_CRIT, PEAR_LOG_ERR, 
		  PEAR_LOG_WARNING, PEAR_LOG_NOTICE, PEAR_LOG_INFO, PEAR_LOG_DEBUG
	   see http://www.indelible.org/pear/Log/guide.php#log-levels for more info
	 */
	$config->log_level = "PEAR_LOG_INFO";
	
	/* If location where error was occured should be returned to user
	   html output, set this to true
	 */
	$config->log_error_return_location_of_error_to_html = false;

	/* ------------------------------------------------------------*/
	/* Speed dial                                                  */
	/* ------------------------------------------------------------*/


	/* validation regex which must much username from request uri 
	   in speed dial
	*/
	$config->speed_dial['validation']="^[0-9][0-9]$";

	/* index into $lang_str array which contains error string used 
	   for failed validations
	*/
	$config->speed_dial['validation_msg']="fe_invalid_speed_dial";

	/* ------------------------------------------------------------*/
	/* ACLs                                                        */
	/* ------------------------------------------------------------*/

	/* there may be SIP contacts which you wish to prevent from being added
	   through serweb to avoid loops, forwarding to unfriendly domains, etc.
	   use these REGexs  to specify which contacts you do not wish;
	   the first value includes banned REs, the second displays error message
	   displayed to users if they attempt to introduce a banned contact
	*/
	$config->denny_reg=array();
	$config->denny_reg[]=new CREG_list_item("iptel\.org$","local forwarding prohibited");
	$config->denny_reg[]=new CREG_list_item("gateway","gateway contacts prohibited");

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
	
	/* ------------------------------------------------------------*/
	/* applications (experimental)                                 */
	/* ------------------------------------------------------------*/

	/* subscribe-notify -- list of events to which a user can subscribe and
	   is then notified with an instant message, if they occur; experimental
	*/
	$config->sub_not=array();
	$config->sub_not[]=new Csub_not("sip:weather@iptel.org".
		";type=temperature;operator=lt;value=0","temperature is too low");
	$config->sub_not[]=new Csub_not("sip:weather@iptel.org".
		";type=wind;operator=gt;value=10","wind is too fast");
	$config->sub_not[]=new Csub_not("sip:weather@iptel.org;".
		"type=pressure;operator=lt;value=1000","pressure is too low");
	$config->sub_not[]=new Csub_not("sip:weather@iptel.org;type=metar",
		"send METAR data");

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
	/*            configure server monitoring					   */
	/* ------------------------------------------------------------*/

	/* if you change this values, please delete all data from table	
	   "table_ser_mon_agg" and "table_ser_mon" by reason that the 
		aggregated data may be calculated bad if you don't do it
	*/

	/* length of marginal period in seconds */
	$config->ser_moni_marginal_period_length=60*5;   //5 minutes
	
	/* length of interval (in seconds) for which will data stored, 
	   data older then this interval will be deleted
	*/
	$config->ser_moni_aggregation_interval=60*15;	//15 minut

	/* ------------------------------------------------------------*/
	/*            click to dial                                    */
	/* ------------------------------------------------------------*/

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
	/*            caller screening                                 */
	/* ------------------------------------------------------------*/

	/*
		this array describe how to dispose of draggers
		$config->calls_forwarding["screening"][]=new Ccall_fw(<action>, <param1>, <param2>, <label>)

		<action> is "reply" or "relay"
		"reply" have parameters status code and phrase (e.g. ("486", "busy") or ("603", "decline"))
		"relay" have only one parameter - address of server where to request forward
		<label> is string which is displayed to user
	*/
	$config->calls_forwarding=array();
	$config->calls_forwarding["screening"][]=new Ccall_fw("reply", "603", "decline", "decline");
	$config->calls_forwarding["screening"][]=new Ccall_fw("reply", "486", "busy", "reply you are busy");
	$config->calls_forwarding["screening"][]=new Ccall_fw("relay", "sip:voicemail@".$config->domain, null, "forward to voicemail");

	/* ------------------------------------------------------------*/
	/* Values you typically do NOT want to change unless you know  *
    /* well what you are doing                                     *
	/* ------------------------------------------------------------*/


	/* these are table names as reffered from script and via FIFO */
	$config->ul_table="location";
	$config->fifo_aliases_table="aliases";


	/* development value
	$config->reply_fifo_path="d:/temp/".$config->reply_fifo_filename; */	

	/* serweb version */
	$config->psignature="Web_interface_Karel_Kozlik-0.9";

	/* IM paging configuration */
	$config->im_length=1300;

	/* expiration times, priorities, etc. for usrloc/alias contacts */
	$config->new_alias_expires='567648000';
	$config->new_alias_q=1.00;
	$config->new_alias_callid="web_call_id@fox";
	$config->new_alias_cseq=1;
	$config->ul_priority="1.00";
	/* replication support ? (a new ser feature) */
	$config->ul_replication=1;
	/* flags support ? (a new ser feature) */
	$config->ul_flags=1;

	/* seconds in which expires "get pass session" */
	$config->pre_uid_expires=3600;                
	/* is the sql database query for user authentication formed
	   with clear text password or a hashed one; the former is less
	   secure the latter works even if password hash is incorrect,
	   which sometimes happens, when it is calculated from an
	   incorrect domain during installation process
	*/
	$config->clear_text_pw=1;

	/* ------------------------------------------------------------*/
	/*            send daily missed calls by email                 */
	/* ------------------------------------------------------------*/
	
	/*
		name of attribute in user preferences for daily sending missed  
		calls to email, it's type should be boolean
	*/
	$config->up_send_daily_missed_calls="send_daily_missed_calls";
	
	/*
		subject and body of daily sended email with missed calls
	*/
	$config->send_daily_missed_calls_mail_subj="your missed calls";
	$config->send_daily_missed_calls_mail_body=" Hello, \n".
			"we are sending your missed calls";




	/* $config->realm, $config->domainname and $config->default_domain will be substituted by $config->domain */
	$config->realm=$config->domainname=$config->default_domain=$config->domain;

				
?>
