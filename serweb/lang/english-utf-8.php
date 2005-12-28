<?
/*
 * $Id: english-utf-8.php,v 1.16 2005/12/28 10:58:35 kozlik Exp $
 *
 * Prefixes:
 * 'fe' - form error
 * 'ff' - form field
 * 'msg_*_s' - message short
 * 'msg_*_l' - message long
 * 'l' - link
 * 'th' - table heading
 * 'err' - error
 */

$lang_set['charset'] = 			"utf-8";
$lang_set['date_time_format'] = "Y-m-d H:i";
$lang_set['date_format'] = 		"Y-m-d";
$lang_set['time_format'] = 		"H:i";


/* ------------------------------------------------------------*/
/*      common messages                                        */
/* ------------------------------------------------------------*/

$lang_str['user_management'] = 					"user management";
$lang_str['admin_interface'] = 					"admin interface";
$lang_str['user'] = 							"user";
$lang_str['from'] = 							"from";
$lang_str['no_records'] = 						"No records";
$lang_str['l_logout'] = 						"Logout";
$lang_str['l_edit'] = 							"edit";
$lang_str['l_change'] = 						"change";
$lang_str['l_delete'] = 						"delete";
$lang_str['l_back_to_main'] = 					"back to main page";
$lang_str['l_back'] = 							"back";
$lang_str['l_disable'] = 						"disable";
$lang_str['l_enable'] = 						"enable";
$lang_str['l_disable_all'] = 					"disable all";
$lang_str['l_enable_all'] = 					"enable all";
$lang_str['status_unknown'] = 					"unknown";
$lang_str['status_nonlocal'] = 					"non-local";
$lang_str['status_nonexists'] = 				"non-existent";
$lang_str['status_online'] = 					"on line";
$lang_str['status_offline'] = 					"off line";
$lang_str['search_filter'] = 					"filter";
$lang_str['showed_users'] = 					"Displaying users";
$lang_str['no_users_found'] = 					"No users found";
$lang_str['none'] = 							"none";

/* ------------------------------------------------------------*/
/*      error messages                                         */
/* ------------------------------------------------------------*/

$lang_str['fe_not_valid_email'] =	 			"not valid email address";
$lang_str['fe_not_valid_sip'] = 				"not valid sip address";
$lang_str['fe_not_valid_phonenumber'] = 		"not valid phonenumber";
$lang_str['fe_not_filled_sip'] = 				"you must fill sip address";
$lang_str['fe_passwords_not_match'] =			"passwords not match";
$lang_str['fe_not_filled_username'] = 			"You must fill username";
$lang_str['fe_not_allowed_uri'] = 				"Not allowed sip address";
$lang_str['fe_max_entries_reached'] = 			"Maximum number of entries reached";

/* ------------------------------------------------------------*/
/*      buttons                                                */
/* ------------------------------------------------------------*/

$lang_str['b_add'] =		 					"Add";
$lang_str['b_back'] =		 					"Back";
$lang_str['b_delete_calls'] =		 			"Delete calls";
$lang_str['b_dial_your_voicemail'] =		 	"Dial your voicemail";
$lang_str['b_download_greeting'] =		 		"Download your greeting";
$lang_str['b_edit_items_of_the_list'] =		 	"Edit items of the list";
$lang_str['b_find'] = 							"Find";
$lang_str['b_forgot_pass_submit'] = 			"Get password";
$lang_str['b_login'] =		 					"Login";
$lang_str['b_next'] =		 					"Next";
$lang_str['b_register'] = 						"Register";
$lang_str['b_send'] =		 					"Send";
$lang_str['b_submit'] =		 					"Save";
$lang_str['b_test_firewall_NAT'] =		 		"Test firewall/NAT";
$lang_str['b_upload_greeting'] =		 		"Upload greeting";


/* ------------------------------------------------------------*/
/*      tabs                                                   */
/* ------------------------------------------------------------*/

$lang_str['tab_my_account'] =		 			"my account";
$lang_str['tab_phonebook'] =		 			"phone book";
$lang_str['tab_missed_calls'] =	 				"missed calls";
$lang_str['tab_accounting'] =	 				"accounting";
$lang_str['tab_send_im'] =	 					"send IM";
$lang_str['tab_message_store'] =	 			"message store";
$lang_str['tab_voicemail'] =	 				"voicemail";
$lang_str['tab_user_preferences'] =	 			"user preferences";
$lang_str['tab_speed_dial'] =	 				"speed dial";
$lang_str['tab_caller_screening'] =	 			"caller screening";

$lang_str['tab_users'] =	 					"users";
$lang_str['tab_admin_privileges'] =	 			"admin privileges";
$lang_str['tab_ser_moni'] =	 					"server monitoring";
$lang_str['tab_domains'] =	 					"domains";
$lang_str['tab_customers'] =	 				"customers";
$lang_str['tab_global_attributes'] =	 		"global attributes";

/* ------------------------------------------------------------*/
/*      form fields                                            */
/* ------------------------------------------------------------*/

$lang_str['ff_first_name'] = 					"first name";
$lang_str['ff_last_name'] = 					"last name";
$lang_str['ff_sip_address'] = 					"sip address";
$lang_str['ff_your_timezone'] = 				"your timezone";
$lang_str['ff_username'] = 						"username";
$lang_str['ff_email'] = 						"email";
$lang_str['ff_show_online_only'] = 				"show on-line users only";
$lang_str['ff_language'] = 						"language";

/* ------------------------------------------------------------*/
/*      table heading                                          */
/* ------------------------------------------------------------*/

$lang_str['th_name'] = 							"name";
$lang_str['th_sip_address'] = 					"sip address";
$lang_str['th_aliases'] = 						"aliases";
$lang_str['th_status'] = 						"status";
$lang_str['th_timezone'] = 						"timezone";
$lang_str['th_calling_subscriber'] = 			"calling subscriber";
$lang_str['th_time'] = 							"time";
$lang_str['th_username'] = 						"username";
$lang_str['th_email'] = 						"email";

/* ------------------------------------------------------------*/
/*      login messages                                         */
/* ------------------------------------------------------------*/

$lang_str['bad_username'] = 					"Bad username or password";
$lang_str['account_disabled'] = 				"Your account was disabled";
$lang_str['msg_logout_s'] = 					"Loged out";
$lang_str['msg_logout_l'] = 					"You have loged out. To login again, type your username and password bellow";
$lang_str['userlogin'] = 						"Userlogin";
$lang_str['adminlogin'] = 						"Adminlogin";
$lang_str['enter_username_and_passw'] = 		"Please enter your username and password";
$lang_str['ff_password'] = 						"password";
$lang_str['l_forgot_passw'] = 					"Forgot Password?";
$lang_str['l_register'] = 						"Subscribe!";
$lang_str['remember_uname'] = 					"Remember my username on this computer";
$lang_str['session_expired'] = 					"Session expired";
$lang_str['session_expired_relogin'] = 			"Your session expired, please relogin.";

/* ------------------------------------------------------------*/
/*      my account                                             */
/* ------------------------------------------------------------*/

$lang_str['msg_changes_saved_s'] = 				"Changes saved";
$lang_str['msg_changes_saved_l'] = 				"Your changes have been saved";
$lang_str['msg_loc_contact_deleted_s'] = 		"Contact deleted";
$lang_str['msg_loc_contact_deleted_l'] = 		"Your contact have been deleted";
$lang_str['msg_loc_contact_added_s'] = 			"Contact added";
$lang_str['msg_loc_contact_added_l'] = 			"Your contact have been added";
$lang_str['ff_your_email'] = 					"your email";
$lang_str['ff_fwd_to_voicemail'] = 				"forwarding to voicemail";
$lang_str['ff_allow_lookup_for_me'] = 			"allow others to look up my SIP address";
$lang_str['ff_status_visibility'] = 			"allow others to see whether or not I'm online";
$lang_str['ff_your_password'] = 				"your password";
$lang_str['ff_retype_password'] = 				"retype password";
$lang_str['your_aliases'] = 					"your aliases";
$lang_str['your_acl'] = 						"Access-Control-list";
$lang_str['th_contact'] = 						"contact";
$lang_str['th_expires'] = 						"expires";
$lang_str['th_priority'] = 						"priority";
$lang_str['th_location'] = 						"location";
$lang_str['add_new_contact'] = 					"add new contact";
$lang_str['ff_expires'] = 						"expires";
$lang_str['contact_expire_hour'] = 				"one hour";
$lang_str['contact_expire_day'] = 				"one day";
$lang_str['contact_will_not_expire'] = 			"permanent";
$lang_str['acl_err_local_forward'] = 			"local forwarding prohibited";
$lang_str['acl_err_gateway_forward'] = 			"gateway contacts prohibited";

/* ------------------------------------------------------------*/
/*      phonebook                                              */
/* ------------------------------------------------------------*/

$lang_str['msg_pb_contact_deleted_s'] = 		"Contact Deleted";
$lang_str['msg_pb_contact_deleted_l'] = 		"The contact has been deleted from your Phone Book";
$lang_str['msg_pb_contact_updated_s'] = 		"Contact Updated";
$lang_str['msg_pb_contact_updated_l'] = 		"Your changes have been saved";
$lang_str['msg_pb_contact_added_s'] = 			"Contact Added";
$lang_str['msg_pb_contact_added_l'] = 			"The contact has been added to your Phone Book";
$lang_str['phonebook_records'] = 				"Phonebook records";
$lang_str['l_find_user'] = 						"find user";

/* ------------------------------------------------------------*/
/*      find user                                              */
/* ------------------------------------------------------------*/

$lang_str['find_user'] = 						"Find user";
$lang_str['l_add_to_phonebook'] = 				"add to phonebook";
$lang_str['l_back_to_phonebook'] = 				"back to phonebook";
$lang_str['found_users'] = 						"Users";

/* ------------------------------------------------------------*/
/*      missed calls                                           */
/* ------------------------------------------------------------*/

$lang_str['th_reply_status'] = 					"reply status";
$lang_str['missed_calls'] = 					"Missed calls";
$lang_str['no_missed_calls'] = 					"No missed calls";

/* ------------------------------------------------------------*/
/*      accounting                                             */
/* ------------------------------------------------------------*/

$lang_str['th_destination'] = 					"destination";
$lang_str['th_length_of_call'] = 				"length of call";
$lang_str['th_hangup'] = 						"hang up";
$lang_str['calls_count'] = 						"Calls";
$lang_str['no_calls'] = 						"No calls";
$lang_str['msg_calls_deleted_s'] = 				"Calls deleted";
$lang_str['msg_calls_deleted_l'] = 				"Calls has been succesfully deleted";


/* ------------------------------------------------------------*/
/*      send IM                                                */
/* ------------------------------------------------------------*/

$lang_str['fe_no_im'] = 						"you didn't write message";
$lang_str['fe_im_too_long'] = 					"instant message is too long";
$lang_str['msg_im_send_s'] = 					"Message Sended";
$lang_str['msg_im_send_l'] = 					"Message has been successfully sended to address";
$lang_str['max_length_of_im'] = 				"Max length of instant message is";
$lang_str['sending_message'] = 					"sending message";
$lang_str['please_wait'] = 						"please wait!";
$lang_str['ff_sip_address_of_recipient'] = 		"sip address of recipient";
$lang_str['ff_text_of_message'] = 				"text of message";
$lang_str['im_remaining'] = 					"Remaining";
$lang_str['im_characters'] = 					"characters";


/* ------------------------------------------------------------*/
/*      message store                                          */
/* ------------------------------------------------------------*/

$lang_str['instant_messages_store'] = 			"Instant messages store";
$lang_str['voicemail_messages_store'] = 		"Voicemail messages store";
$lang_str['no_stored_instant_messages'] = 		"No stored instant messages";
$lang_str['no_stored_voicemail_messages'] = 	"No stored voicemail messages";
$lang_str['th_subject'] = 						"subject";
$lang_str['l_reply'] = 							"reply";
$lang_str['err_can_not_open_message'] = 		"Can't open message";
$lang_str['err_voice_msg_not_found'] = 			"Message not found or you haven't access to read message";
$lang_str['msg_im_deleted_s'] = 				"Message deleted";
$lang_str['msg_im_deleted_l'] = 				"Message has been successfully deleted";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['customize_greetings'] = 				"Customize greetings";
$lang_str['err_can_not_open_greeting'] = 		"Can't open greeting";

/* ------------------------------------------------------------*/
/*      attributes                                             */
/* ------------------------------------------------------------*/

$lang_str['fe_invalid_value_of_attribute'] = 	"invalid value of attribute";
$lang_str['fe_is_not_number'] = 				"is not valid number";
$lang_str['fe_is_not_sip_adr'] = 				"is not valid sip address";
$lang_str['no_attributes_defined'] = 			"No attributes defined by admin";

$lang_str['ff_send_daily_missed_calls'] =		"send me daily my missed calls to my email";

$lang_str['ff_uri_def_f'] =						"default flags for uri";
$lang_str['ff_credential_def_f'] =				"default flags for credentials";
$lang_str['ff_domain_def_f'] =					"default flags for domain";


/* ------------------------------------------------------------*/
/*      speed dial                                             */
/* ------------------------------------------------------------*/

$lang_str['th_speed_dial'] = 					"Speed dial";
$lang_str['th_new_uri'] = 						"New uri";




/* ------------------------------------------------------------*/
/*      caller screening                                       */
/* ------------------------------------------------------------*/

$lang_str['msg_caller_screening_deleted_s'] = 	"Screened uri deleted";
$lang_str['msg_caller_screening_deleted_l'] = 	"Screened uri has been deleted";
$lang_str['msg_caller_screening_updated_s'] = 	"Screened uri changed";
$lang_str['msg_caller_screening_updated_l'] = 	"Screened uri has been changed";
$lang_str['msg_caller_screening_added_s'] = 	"Screened uri added";
$lang_str['msg_caller_screening_added_l'] = 	"Screened uri has been added";
$lang_str['fe_not_caller_uri'] = 				"you must fill caller uri";
$lang_str['ff_screening_caller_uri'] = 			"caller uri (regular expression)";
$lang_str['ff_action'] = 						"action";
$lang_str['th_caller_uri'] = 					"caller uri";
$lang_str['th_action'] = 						"action";
$lang_str['no_caller_screenings_defined'] = 	"No caller screenings defined";
$lang_str['err_screening_already_exists'] = 	"Record with this caller uri already exists";
$lang_str['cs_decline'] = 						"decline";
$lang_str['cs_reply_busy'] = 					"reply you are busy";
$lang_str['cs_fw_to_voicemail'] = 				"forward to voicemail";


/* ------------------------------------------------------------*/
/*      registration                                           */
/* ------------------------------------------------------------*/

$lang_str['fe_not_accepted_terms'] = 			"You don't accept terms and conditions";
$lang_str['choose_timezone'] = 					"--- please select your timezone ---";
$lang_str['choose_timezone_of_user'] = 			"--- please select timezone of user ---";
$lang_str['fe_not_choosed_timezone'] = 			"select your timezone please";
$lang_str['fe_uname_not_follow_conventions'] = 	"username does not follow suggested conventions";
$lang_str['fe_not_filled_password'] = 			"you must fill password";
$lang_str['fe_not_filled_your_fname'] = 		"you must fill your first name";
$lang_str['fe_not_filled_your_lname'] = 		"you must fill your last name";
$lang_str['fe_uname_already_choosen_1'] = 		"Sorry, the user name";
$lang_str['fe_uname_already_choosen_2'] = 		"has already been chosen. Try again";
$lang_str['err_sending_mail'] = 				"Sorry, there was an error when sending mail. Please try again later";
$lang_str['registration_introduction'] = 		"To register, please fill out the form below and click the submit button at the bottom of the page. An email message will be sent to you confirming your registration. Please contact <a href=\"mailto:".$config->regmail."\">".$config->regmail."</a> if you have any questions concerning registration and our free trial SIP services.";
$lang_str['reg_email_desc'] = 					"Address to which a subscription confirmation request will be sent. (If an invalid address is given, no confirmation will be sent and no SIP account will be created.)";
$lang_str['ff_phone'] = 						"phone";
$lang_str['reg_phone_desc'] = 					"This is your PSTN phone number where you can be reached.";
$lang_str['ff_pick_username'] = 				"pick your user name";
$lang_str['reg_username_desc'] = 				"Your SIP address will be username@".$config->domain.". Indicate only the username part of the address. It may be either a numerical address starting with '8' (e.g., '8910') or a lower-case alphanumerical address starting with an alphabetical character (e.g., john.doe01). Do not forget your username -- you will need it to configure your phone!";
$lang_str['ff_pick_password'] = 				"pick password";
$lang_str['reg_password_desc'] = 				"Do not forget your password -- you will need it to configure your phone!";
$lang_str['ff_confirm_password'] = 				"confirmation password";
$lang_str['ff_terms_and_conditions'] = 			"terms and conditions";
$lang_str['ff_i_accept'] = 						"I accept";
$lang_str['ff_timezone'] = 						"timezone";
$lang_str['l_back_to_loginform'] = 				"Back to login form";
$lang_str['msg_user_registered_s'] = 			"User registered";
$lang_str['msg_user_registered_l'] = 			"New user has been successfully registered";
$lang_str['register_new_user'] = 				"register new user";

/* ------------------------------------------------------------*/
/*      registration - finished                                */
/* ------------------------------------------------------------*/

$lang_str['reg_finish_thanks'] = 				"Thank you for registering with ".$config->domain;
$lang_str['reg_finish_app_forwarded'] = 		"Your application was forwarded for approval.";
$lang_str['reg_finish_confirm_msg'] = 			"Expect a confirmation message shortly.";
$lang_str['reg_finish_sip_address'] = 			"We are reserving the following SIP address for you:";
$lang_str['reg_finish_questions'] = 			"If you have any further questions please feel free to send";
$lang_str['reg_finish_infomail'] = 				"an email to <a href=\"mailto:".$config->infomail."\">".$config->infomail."</a>.";

/* ------------------------------------------------------------*/
/*      registration - confirmation                            */
/* ------------------------------------------------------------*/

$lang_str['reg_conf_congratulations'] = 		"Congratulations! Your ".$config->domain." account was set up!";
$lang_str['reg_conf_set_up'] = 					"Your ".$config->domain." account was set up!";
$lang_str['reg_conf_jabber_failed'] = 			"But your ".$config->domain." Jabber Gateway registration failed.";
$lang_str['reg_conf_contact_infomail'] = 		"Please contact <a href=\"mailto:".$config->infomail."\">".$config->infomail."</a> for further assistance.";
$lang_str['reg_conf_failed'] = 					"We regret but your ".$config->domain." confirmation attempt failed.";
$lang_str['err_reg_conf_not_exists_conf_num'] = "Sorry. No such a confirmation number exists";
$lang_str['err_reg_conf_already_created'] = 	"Your account has already been created";

/* ------------------------------------------------------------*/
/*      registration - forgot password                         */
/* ------------------------------------------------------------*/

$lang_str['forgot_pass_head'] = 				"Forgot Password?";
$lang_str['forgot_pass_introduction'] = 		"If you have forgotten your password, please enter your username in the form below. An email containing your password will then be sent to the email-address you have registered with!";
$lang_str['forgot_pass_sended'] = 				"New password was created and sended to email address you have registered with.";
$lang_str['msg_pass_conf_sended_s'] = 			"Login informations sended";
$lang_str['msg_pass_conf_sended_l'] = 			"Login informations was send to your email address";
$lang_str['msg_password_sended_s'] = 			"New password sended";
$lang_str['msg_password_sended_l'] = 			"New password was send to your email address";
$lang_str['err_no_user'] = 						"Sorry, this is not a registered username!";

/* ------------------------------------------------------------*/
/*      admin - users management                               */
/* ------------------------------------------------------------*/

$lang_str['err_admin_can_not_delete_user_1'] = 	"You can't delete user";
$lang_str['err_admin_can_not_delete_user_2'] = 	"this user is from different domain";
$lang_str['msg_acl_updated_s'] = 				"ACL updated";
$lang_str['msg_acl_updated_l'] = 				"Access control list of user has been updated";
$lang_str['msg_user_deleted_s'] = 				"User deleted";
$lang_str['msg_user_deleted_l'] = 				"User has been deleted succesfuly";
$lang_str['th_phone'] = 						"phone";
$lang_str['l_acl'] = 							"ACL";
$lang_str['l_aliases'] = 						"aliases";
$lang_str['l_account'] = 						"account";
$lang_str['l_accounting'] = 					"accounting";

/* ------------------------------------------------------------*/
/*      admin - ACL, aliases                                   */
/* ------------------------------------------------------------*/

$lang_str['access_control_list_of_user'] = 		"Access control list of user";
$lang_str['have_not_privileges_to_acl'] = 		"You haven't any privileges to control ACL";
$lang_str['err_alias_already_exists_1'] = 		"The alias:";
$lang_str['err_alias_already_exists_2'] = 		"already exists";
$lang_str['msg_alias_deleted_s'] = 				"Alias Deleted";
$lang_str['msg_alias_deleted_l'] = 				"The Alias of user has been deleted";
$lang_str['msg_alias_updated_s'] = 				"Alias Updated";
$lang_str['msg_alias_updated_l'] = 				"Your changes have been saved";
$lang_str['msg_alias_added_s'] = 				"Alias Added";
$lang_str['msg_alias_added_l'] = 				"The Alias has been added to user";
$lang_str['change_aliases_of_user'] = 			"Change aliases of user";
$lang_str['ff_alias'] = 						"alias";
$lang_str['th_alias'] = 						"alias";
$lang_str['realy_you_want_delete_this_alias'] = "Realy you want delete this alias?";
$lang_str['user_have_not_any_aliases'] = 		"User haven't any aliases";

/* ------------------------------------------------------------*/
/*      admin privileges                                       */
/* ------------------------------------------------------------*/

$lang_str['admin_privileges_of'] = 				"Admin privileges of";
$lang_str['admin_competence'] = 				"admin competence";
$lang_str['ff_is_admin'] = 						"is admin";
$lang_str['ff_change_privileges'] = 			"changes privileges of admins";
$lang_str['ff_is_hostmaster'] = 				"is hostmaster";
$lang_str['acl_control'] = 						"ACL control";
$lang_str['msg_privileges_updated_s'] = 		"Privileges updated";
$lang_str['msg_privileges_updated_l'] = 		"The privileges of user has been updated";
$lang_str['list_of_users'] = 					"List of users";
$lang_str['th_domain'] = 						"domain";
$lang_str['l_change_privileges'] = 				"change privileges";
$lang_str['ff_domain'] = 						"domain";
$lang_str['ff_realm'] = 						"realm";
$lang_str['th_realm'] = 						"realm";
$lang_str['ff_show_admins_only'] = 				"show admins only";
$lang_str['err_cant_ch_priv_of_hostmaster'] = 	"This user is hostmaster. You can't change privileges of hostmaster because you are not hostmaster!";

/* ------------------------------------------------------------*/
/*      server monitoring                                      */
/* ------------------------------------------------------------*/

$lang_str['ser_moni_current'] = 				"current";
$lang_str['ser_moni_average'] = 				"average";
$lang_str['ser_moni_waiting_cur'] = 			"waiting current";
$lang_str['ser_moni_waiting_avg'] = 			"waiting average";
$lang_str['ser_moni_total_cur'] = 				"total current";
$lang_str['ser_moni_total_avg'] = 				"total average";
$lang_str['ser_moni_local_cur'] = 				"local current";
$lang_str['ser_moni_local_avg'] = 				"local average";
$lang_str['ser_moni_replies_cur'] = 			"replied localy current";
$lang_str['ser_moni_replies_avg'] = 			"replied localy average";
$lang_str['ser_moni_registered_cur'] = 			"registered current";
$lang_str['ser_moni_registered_avg'] = 			"registered average";
$lang_str['ser_moni_expired_cur'] = 			"expired current";
$lang_str['ser_moni_expired_avg'] = 			"expired average";
$lang_str['ser_moni_general_values'] = 			"general values";
$lang_str['ser_moni_diferencial_values'] = 		"diferencial values";
$lang_str['ser_moni_transaction_statistics'] = 	"Transaction Statistics";
$lang_str['ser_moni_completion_status'] = 		"Completion status";
$lang_str['ser_moni_stateless_server_statis'] = "Stateless Server Statistics";
$lang_str['ser_moni_usrLoc_stats'] = 			"UsrLoc Statistics";
$lang_str['domain'] = 							"domain";


$lang_str['fe_not_filled_name_of_attribute'] = 	"you must fill attribute name";
$lang_str['ff_att_name'] = 						"attribute name";
$lang_str['ff_att_type'] = 						"attribute type";
$lang_str['ff_att_default_value'] = 			"default value";
$lang_str['th_att_name'] = 						"attribute name";
$lang_str['th_att_type'] = 						"attribute type";
$lang_str['th_att_default_value'] = 			"default value";
$lang_str['fe_not_filled_item_label'] = 		"you must fill item label";
$lang_str['fe_not_filled_item_value'] = 		"you must fill item value";
$lang_str['edit_items_of_the_list'] = 			"edit items of the list";
$lang_str['ff_item_label'] = 					"item label";
$lang_str['ff_item_value'] = 					"item value";
$lang_str['ff_set_as_default'] = 				"set as default";
$lang_str['th_item_label'] = 					"item label";
$lang_str['th_item_value'] = 					"item value";
$lang_str['l_back_to_editing_attributes'] = 	"back to editing attributes";


/* ------------------------------------------------------------*/
/* ------------------------------------------------------------*/
/*      Application units                                      */
/* ------------------------------------------------------------*/
/*      strings which are used in application units or         */
/*      data layer methods                                     */
/* ------------------------------------------------------------*/

/* ------------------------------------------------------------*/
/*      accounting                                             */
/* ------------------------------------------------------------*/

$lang_str['sel_item_all_calls'] = 				"All calls";
$lang_str['sel_item_outgoing_calls'] = 			"Outgoing calls only";
$lang_str['sel_item_incoming_cals'] = 			"Incoming calls only";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['fe_no_greeeting_file'] = 			"you didn't select greeting file";
$lang_str['fe_invalid_greeting_file'] = 		"greeting file is invalid";
$lang_str['fe_greeting_file_no_wav'] = 			"greeting file type must be audio/wav";
$lang_str['fe_greeting_file_too_big'] = 		"greeting file is too big";
$lang_str['msg_greeting_stored_s'] = 			"Greeting stored";
$lang_str['msg_greeting_stored_l'] = 			"Your greeting has been succesfully stored";
$lang_str['msg_greeting_deleted_s'] = 			"Greeting removed";
$lang_str['msg_greeting_deleted_l'] = 			"Your greeting has been succesfully removed";

/* ------------------------------------------------------------*/
/*      whitelist                                              */
/* ------------------------------------------------------------*/

$lang_str['err_whitelist_already_exists'] = 	"Whitelist entry already exists";

/* ------------------------------------------------------------*/
/*      multidomain                                            */
/* ------------------------------------------------------------*/

$lang_str['fe_not_customer_name'] = 			"You must fill name of customer";
$lang_str['ff_customer_name'] = 				"name of customer";
$lang_str['no_customers'] = 					"No customer";
$lang_str['customer'] = 						"Customer";

$lang_str['msg_customer_updated_s'] = 			"Customer updated";
$lang_str['msg_customer_updated_l'] = 			"Customer name has been updated";
$lang_str['msg_customer_deleted_s'] = 			"Customer deleted";
$lang_str['msg_customer_deleted_l'] = 			"Customer has been deleted";
$lang_str['msg_customer_added_s'] = 			"Customer created";
$lang_str['msg_customer_added_l'] = 			"New customer has been created";
$lang_str['err_customer_own_domains'] = 		"Customer is owning some domains, can't delete him";

$lang_str['d_id'] = 							"Domain ID";
$lang_str['d_name'] = 							"Domain name";
$lang_str['list_of_domains'] = 					"List of domains";
$lang_str['showed_domains'] = 					"Displaying domains";
$lang_str['no_domains_found'] = 				"No domains found";
$lang_str['new_dom_name'] = 					"Add new domain name";
$lang_str['owner'] = 							"Owner";

$lang_str['realy_delete_domain'] = 				"Realy you want delete this domain?";
$lang_str['l_create_new_domain'] = 				"create new domain";
$lang_str['l_reload_ser'] = 					"reload SER and web server";
$lang_str['no_domain_name_is_set'] = 			"Enter at least one domain name";

$lang_str['msg_domain_reload_s'] = 				"Config reloaded";
$lang_str['msg_domain_reload_l'] = 				"Configuration of SER and web serwer has been reloaded";

$lang_str['msg_domain_deleted_s'] = 			"Domain deleted";
$lang_str['msg_domain_deleted_l'] = 			"This domain is no longer served and all associated records including subscriber data will be deleted soon. Make sure that DNS records no longer refer to this server";

$lang_str['assigned_domains'] = 				"Assigned domains";
$lang_str['unassigned_domains'] = 				"Unassigned domains";
$lang_str['l_assign_domain'] = 					"assign domain";
$lang_str['l_unassign_domain'] = 				"unassign domain";
$lang_str['l_assigned_domains'] = 				"assigned domains";
$lang_str['l_change_layout'] = 					"change layout";
$lang_str['l_domain_attributes'] = 				"domain attributes";
$lang_str['l_unassign_admin'] = 				"unassign admin";

$lang_str['admins_of_domain'] = 				"Admins of this domain";
$lang_str['no_admins'] = 						"No admins";

$lang_str['ff_address'] = 						"address";

$lang_str['lf_terms_and_conditions'] =			"terms and conditions";
$lang_str['lf_mail_register_by_admin'] = 		"mail which is send to user when is created by admin";
$lang_str['lf_mail_register'] = 				"mail confirmation registration";
$lang_str['lf_mail_fp_conf'] = 					"mail confirmation of reset password whan an old one was forgotten";
$lang_str['lf_mail_fp_pass'] = 					"mail new password whan an old one was forgotten";
$lang_str['lf_config'] = 						"domain configuration";

$lang_str['l_toggle_wysiwyg'] = 				"toggle WYSIWYG";
$lang_str['l_upload_images'] = 					"upload images";
$lang_str['l_back_to_default'] = 				"restore default content";

$lang_str['wysiwyg_warning'] = 					"Please be careful when useing WYSIWYG editor. Prolog.html must start by &lt;body&gt; element and epilog.html must end by &lt;/body&gt; element. WYSIWYG editor may strip them! Please note that compatibility list of used WYSIWYG editor is: 'Mozilla, MSIE and FireFox (Safari experimental)'. If your browser is not on this list WYSIWYG editor may not work.";

$lang_str['choose_one'] = 						"choose one";

$lang_str['layout_files'] = 					"Layout files";
$lang_str['text_files'] = 						"Text files";

$lang_str['fe_domain_not_selected']	= 			"Domain for user isn't selected";

$lang_str['th_old_versions'] = 					"Old versions of this file";
$lang_str['initial_ver'] = 						"initial";

/* ------------------------------------------------------------*/
/*      wizard - create new domain                             */
/* ------------------------------------------------------------*/

$lang_str['register_new_admin'] = 				"Register new admin for domain";
$lang_str['assign_existing_admin'] = 			"Assign an existing admin to domain";
$lang_str['assign_admin_to_domain'] = 			"Assign admin to domain";
$lang_str['create_new_domain'] = 				"Create new domain";
$lang_str['l_create_new_customer'] = 			"create new customer";
$lang_str['create_new_customer'] = 				"Create new customer";
$lang_str['l_close_window'] = 					"close window";
$lang_str['step'] = 							"step";
$lang_str['l_select'] = 						"select";
$lang_str['domain_setup_success'] = 			"New domain has been set up successfully!";
$lang_str['l_skip_asignment_of_admin'] = 		"skip assignment of admin";


?>
