<?
/*
 * $Id: config.php,v 1.29 2003/11/26 23:18:54 kozlik Exp $
 */

class Csub_not {
	var $uri, $desc;
	function Csub_not($uri, $desc){
		$this->uri=$uri;
		$this->desc=$desc;
	}
}

class CREG_list_item {
	var $reg, $label;
	function CREG_list_item($reg, $label){
		$this->reg=$reg;
		$this->label=$label;
	}
}

class Capplet_params {
	var $name, $value;
	function Capplet_params($name, $value){
		$this->name=$name;
		$this->value=$value;
	}
}

class Ctab{
	var $name, $page, $enabled;
	function Ctab($enabled, $name, $page){
		$this->name=$name;
		$this->page=$page;
		$this->enabled=$enabled;
	}
}

class Cconfig {
	var $db_host;
	var $db_name;
	var $db_user;
	var $db_pass;

	var $table_subscriber;
	var $table_pending;
	var $table_grp;
	var $table_aliases;
	var $table_location;
	var $table_missed_calls;
	var $table_accounting;
	var $table_phonebook;
	var $table_event;
	var $table_netgeo_cache;
	var $table_ser_mon;
	var $table_ser_mon_agg;
	var $table_message_silo;
	var $table_voice_silo;

	var $voice_silo_dir;
	var $greetings_spool_dir;

	var $show_voicemail_acl;
	var $show_voice_silo;

	var $enable_dial_voicemail;
	var $setup_jabber_account;

	var $enable_test_firewall;
	var $stun_applet_width;
	var $stun_applet_height;
	var $stun_class;
	var $stun_archive;
	var $stun_server;
	var $stun_port;
	var $stun_retransmit;
	var $stun_sourceport;

	var $grp_values;

	var $realm;
	var $domainname;

	var $first_alias_number;

	var $new_alias_expires;
	var $new_alias_q;
	var $new_alias_callid;
	var $new_alias_cseq;

	var $pre_uid_expires;

	var $psignature;

	var $web_contact;
	var $fifo_server;
	var $reply_fifo_filename;
	var $reply_fifo_path;

	var $fifo_aliases_table;
	var $ul_table;
	var $ul_priority;
	var $ul_replication;

	var $im_length;
	var $default_domain;

	var $root_path;
	var $root_uri;
	var $img_src_path;
	var $js_src_path;
	var $style_src_path;
	var $zonetab_file;

	var $charset;

	var $default_width;

	var $num_of_showed_items;
	var $max_showed_rows;

	var $enable_tabs;

	var $sub_not;

	var $mail_header_from;

	var $mail_forgot_pass;
	var $forgot_pass_subj;
	var $mail_register;
	var $register_subj;

	// web pages which should be virtually included  in beginning and
	// end of every serweb page
	var $prolog;
	var $separator;
	var $epilog;

	var $terms_and_conditions;

	var $metar_event_uri;
	var $metar_from_sip_uri;
	var $metar_na_message;
	
	var $ser_moni_marginal_period_length;
	var $ser_moni_aggregation_interval;
	var $clear_text_pw;
	var $ul_multidomain;

	var $ctd_target;
	var $ctd_uri;

	function Cconfig(){
		////////////////////////////////////////////////////////////////
		//            configure database

		/* these are the defaults with which SER installs; if you changed
		   the SER account for MySQl, you need to update here 
		*/

		$this->db_host="192.168.2.16";		//database host
		$this->db_name="ser";			//database name
		$this->db_user="ser";			//database conection user
		$this->db_pass="heslo";			//database conection password


		/* ------------------------------------------------------------*/
		/*      basic local configuration options                      */
		/* ------------------------------------------------------------*/
		/* you need to align these values to your local server settings */

		/* the web path bellow which serweb's "admin" and "user_interface" 
		   directories begin to spread; If set up to be in root (http://www/admin ...),
           set just "/" here. Set a specific path otherwise, e.g., "/iptel/html/". 
		   Don't forget trailing slash.  
			Hint: if path incorrect, image buttons do not show up
		*/
		$this->root_path="/iptel/html/";
		/* roo uri of your server */
		$this->root_uri="http://192.168.2.16";
		/* where is your zone file on your server ? */
		$this->zonetab_file =   "/usr/share/zoneinfo/zone.tab";
		/* serweb will send confirmation emails and SIP IMs -- what sender 
		   address should it claim ?
		   should appear in them ?
		*/
		$this->mail_header_from="registrar@mydomain.org";			
		$this->web_contact="sip:daemon@192.168.2.16";
		/* spool directory with voicemail messages */
		$this->voice_silo_dir = '/var/spool/voicemail/'; 
		/* directory with voicemail greetings */
		$this->greetings_spool_dir = '/var/greetings/';
		/* serweb talks to SER via FIFO -- this is FIFO's name,
		   it must have the same value as SER's fifo config param
		*/
		$this->fifo_server="/tmp/ser_fifo";
		/* these are absolute web paths to HTML documents surrounding
		   serweb pages -- these may typically include banner, trailers,
		   and whatever else appropriate to your web design; make sure
		   the values point to existing files; the files should include
		   at least:
           prolog: <body> or <body><h1>, etc.
		   separator: may be empty, or </h1><hr> etc.
           epilog: </body>
		*/
		$this->prolog="/prolog.html";
		$this->separator="/separator.html";
		$this->epilog="/epilog.html";

		/* content of html <title> tag */
		$this->title="iptel.org, the IP Telephony Site";

		/* your domain name */
		$this->realm=$this->domainname=$this->default_domain=
			ereg_replace( "(www\.|sip\.)?(.*)", "\\2",  $_SERVER['SERVER_NAME']);
		/* initial nummerical alias for new subscriber -- don't forget to
		   align your SER routing script to it !
		*/
		$this->first_alias_number=82000;


		/* info email address */
		$this->infomail	=	"info@iptel.org";
		/* email address for questions concerning registration */
		$this->regmail	=	"registrar@iptel.org";

		/* alternate development settings ...
		$this->zonetab_file =	"d:/data/http/iptel/_data/zone.tab";		
		$this->root_path="/~iptel/";
		$this->mail_header_from="php.kk@kufr.cz";			
		$this->voice_silo_dir = 'c:/temp/'; 
		$this->greetings_spool_dir = 'c:/temp/';
		$this->fifo_server="d:/temp/tmp";					//path to fifo server
		*/


		/* ------------------------------------------------------------*/
		/* serweb appearance                                           */
		/* ------------------------------------------------------------*/

		/* which tabs should show in user's profile ? those set to false
		   by default are experimental features which have not been tested
		   yet
		*/
			
		$this->enable_tabs[1]=true;					//enable tab my account
		$this->enable_tabs[2]=true;					//enable tab phonebook
		$this->enable_tabs[3]=true;				//enable tab missed calls
		$this->enable_tabs[4]=true;				//enable tab accounting
		$this->enable_tabs[5]=true;					//enable tab send IM
		$this->enable_tabs[6]=false;				//enable tab notification subscription
		$this->enable_tabs[7]=true;				//enable tab message store

		/* admin tabs definitions
			Ctab (enabled, name_of_tab, php_script)
		*/
		$this->admin_tabs[]=new Ctab (true, "users", "users.php");
		$this->admin_tabs[]=new Ctab (true, "server monitoring", "ser_moni.php");

		$this->default_width=564;					//width of usable area

		$this->num_of_showed_items=20; 	/* num of showed items in the list of users */
		$this->max_showed_rows=50;		/* maximum of showed items in "user find" */

		/* show test firewall/NAT button (see also advanced FW/NAT settings bellow */
		$this->enable_test_firewall=true;           

		/* experimental/incomplete features turned off: voicemail
		   and set up a jabber account for each new SIP user too
		*/
		$this->show_voicemail_acl=true; /* show voicemail in ACL list */
		$this->show_voice_silo=false; /* show voice messages in silo too */
		$this->enable_dial_voicemail=false;
		$this->enable_tabs[8]=true;				//enable tab voicemail
		$this->setup_jabber_account=false;

		/* ------------------------------------------------------------*/
		/* ACLs                                                        */
		/* ------------------------------------------------------------*/

		/* there may be SIP contacts which you wish to prevent from being added
		   through serweb to avoid loops, forwarding to unfriendly domains, etc.
		   use these REGexs  to specify which contacts you do not wish;
		   the first value includes banned REs, the second displays error message
		   displayed to users if they attempt to introduce a banned contact
		*/
		$this->denny_reg[]=new CREG_list_item("iptel\.org$","local forwarding prohibited");
		$this->denny_reg[]=new CREG_list_item("gateway","gateway contacts prohibited");

		/* SER configuration script may check for group membership of users 
		   identified using digest authentication; e.g., it may only allow
		   international calls to callers who are members of 'int' group;
		   this is a list of groups that serweb allows to set -- they need to
		   correspond to names of groups used in SER's membership checks
		*/
		$this->grp_values[]="voicemail";
		$this->grp_values[]="ld";
		$this->grp_values[]="local";
		$this->grp_values[]="int";



		/* ------------------------------------------------------------*/
		/* text														   */
		/* ------------------------------------------------------------*/
		/* human-readable text containing messages displayed to users
		   in web or sent by email; you may need to hire a lawyer ,
		   a word-smith, a diplomat or a translator to get it right :)
		*/


		/* text of password-reminder email */
		$this->forgot_pass_subj="your login information";
		$this->mail_forgot_pass="Hello,\n".
			"now you can access to your account at the folowing URL within 1 hour:\n".
			$this->root_uri.$this->root_path."user/my_account.php?#session#\n\n".
			"We recommend change your password after you login\n\n";

		/* text of confirmation email sent during account registration  */
		$this->register_subj="Your ".$this->domainname." Registration";
		$this->mail_register=
			"Thank you for registering with ".$this->domainname.".\n\n".
			"We are reserving the following SIP address for you: #sip_address#\n\n".
			"To finalize your registration please check the following URL within ".
			"24 hours:\n".
			$this->root_uri.$this->root_path."user/reg/confirmation.php?nr=#confirm#\n\n".
			"(If you confirm later you will have to re-register.)\n\n".
			"Windows Messenger users may look at additional configuration hints at\n".
			"http://www.iptel.org/phpBB/viewtopic.php?topic=11&forum=1&0\n";

		/* terms and conditions as they appear on the subscription webpage */
		$this->terms_and_conditions=
			"BY PRESSING THE 'I ACCEPT' BUTTON, YOU (HEREINAFTER THE 'USER') ARE ".
			"STATING THAT YOU AGREE TO ACCEPT AND BE BOUND BY ALL OF THE TERMS AND ".
			"CONDITIONS OF THIS AGREEMENT.  DO NOT PROCEED IF YOU ARE UNABLE TO AGREE".
			" TO THE TERMS AND CONDITIONS OF THIS AGREEMENT. THESE TERMS AND CONDITIONS ".
			"OF SERVICE FOR USE OF ".$this->domainname." SIP SERVER (THE 'AGREEMENT')".
			" CONSTITUTE A LEGALLY BINDING CONTRACT BETWEEN ".$this->domainname.
			" AND THE ENTITY THAT AGREES TO AND ACCEPTS THESE TERMS AND CONDITIONS. ".
			"ACCESS TO ".$this->domainname."'s SESSION INITIATION PROTOCOL SERVER ".
			"('SIP SERVER') IS BEING PROVIDED ON AN 'AS IS' AND 'AS AVAILABLE' BASIS, ".
			"AND ".$this->domainname." MAKES NO REPRESENTATIONS OR WARRANTIES OF ANY ".
			"KIND, WHETHER EXPRESS OR IMPLIED, WITH RESPECT TO USER'S ACCESS OF THE ".
			"SIP SERVER, INCLUDING BUT NOT LIMITED TO WARRANTIES OF MERCHANTABILITY, ".
			"NONINFRINGEMENT, TITLE OR FITNESS FOR A PARTICULAR PURPOSE. FURTHER, ".
			$this->domainname." MAKES NO REPRESENTATIONS OR WARRANTIES THAT THE SIP ".
			"SERVER, OR USER'S ACCESS THERETO, WILL BE AVAILABLE AT ANY GIVEN TIME, ".
			"OR WILL BE FREE FROM ERRORS, DEFECTS, OMISSIONS, INACCURACIES, OR FAILURES".
			" OR DELAYS IN DELIVERY OF DATA. USER ASSUMES, AND ".$this->domainname.
			" DISCLAIM, TOTAL RISK, RESPONSIBILITY, AND LIABILITY FOR USER'S ACCESS TO ".
			"AND USE OF THE SIP SERVER.\n\n".
			"Access to ".$this->domainname." SIP Server is being provided on a ".
			"non-exclusive basis. User acknowledges and understands that ".
			$this->domainname." SIP site is in a developmental stage and that ".
			$this->domainname." makes no guarantees regarding the availability or ".
			"functionality thereof. User may not sublicense its access rights to the ".
			"SIP Server to any third party. \n\n".
			"USER AGREES TO INDEMNIFY, DEFEND AND HOLD iptel.org, ITS AFFILIATES, ".
			"DIRECTORS, OFFICERS, EMPLOYEES, AGENTS AND LICENSORS HARMLESS FROM AND ".
			"AGAINST ANY AND ALL CLAIMS, ACTIONS, EXPENSES, LOSSES, AND LIABILITIES ".
			"(INCLUDING COURTS COSTS AND REASONABLE ATTORNEYS' FEES), ".
			"ARISING FROM OR RELATING TO THIS AGREEMENT INCLUDING USER'S ACCESS TO ".
			"AND USE OF THE SIP SERVER TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW,".
			" IN NO EVENT SHALL ".$this->domainname." OR ANY OF ITS LICENSORS, BE LIABLE ".
			"FOR ANY INDIRECT, SPECIAL, PUNITIVE, EXEMPLARY, OR CONSEQUENTIAL DAMAGES, ".
			"ARISING OUT OF THE ACCESS TO OR USE OF OR INABILITY TO ACCESS OR USE THE ".
			"SIP SERVER, OR THAT RESULT FROM MISTAKES, OMISSIONS, INTERRUPTIONS, ".
			"DELETIONS OF FILES, ERRORS, DEFECTS, DELAYS IN TRANSMISSION OR OPERATION OR ".
			"ANY FAILURE OF PERFORMANCE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH ".
			"DAMAGES. \n\n".
			"If User commits, in ".$this->domainname."'s  sole determination, a default ".
			"of these terms and conditions, ".$this->domainname." may immediately ".
			"terminate User's access to the SIP Server. Furthermore, ".$this->domainname.
			" reserves the right to discontinue offering access to the SIP Server at any ".
			"time. \n\n".

			"User may not assign its rights hereunder without the prior written ".
			"consent of ".$this->domainname.". User agrees to comply with all laws, ".
			"regulations and other legal requirements that apply to these terms and ".
			"conditions.  \n\n".
			"If any provision of this Agreement is held to be unenforceable for any ".
			"reason, such provision shall be reformed only to the extent necessary to ".
			"comply with applicable laws, and the remainder shall remain in full force ".
			"and effect. \n\n".
			"Any failure of ".$this->domainname." to enforce any provision of this ".
			"Agreement shall not constitute a waiver of any rights under such provision ".
			"or any other provision of this Agreement. \n\n".
			"USER ACKNOWLEDGES THAT IT HAS READ THIS AGREEMENT, UNDERSTANDS IT, AND ".
			"AGREES THAT IT IS THE COMPLETE AND EXCLUSIVE STATEMENT OF THE ENTIRE ".
			"AGREEMENT BETWEEN COMPANY AND ".$this->domainname." WITH RESPECT TO THE ".
			"SUBJECT MATTER HEREIN, AND SUPERSEDES ALL PRIOR AND CONTEMPORANEOUS ".
			"PROPOSALS, DISCUSSIONS, AGREEMENTS, UNDERSTANDINGS, AND COMMUNICATIONS, ".
			"WHETHER WRITTEN OR ORAL AND MAY BE AMENDED ONLY IN A WRITING EXECUTED BY ".
			"BOTH USER AND ".$this->domainname.". \n\n";

		/* =========================================================== */
        /* ADVANCED SETTINGS                                           */
		/* =========================================================== */

		/* ------------------------------------------------------------*/
		/* applications (experimental)                                 */
		/* ------------------------------------------------------------*/

		/* subscribe-notify -- list of events to which a user can subscribe and
		   is then notified with an instant message, if they occur; experimental
		*/
		$this->sub_not[]=new Csub_not("sip:weather@iptel.org".
			";type=temperature;operator=lt;value=0","temperature is too low");
		$this->sub_not[]=new Csub_not("sip:weather@iptel.org".
			";type=wind;operator=gt;value=10","wind is too fast");
		$this->sub_not[]=new Csub_not("sip:weather@iptel.org;".
			"type=pressure;operator=lt;value=1000","pressure is too low");
		$this->sub_not[]=new Csub_not("sip:weather@iptel.org;type=metar",
			"send METAR data");

		/* metar wheather application */
		//this is an identificator in event table for sending METAR data
		$this->metar_event_uri="sip:weather@iptel.org;type=metar";
		//from header in sip message
		$this->metar_from_sip_uri="sip:daemon@iptel.org";
		// N/A message - is sended to user when we can't get his location or METAR data
		$this->metar_na_message="sorry we can't get your location or METAR data for you";


		/* ------------------------------------------------------------*/
		/*            configure FW/NAT detection applet                */
		/* ------------------------------------------------------------*/

		/* the applet is used to detect whether user is behind firewall or NAT */

		//width of NAT detection applet
		$this->stun_applet_width=350;				
		//height of NAT detection applet
		$this->stun_applet_height=100;				
		//starting class of NAT detection applet
		$this->stun_class="STUNClientApplet.class"; 
		//jar archive with NAT detection applet - optional - you can comment 
		// it if you don't use jar archive
        $this->stun_archive="STUNClientApplet.jar";             

		/* applet parameters: */

		/* STUN server address - must be same as web server address because 
		   the java security manager allows only this one
		*/
		$this->stun_applet_param[]=new Capplet_params("server", "www.iptel.org");

		/* STUN server port. The Default value is 1221 - optional - you can comment 
			it if you want use default value
		*/
		$this->stun_applet_param[]=new Capplet_params("port", 1221);
		/* destination port for the first probing attempt -- just set up a simple
		   tcp echo server there; we use the first TCP connection to determine
		   local IP address, which can't be learned from systems setting due to
	       security manager ; default is 5060
		*/
		$this->stun_applet_param[]=new Capplet_params("tcp_dummyport", 5061);

		/* Number of times to resend a STUN message to a STUN server. The 
			Default is 9 times - optional - you can comment it if you want 
			use default value
		*/
		// $this->stun_applet_param[]=new Capplet_params("retransmit", 9);

		/* Specify source port of UDP packet to be sent from. The Default value 
		   is 5000 - optional - you can comment it if you want use default value
		*/
		// $this->stun_applet_param[]=new Capplet_params("sourceport", 5000);



		/* ------------------------------------------------------------*/
		/*            configure server monitoring					   */
		/* ------------------------------------------------------------*/

		/* if you change this values, please delete all data from table	
		   "table_ser_mon_agg" and "table_ser_mon" by reason that the 
			aggregated data may be calculated bad if you don't do it
		*/

		/* length of marginal period in seconds */
		$this->ser_moni_marginal_period_length=60*5;   //5 minutes
		
		/* length of interval (in seconds) for which will data stored, 
		   data older then this interval will be deleted
		*/
		$this->ser_moni_aggregation_interval=60*15;	//15 minut

		/* ------------------------------------------------------------*/
		/*            click to dial                                    */
		/* ------------------------------------------------------------*/

		/* address of the final destination to which we want to transfer
		   initial CSeq and CallId */
		$this->ctd_target="sip:23@192.168.2.16";

		/* address of user wishing to initiate conversation */
		$this->ctd_uri="sip:44@192.168.2.16";
		
		/* from header for click-to-dial request */
		$this->ctd_from	=	"sip:controller@iptel.org";
		
		/* ------------------------------------------------------------*/
		/* Values you typically do NOT want to change unless you know  *
        /* well what you are doing                                     *
		/* ------------------------------------------------------------*/

		/* Unless you used brute-force to change SER table names */
		$this->table_subscriber="subscriber";
		$this->table_pending="pending";
		$this->table_grp="grp";
		$this->table_aliases="aliases";
		$this->table_location="location";
		$this->table_missed_calls="missed_calls";
		$this->table_accounting="acc";
		$this->table_phonebook="phonebook";
		$this->table_event="event";
		$this->table_netgeo_cache="netgeo_cache";
		$this->table_ser_mon="server_monitoring";
		$this->table_ser_mon_agg="server_monitoring_agg";
		$this->table_message_silo="silo";
		$this->table_voice_silo="voice_silo";

		/* these are table names as reffered from script and via FIFO */
		$this->ul_table="location";
		$this->fifo_aliases_table="aliases";

		/* relative paths of serweb tree */
		$this->img_src_path =	$this->root_path."img/";
		$this->js_src_path =    $this->root_path."styles/";
		$this->style_src_path = $this->root_path."styles/";
		$this->user_pages_path = $this->root_path."user/";
		$this->admin_pages_path = $this->root_path."admin/";

		/* values used for names of reply fifos -- they change radnomly */
		$this->reply_fifo_filename="webfifo_".rand();
		$this->reply_fifo_path="/tmp/".$this->reply_fifo_filename;
		/* development value
		$this->reply_fifo_path="d:/temp/".$this->reply_fifo_filename; */	

		/* serweb version */
		$this->psignature="Web_interface_Karel_Kozlik-0.9";

		/* IM paging configuration */
		$this->charset="windows-1250";
		$this->im_length=1300;

		/* expiration times, priorities, etc. for usrloc/alias contacts */
		$this->new_alias_expires='567648000';
		$this->new_alias_q=1.00;
		$this->new_alias_callid="web_call_id@fox";
		$this->new_alias_cseq=1;
		$this->ul_priority="1.00";
		/* replication support ? (a new ser feature) */
		$this->ul_replication=1;
		/* fifo expects usernames AND domainnames ? make sure this
		   option is synchronized with usrloc's use_domain option	*/
		$this->ul_multidomain=0;

		/* seconds in which expires "get pass session" */
		$this->pre_uid_expires=3600;                
		/* is the sql database query for user authentication formed
		   with clear text password or a hashed one; the former is less
		   secure the latter works even if password hash is incorrect,
		   which sometimes happens, when it is calculated from an
		   incorrect domain during installation process
		*/
		$this->clear_text_pw=1;

	}
}

$config=new Cconfig();
?>
