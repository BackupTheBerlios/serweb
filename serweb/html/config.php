<?
/*
 * $Id: config.php,v 1.15 2003/01/07 18:27:55 kozlik Exp $
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

	var $show_voicemail_acl;

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

	var $ul_table;
	var $ul_priority;

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

	function Cconfig(){
		////////////////////////////////////////////////////////////////
		//            configure database

		$this->db_host="localhost";					//database host
		$this->db_name="ser";						//database name
		$this->db_user="root";						//database conection user
		$this->db_pass="qwer";						//database conection password

		// names of tables
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


		////////////////////////////////////////////////////////////////
		//            configure directories and files

		$this->root_path="/~iptel/";
		$this->root_uri="http://www.iptel.org";
		$this->img_src_path =	$this->root_path."img/";
		$this->js_src_path =    $this->root_path."styles/";
		$this->style_src_path = $this->root_path."styles/";
		$this->zonetab_file =	"d:/data/http/iptel/_data/zone.tab";		//TZ zone descriptions file, usually: /usr/share/zoneinfo/zone.tab


		////////////////////////////////////////////////////////////////
		//            configure fifo server

		$this->fifo_server="d:/temp/tmp";					//path to fifo server
		$this->reply_fifo_filename="webfifo_".rand();
		$this->reply_fifo_path="d:/temp/".$this->reply_fifo_filename;


		////////////////////////////////////////////////////////////////
		//            configure user interface

                $this->show_voicemail_acl=true;                                 //show "voicemail" in ACL and voicemail checkbox at my account

		$this->enable_dial_voicemail=false;

		$this->setup_jabber_account=false;

		$this->enable_tabs[1]=true;					//enable tab my account
		$this->enable_tabs[2]=true;					//enable tab phonebook
		$this->enable_tabs[3]=true;					//enable tab missed calls
		$this->enable_tabs[4]=true;					//enable tab accounting
		$this->enable_tabs[5]=true;					//enable tab send IM
		$this->enable_tabs[6]=true;					//enable tab notification subscription

		$this->prolog="/~iptel/prolog.html";
		$this->separator="/~iptel/separator.html";
		$this->epilog="/~iptel/epilog.html";

		$this->default_width=564;					//width of usable area

		$this->realm="iptel.org";
		$this->domainname="iptel.org";


		$this->first_alias_number=18888;

		$this->new_alias_expires='2020-01-01 00:00:00';
		$this->new_alias_q=1.00;
		$this->new_alias_callid="web_call_id@fox";
		$this->new_alias_cseq=1;

                $this->pre_uid_expires=3600;                                    //seconds in which expires "get pass session"

		$this->psignature="Web_interface_Karel_Kozlik-1.0";

		$this->web_contact="sip:daemon@iptel.org";			//address of pseudo sender

		$this->ul_table="location";
		$this->ul_priority="1.00";

                $this->im_length=1300;                                          //max length of instant message
		$this->default_domain="iptel.org";

		$this->charset="windows-1250";

                $this->num_of_showed_items=20;                                  //num of showed items in the list of users


		//regular expression list of denny sip adresses in "add contact"
		//if entered address match one regular expression from list, corresponding label is displayed
		$this->denny_reg[]=new CREG_list_item("iptel\.org$","iptel contacts prohibited");
		$this->denny_reg[]=new CREG_list_item("gateway","gateway contacts prohibited");

		//notification subscription
		$this->sub_not[]=new Csub_not("sip:weather@iptel.org;type=temperature;operator=lt;value=0","temperature is too low");
		$this->sub_not[]=new Csub_not("sip:weather@iptel.org;type=wind;operator=gt;value=10","wind is too fast");
		$this->sub_not[]=new Csub_not("sip:weather@iptel.org;type=pressure;operator=lt;value=1000","pressure is too low");
		$this->sub_not[]=new Csub_not("sip:weather@iptel.org;type=metar","send METAR data");

		//groups
		$this->grp_values[]="voicemail";
		$this->grp_values[]="ld";
		$this->grp_values[]="local";
		$this->grp_values[]="int";


		$this->mail_header_from="php.kk@kufr.cz";			//header From: in outgoing emails

		$this->forgot_pass_subj="your login information";
		$this->mail_forgot_pass="Hi,\n".
					"now you can access to your account at the folowing URL within 1 hour:\n".
					"http://oook/~iptel/user_interface/my_account.php?#session#\n\n".
					"we recommend change your password after you login\n".
					"iptel.org\n";

		$this->register_subj="Your iptel.org Registration";
		$this->mail_register="Thank you for registering with iptel.org.\n\n".
							"We are reserving the following SIP address for you: #sip_address#\n\n".
							"To finalize your registration please check the following URL within 24 hours:\n".
							"http://oook/~iptel/user_interface/reg/confirmation.php?nr=#confirm#\n\n".
							"(If you confirm later you will have to re-register.)\n\n".
							"Windows Messenger users may look at additional configuration hints at\n".
							"http://www.iptel.org/phpBB/viewtopic.php?topic=11&forum=1&0\n";


		$this->terms_and_conditions="BY PRESSING THE 'I ACCEPT' BUTTON, YOU (HEREINAFTER THE 'USER') ARE STATING THAT YOU AGREE ".
									"TO ACCEPT AND BE BOUND BY ALL OF THE TERMS AND CONDITIONS OF THIS ".
									"AGREEMENT.  DO NOT PROCEED IF YOU ARE UNABLE TO AGREE TO THE TERMS ".
									"AND CONDITIONS OF THIS AGREEMENT. THESE TERMS AND CONDITIONS OF SERVICE ".
									"FOR USE OF iptel.org SIP SERVER (THE 'AGREEMENT') CONSTITUTE A LEGALLY ".
									"BINDING CONTRACT BETWEEN iptel.org AND THE ENTITY THAT AGREES TO AND ".
									"ACCEPTS THESE TERMS AND CONDITIONS. ACCESS TO iptel.org's SESSION ".
									"INITIATION PROTOCOL SERVER ('SIP SERVER') IS BEING PROVIDED ON AN ".
									"'AS IS' AND 'AS AVAILABLE' BASIS, AND iptel.org MAKES NO REPRESENTATIONS ".
									"OR WARRANTIES OF ANY KIND, WHETHER EXPRESS OR IMPLIED, WITH RESPECT TO ".
									"USER'S ACCESS OF THE SIP SERVER, INCLUDING BUT NOT LIMITED TO WARRANTIES ".
									"OF MERCHANTABILITY, NONINFRINGEMENT, TITLE OR FITNESS FOR A PARTICULAR PURPOSE. ".
									"FURTHER, iptel.org MAKES NO REPRESENTATIONS OR WARRANTIES THAT THE SIP SERVER, ".
									"OR USER'S ACCESS THERETO, WILL BE AVAILABLE AT ANY GIVEN TIME, OR WILL BE FREE ".
									"FROM ERRORS, DEFECTS, OMISSIONS, INACCURACIES, OR FAILURES OR DELAYS IN DELIVERY ".
									"OF DATA. USER ASSUMES, AND iptel.org DISCLAIM, TOTAL RISK, RESPONSIBILITY, AND ".
									"LIABILITY FOR USER'S ACCESS TO AND USE OF THE SIP SERVER.".
									"\n\n".
									"Access to iptel.org SIP Server is being provided on a non-exclusive basis.".
									"User acknowledges and understands that iptel.org SIP site is in a developmental ".
									"stage and that iptel.org makes no guarantees regarding the availability or functionality thereof. ".
									"User may not sublicense its access rights to the SIP Server to any third party. ".
									"\n\n".
									"USER AGREES TO INDEMNIFY, DEFEND AND HOLD iptel.org, ITS AFFILIATES, DIRECTORS, OFFICERS, ".
									"EMPLOYEES, AGENTS AND LICENSORS HARMLESS FROM AND AGAINST ANY AND ALL CLAIMS, ACTIONS, ".
									"EXPENSES, LOSSES, AND LIABILITIES (INCLUDING COURTS COSTS AND REASONABLE ATTORNEYS' FEES), ".
									"ARISING FROM OR RELATING TO THIS AGREEMENT INCLUDING USER'S ACCESS TO AND USE OF THE SIP SERVER. ".
									"TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, IN NO EVENT SHALL iptel.org OR ANY OF ITS ".
									"LICENSORS, BE LIABLE FOR ANY INDIRECT, SPECIAL, PUNITIVE, EXEMPLARY, OR CONSEQUENTIAL DAMAGES, ".
									"ARISING OUT OF THE ACCESS TO OR USE OF OR INABILITY TO ACCESS OR USE THE SIP SERVER, OR THAT ".
									"RESULT FROM MISTAKES, OMISSIONS, INTERRUPTIONS, DELETIONS OF FILES, ERRORS, DEFECTS, ".
									"DELAYS IN TRANSMISSION OR OPERATION OR ANY FAILURE OF PERFORMANCE, EVEN IF ADVISED ".
									"OF THE POSSIBILITY OF SUCH DAMAGES. ".
									"\n\n".
									"If User commits, in iptel.org's sole determination, a default of these terms and ".
									"conditions, iptel.org may immediately terminate User's access to the SIP Server.  ".
									"Furthermore, iptel.org reserves the right to discontinue offering access to the ".
									"SIP Server at any time. ".
									"\n\n".
									"User may not assign its rights hereunder without the prior written consent of ".
									"iptel.org. User agrees to comply with all laws, regulations and other legal ".
									"requirements that apply to these terms and conditions.  ".
									"\n\n".
									"If any provision of this Agreement is held to be unenforceable for any reason, ".
									"such provision shall be reformed only to the extent necessary to comply with ".
									"applicable laws, and the remainder shall remain in full force and effect. ".
									"\n\n".
									"Any failure of iptel.org to enforce any provision of this Agreement shall ".
									"not constitute a waiver of any rights under such provision or any other ".
									"provision of this Agreement. ".
									"\n\n".
									"USER ACKNOWLEDGES THAT IT HAS READ THIS AGREEMENT, UNDERSTANDS IT, AND AGREES THAT ".
									"IT IS THE COMPLETE AND EXCLUSIVE STATEMENT OF THE ENTIRE AGREEMENT BETWEEN COMPANY ".
									"AND iptel.org WITH RESPECT TO THE SUBJECT MATTER HEREIN, AND SUPERSEDES ALL PRIOR ".
									"AND CONTEMPORANEOUS PROPOSALS, DISCUSSIONS, AGREEMENTS, UNDERSTANDINGS, AND COMMUNICATIONS, ".
									"WHETHER WRITTEN OR ORAL AND MAY BE AMENDED ONLY IN A WRITING EXECUTED BY BOTH USER AND iptel.org. ".
									"\n\n";


		////////////////////////////////////////////////////////////////
		//            configure FW/NAT detection applet

		$this->enable_test_firewall=true;			//show test firewall/NAT button at my account tab

		$this->stun_applet_width=350;				//width of NAT detection applet
		$this->stun_applet_height=100;				//height of NAT detection applet
                $this->stun_class="STUNClientApplet.class";             //starting class of NAT detection applet
                $this->stun_archive="STUNClientApplet.jar";             //jar archive with NAT detection applet - optional - you can comment it if you don't use jar archive

		// applet parameters:

		// STUN server address - must be same as web server address because the java security manager allows only this one
		$this->stun_applet_param[]=new Capplet_params("server", "oook");

		//STUN server port. The Default value is 1221 - optional - you can comment it if you want use default value
//		$this->stun_applet_param[]=new Capplet_params("port", 1221);

		//Number of times to resend a STUN message to a STUN server. The Default is 9 times - optional - you can comment it if you want use default value
//		$this->stun_applet_param[]=new Capplet_params("retransmit", 9);

		//Specify source port of UDP packet to be sent from. The Default value is 5000 - optional - you can comment it if you want use default value
//		$this->stun_applet_param[]=new Capplet_params("sourceport", 5000);

		//Specify port for TCP dummy connection to server. This connection is used for get local IP address.
		//Some process must listenning on this port. The Default value is 5060  - optional - you can comment it if you want use default value
		//Because java security manager don't allow detect local ip address, applet must create some dummy tcp connection to the server
		//and get local ip from structures for this tcp connection
//		$this->stun_applet_param[]=new Capplet_params("tcp_dummyport", 5060);


		////////////////////////////////////////////////////////////////
		//            configure sending METAR data

		//this is an identificator in event table for sending METAR data
		$this->metar_event_uri="sip:weather@iptel.org;type=metar";

		//from header in sip message
		$this->metar_from_sip_uri="sip:daemon@iptel.org";

		// N/A message - is sended to user when we can't get his location or METAR data
		$this->metar_na_message="sorry we can't get your location or METAR data for you";
	}
}

$config=new Cconfig();
?>
