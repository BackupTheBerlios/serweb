<?
/*
 * $Id: english-iso-8859-1.php,v 1.75 2013/06/14 14:27:54 kozlik Exp $
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

$lang_set['charset'] = 			"iso-8859-1";
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
$lang_str['l_dele_account'] = 					"Delete my account";
$lang_str['l_cancel'] = 						"cancel";
$lang_str['l_edit'] = 							"edit";
$lang_str['l_insert'] = 						"insert";
$lang_str['l_extended'] = 						"extended";
$lang_str['l_rename'] = 						"rename";
$lang_str['l_change'] = 						"change";
$lang_str['l_delete'] = 						"delete";
$lang_str['l_undelete'] = 						"undelete";
$lang_str['l_purge'] = 	    					"purge";
$lang_str['l_back_to_main'] = 					"back to main page";
$lang_str['l_back'] = 							"back";
$lang_str['l_disable'] = 						"disable";
$lang_str['l_enable'] = 						"enable";
$lang_str['l_disable_all'] = 					"disable all";
$lang_str['l_enable_all'] = 					"enable all";
$lang_str['l_generate'] = 					    "generate";
$lang_str['status_unknown'] = 					"unknown";
$lang_str['status_nonlocal'] = 					"non-local";
$lang_str['status_nonexists'] = 				"non-existent";
$lang_str['status_online'] = 					"on line";
$lang_str['status_offline'] = 					"off line";
$lang_str['search_filter'] = 					"filter";
$lang_str['showed_users'] = 					"Displaying users";
$lang_str['displaying_records'] = 				"Displaying records";
$lang_str['no_users_found'] = 					"No users found";
$lang_str['no_records_found'] = 				"No records found";
$lang_str['none'] = 							"none";
$lang_str['warning'] = 							"Warning!";
$lang_str['domain'] = 							"domain";
$lang_str['yes'] = 								"YES";
$lang_str['no'] = 								"NO";
$lang_str['not_exists'] = 						"does not exists";
$lang_str['filter_wildcard_note'] =             "You could use '*' and '?' wildcards in the filter fields";

/* ------------------------------------------------------------*/
/*      error messages                                         */
/* ------------------------------------------------------------*/

$lang_str['fe_not_valid_email'] =	 			"not valid email address";
$lang_str['fe_is_not_valid_email'] =	 		"is not valid email address";
$lang_str['fe_not_valid_sip'] = 				"not valid sip address";
$lang_str['fe_not_valid_phonenumber'] = 		"not valid phonenumber";
$lang_str['fe_not_filled_sip'] = 				"you must fill sip address";
$lang_str['fe_passwords_not_match'] =			"passwords not match";
$lang_str['fe_not_filled_username'] = 			"You must fill username";
$lang_str['fe_not_allowed_uri'] = 				"Not allowed sip address";
$lang_str['fe_max_entries_reached'] = 			"Maximum number of entries reached";
$lang_str['fe_not_valid_username'] = 			"not valid username";
$lang_str['fe_not_valid_domainname'] = 			"not valid domainname";

/* ------------------------------------------------------------*/
/*      buttons                                                */
/* ------------------------------------------------------------*/

$lang_str['b_add'] =		 					"Add";
$lang_str['b_apply'] =		 					"Apply";
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
$lang_str['b_cancel'] =		 					"Cancel";
$lang_str['b_select'] =		 					"Select";
$lang_str['b_test_firewall_NAT'] =		 		"Test firewall/NAT";
$lang_str['b_upload_greeting'] =		 		"Upload greeting";
$lang_str['b_extended_settings'] =		 		"Extended settings";
$lang_str['b_search'] =		 					"Search";
$lang_str['b_clear_filter'] =		 			"Clear filter";


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

$lang_str['tab_users'] =	 					"users";
$lang_str['tab_admin_privileges'] =	 			"admin privileges";
$lang_str['tab_domains'] =	 					"domains";
$lang_str['tab_customers'] =	 				"customers";
$lang_str['tab_global_attributes'] =	 		"global attributes";
$lang_str['tab_attr_types'] =	 				"types of attributes";

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
$lang_str['ff_reg_confirmation'] = 				"require confirmation of registration";
$lang_str['ff_uid'] = 							"uid";
$lang_str['ff_for_ser'] = 						"for SER";
$lang_str['ff_for_serweb'] = 					"for SerWeb";
$lang_str['ff_contact_email'] = 				"contact email";

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
$lang_str['th_uid'] = 							"uid";

/* ------------------------------------------------------------*/
/*      login messages                                         */
/* ------------------------------------------------------------*/

$lang_str['bad_username'] = 					"Bad username or password";
$lang_str['account_disabled'] = 				"Your account was disabled";
$lang_str['domain_not_found'] = 				"Your domain not found";
$lang_str['msg_logout_s'] = 					"Logged out";
$lang_str['msg_logout_l'] = 					"You have logged out. To login again, type your username and password bellow";
$lang_str['userlogin'] = 						"Userlogin";
$lang_str['adminlogin'] = 						"Adminlogin";
$lang_str['enter_username_and_passw'] = 		"Please enter your username and password";
$lang_str['ff_password'] = 						"password";
$lang_str['l_forgot_passw'] = 					"Forgot Password?";
$lang_str['l_register'] = 						"Subscribe!";
$lang_str['l_have_my_domain'] = 				"Have-my-domain!";
$lang_str['remember_uname'] = 					"Remember my username on this computer";
$lang_str['session_expired'] = 					"Session expired";
$lang_str['session_expired_relogin'] = 			"Your session expired, please relogin.";

/* ------------------------------------------------------------*/
/*      account delete                                         */
/* ------------------------------------------------------------*/

$lang_str['msg_self_account_delete_l'] = 		"Your account has been deleted";
$lang_str['l_yes_delete_it'] = 					"yes delete it";
$lang_str['are_you_sure_to_delete_account'] = 	"Are you sure you want to delete your account?";
$lang_str['delete_account_description'] = 		"If you confirm this your account will be deleted. Your data will persist in database for next <keep_days> days and then will be purged. You could ask the admin of your domain during this time period to undelete your account if you change your mind.";


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
$lang_str['l_edit_uri'] = 						"edit aliases";

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
$lang_str['msg_calls_deleted_l'] = 				"Calls has been successfully deleted";


/* ------------------------------------------------------------*/
/*      send IM                                                */
/* ------------------------------------------------------------*/

$lang_str['fe_no_im'] = 						"you didn't write any message";
$lang_str['fe_im_too_long'] = 					"instant message is too long";
$lang_str['msg_im_send_s'] = 					"Message Sent";
$lang_str['msg_im_send_l'] = 					"Message has been successfully sent to address";
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

$lang_str['ff_max_uri_user'] =					"maximum number of URIs per user";

$lang_str['attr_fwd_busy_target'] =				"destination for on-busy forwarding";
$lang_str['attr_fwd_noanswer_target'] =			"destination for on-no-answer forwarding";
$lang_str['attr_fwd_always_target'] =			"unconditional call forwarding target";


/* ------------------------------------------------------------*/
/*      speed dial                                             */
/* ------------------------------------------------------------*/

$lang_str['th_speed_dial'] = 					"Speed dial";
$lang_str['th_new_uri'] = 						"New uri";




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
$lang_str['fe_uname_already_choosen_2'] = 		"has already been chosen.";
$lang_str['err_sending_mail'] = 				"Sorry, there was an error when sending mail. Please try again later";
$lang_str['registration_introduction_1'] = 		"To register, please fill out the form below and click the submit button at the bottom of the page. An email message will be sent to you confirming your registration. Please contact";
$lang_str['registration_introduction_2'] = 		"if you have any questions concerning registration and our free trial SIP services.";
$lang_str['reg_email_desc'] = 					"Address to which a subscription confirmation request will be sent. (If an invalid address is given, no confirmation will be sent and no SIP account will be created.)";
$lang_str['reg_email_uname_desc'] = 			"Your SIP address will be same as your email address. Subscription confirmation request will be sent to this address. (If an invalid address is given, no confirmation will be sent and no SIP account will be created.) Your email address has to be of domain ".$config->domain.".";
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
$lang_str['ff_uname_assign_mode'] =             "Username assignment mode";
$lang_str['l_back_to_loginform'] = 				"Back to login form";
$lang_str['msg_user_registered_s'] = 			"User registered";
$lang_str['msg_user_registered_l'] = 			"New user has been successfully registered";
$lang_str['register_new_user'] = 				"register new user";
$lang_str["err_domain_of_email_not_match"] =    "Your email address is not from same domain as into which you are registering";

/* ------------------------------------------------------------*/
/*      registration - finished                                */
/* ------------------------------------------------------------*/

$lang_str['reg_finish_thanks'] = 				"Thank you for registering with ".$config->domain;
$lang_str['reg_finish_app_forwarded'] = 		"Your application was forwarded for approval.";
$lang_str['reg_finish_confirm_msg'] = 			"Expect a confirmation message shortly.";
$lang_str['reg_finish_sip_address'] = 			"We are reserving the following SIP address for you:";
$lang_str['reg_finish_questions_1'] = 			"If you have any further questions please feel free to send";
$lang_str['reg_finish_questions_2'] = 			"an email to";

/* ------------------------------------------------------------*/
/*      registration - confirmation                            */
/* ------------------------------------------------------------*/

$lang_str['reg_conf_congratulations'] = 		"Congratulations! Your ".$config->domain." account was set up!";
$lang_str['reg_conf_set_up'] = 					"Your ".$config->domain." account was set up!";
$lang_str['reg_conf_jabber_failed'] = 			"But your ".$config->domain." Jabber Gateway registration failed.";
$lang_str['reg_conf_contact_infomail_1'] = 		"Please contact";
$lang_str['reg_conf_contact_infomail_2'] = 		"for further assistance.";
$lang_str['reg_conf_failed'] = 					"We regret but your ".$config->domain." confirmation attempt failed.";
$lang_str['reg_conf_nr_not_exists'] = 			"Either your confirmation number is wrong or your account has been already created!";
$lang_str['err_reg_conf_not_exists_conf_num'] = "Sorry. No such a confirmation number exists";

/* ------------------------------------------------------------*/
/*      registration - forgot password                         */
/* ------------------------------------------------------------*/

$lang_str['forgot_pass_head'] = 				"Forgot Password?";
$lang_str['forgot_pass_introduction'] = 		"If you have forgotten your password, please enter your username in the form below. An email containing your password will then be sent to the email-address you have registered with!";
$lang_str['forgot_pass_sended'] = 				"New password was created and sent to email address you have registered with.";
$lang_str['msg_pass_conf_sended_s'] = 			"Login informations sent";
$lang_str['msg_pass_conf_sended_l'] = 			"Login informations was sent to your email address";
$lang_str['msg_password_sended_s'] = 			"New password sent";
$lang_str['msg_password_sended_l'] = 			"New password was sent to your email address";
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
$lang_str['msg_user_undeleted_s'] = 			"User undeleted";
$lang_str['msg_user_undeleted_l'] = 			"User has been undeleted succesfuly";
$lang_str['msg_user_purged_s'] = 				"User purged";
$lang_str['msg_user_purged_l'] = 				"User has been purged succesfuly";
$lang_str['th_phone'] = 						"phone";
$lang_str['l_acl'] = 							"ACL";
$lang_str['l_aliases'] = 						"aliases";
$lang_str['l_account'] = 						"account";
$lang_str['l_accounting'] = 					"accounting";
$lang_str['realy_you_want_delete_this_user'] =	"Do you really want delete this user?";
$lang_str['realy_you_want_purge_this_user'] =	"Do you really want purge this user?";
$lang_str['l_credentials'] = 					"credentials";
$lang_str['l_uris'] = 					        "SIP URIs";
$lang_str['user_has_no_credentials'] = 			"User has no credentials";
$lang_str['user_has_no_sip_uris'] = 			"User has no SIP URIs";
$lang_str['err_cannot_delete_own_account'] = 	"You can't delete your own account";
$lang_str['err_cannot_disable_own_account'] = 	"You can't disable your own account";
$lang_str['ff_show_deleted_users'] =            "show deleted users";
$lang_str['deleted_user'] = 					"DELETED";

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
$lang_str['ff_uri'] = 						    "URI";
$lang_str['th_uri'] = 						    "URI";
$lang_str['realy_you_want_delete_this_alias'] = "Do you really want to delete this alias?";
$lang_str['user_have_not_any_aliases'] = 		"User doesn't have any aliases";
$lang_str['ff_is_canon'] = 						"is canonical";
$lang_str['ff_is_enabled'] = 					"is enabled";
$lang_str['ff_uri_is_to'] = 					"can be used as 'to' uri";
$lang_str['ff_uri_is_from'] = 					"can be used as 'from' uri";
$lang_str['th_is_canon'] = 						"canonical";
$lang_str['th_uri_is_to'] = 					"to";
$lang_str['th_uri_is_from'] = 					"from";
$lang_str['l_ack'] = 							"confirm";
$lang_str['l_deny'] = 							"cancel";
$lang_str['uris_with_same_uname_did'] = 		"existing URIs with same username and domain";
$lang_str['ack_values'] = 						"Confirm values";
$lang_str['uri_already_exists'] = 				"URI with selected username and domain already exists. Please confirm the values.";
$lang_str['is_to_warning'] = 					"WARNING: URI <uri> is already used by another user and  flag 'IS TO' is set for it. If you will continue, 'IS TO' flag will be cleared for that URI";
$lang_str['err_canon_uri_exists'] = 			"Canonical flag can't be set for this URI because each user can have only one URI with this flag enabled. And it is already enabled for another URI which you are not permited to change.";
$lang_str['uid_with_alias'] = 					"List of UID with alias";
$lang_str['uri_available'] = 					"This alias is not used yet.";
$lang_str['uri_not_available'] = 				"This alias is already used.";
$lang_str['l_uri_suggest'] = 					"Suggest me another one";
$lang_str['no_suggestions'] = 					"Sorry, no suggestions!";
$lang_str['err_ri_dup'] =                       "An equivalent URI already exists.";
$lang_str['err_uri_limit_reached'] =            "Maximum number of URIs has been reached";
$lang_str['err_uri_modify_not_permited'] =      "You are not permited to modify this URI";
$lang_str['user_uris'] =                        "User URIs";
$lang_str['l_back_to_my_account'] = 			"back to my account";
$lang_str['msg_uri_deleted_s'] = 				"URI Deleted";
$lang_str['msg_uri_deleted_l'] = 				"The URI has been deleted";
$lang_str['msg_uri_updated_s'] = 				"URI Updated";
$lang_str['msg_uri_updated_l'] = 				"The URI has been updated";
$lang_str['msg_uri_created_s'] =   				"URI Added";
$lang_str['msg_uri_created_l'] =   				"The URI has been added";

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
$lang_str['l_change_privileges'] = 				"Privileges";
$lang_str['ff_domain'] = 						"domain";
$lang_str['ff_realm'] = 						"realm";
$lang_str['th_realm'] = 						"realm";
$lang_str['ff_show_admins_only'] = 				"show admins only";
$lang_str['err_cant_ch_priv_of_hostmaster'] = 	"This user is hostmaster. You can't change privileges of hostmaster because you are not hostmaster!";


/* ------------------------------------------------------------*/
/*      attribute types                                        */
/* ------------------------------------------------------------*/

$lang_str['fe_not_filled_name_of_attribute'] = 	"you must fill attribute name";
$lang_str['fe_empty_not_allowed'] = 			"can not be empty";
$lang_str['ff_order'] = 						"order";
$lang_str['ff_att_name'] = 						"attribute name";
$lang_str['ff_att_type'] = 						"attribute type";
$lang_str['ff_att_access'] = 					"access";
$lang_str['ff_label'] = 						"label";
$lang_str['ff_att_group'] = 					"group";
$lang_str['ff_att_uri'] = 						"uri";
$lang_str['ff_att_user'] = 						"user";
$lang_str['ff_att_domain'] = 					"domain";
$lang_str['ff_att_global'] = 					"global";
$lang_str['ff_multivalue'] = 					"multivalue";
$lang_str['ff_att_reg'] = 						"required on registration";
$lang_str['ff_att_req'] = 						"required (not empty)";
$lang_str['ff_fr_timer'] = 						"final response timer";
$lang_str['ff_fr_inv_timer'] = 					"final response invite timer";
$lang_str['ff_uid_format'] = 					"format of newly created UIDs";
$lang_str['ff_did_format'] = 					"format of newly created DIDs";

$lang_str['title_group_rename'] = 				"Rename group";
$lang_str['ff_new_group'] = 					"new name of group";

$lang_str['at_access_0'] = 						"full access";
$lang_str['at_access_1'] = 						"read only for users";
$lang_str['at_access_3'] = 						"hidden for users";
$lang_str['at_access_21'] = 					"read only";


$lang_str['th_att_name'] = 						"attribute name";
$lang_str['th_att_type'] = 						"attribute type";
$lang_str['th_order'] = 						"order";
$lang_str['th_label'] = 						"label";
$lang_str['th_att_group'] = 					"group";
$lang_str['fe_order_is_not_number'] = 			"'order' is not valid number";

$lang_str['fe_not_filled_item_label'] = 		"you must fill item label";
$lang_str['fe_not_filled_item_value'] = 		"you must fill item value";
$lang_str['ff_item_label'] = 					"item label";
$lang_str['ff_item_value'] = 					"item value";
$lang_str['th_item_label'] = 					"item label";
$lang_str['th_item_value'] = 					"item value";
$lang_str['l_back_to_editing_attributes'] = 	"back to editing attributes";
$lang_str['realy_want_you_delete_this_attr'] = 	"Really want you delete this attribute?";
$lang_str['realy_want_you_delete_this_item'] = 	"Really want you delete this item?";


$lang_str['attr_type_warning'] = 				"On this page you may define new attributes and change types of them, their flags, etc. Preddefined attributes are mostly used internaly by SerWeb or by SER. Do not change them if you do not know what are you doing!!!";
$lang_str['at_hint_order'] = 					"Attributes are arranged in this order in SerWeb";
$lang_str['at_hint_label'] = 					"Label of attribute displayed in SerWeb. If starts with '@', the string is translated into user language with files in directory 'lang'. It is your responsibility that all used phrases are present in files for all languages.";
$lang_str['at_hint_for_ser'] = 					"Attribute is loaded by SER. Only newly created attributes are affected by change of this.";
$lang_str['at_hint_for_serweb'] = 				"Attribute is loaded by SerWeb. Only newly created attributes are affected by change of this.";
$lang_str['at_hint_user'] = 					"Attribute is displayed on user preferences page";
$lang_str['at_hint_domain'] = 					"Attribute is displayed on domain preferences page";
$lang_str['at_hint_global'] = 					"Attribute is displayed on global preferences page";
$lang_str['at_hint_multivalue'] = 				"Attribute may have multiple values";
$lang_str['at_hint_registration'] = 			"Attribute is displayed on user registration form";
$lang_str['at_hint_required'] = 				"Attribute has to have any not empty value. Not used for all types. Used for types: int, email_adr, sip_adr, etc.";


$lang_str['ff_att_default_value'] = 			"default value";
$lang_str['th_att_default_value'] = 			"default value";
$lang_str['ff_set_as_default'] = 				"set as default";
$lang_str['edit_items_of_the_list'] = 			"edit items of the list";

$lang_str['o_lang_not_selected'] = 				"not selected";

$lang_str['at_int_title'] = 					"Change extended settings of int attribute";
$lang_str['ff_at_int_min'] = 					"min value";
$lang_str['ff_at_int_max'] = 					"max value";
$lang_str['ff_at_int_err'] = 					"error message";

$lang_str['ff_at_int_min_hint'] = 				"Minimum allowed value. Leave this field empty to disable check.";
$lang_str['ff_at_int_max_hint'] = 				"Maximum allowed value. Leave this field empty to disable check.";
$lang_str['ff_at_int_err_hint'] = 				"Customize error message displayed when value is not in specified range. Leave this field empty for default error message. If message starts with '@', the string is translated into user language with files in directory 'lang'. It is your responsibility that all used phrases are present in files for all languages.";

$lang_str['at_import_title'] = 					"Import attribute types";
$lang_str['ff_xml_file'] = 					    "XML file";
$lang_str['ff_at_import_purge'] = 				"Purge all attribute types before importing new ones";
$lang_str['ff_at_import_exists'] = 				"What to do when an attribute type already exists?";
$lang_str['ff_at_import_skip'] = 				"Skip it";
$lang_str['ff_at_import_update'] = 				"Replace existing attribute type with a new one";

$lang_str['fe_file_too_big'] =                  "File is too big";
$lang_str['fe_at_no_xml_file'] =                "Missing XML file";
$lang_str['fe_at_invalid_sml_file'] =           "XML file is not valid";
$lang_str['fe_at_xml_file_type'] =              "Given file is not xml";

$lang_str['err_at_int_range'] = 				"must be in interval %d and %d";
$lang_str['err_at_int_range_min'] = 			"must be great then %d";
$lang_str['err_at_int_range_max'] = 			"must be less then %d";

$lang_str['attr_grp_general'] = 				"general";
$lang_str['attr_grp_privacy'] = 				"privacy";
$lang_str['attr_grp_other'] = 					"other";
$lang_str['err_at_grp_empty'] = 				"Attribute group can't be empty";
$lang_str['err_at_new_grp_empty'] = 	        "Group name can't be empty";
$lang_str['attr_grp_create_new'] = 				"create new group";


$lang_str['l_attr_grp_toggle'] = 				"toggle displaying of attribute groups";
$lang_str['l_export_sql'] = 				    "export to SQL script";
$lang_str['l_export_xml'] = 				    "export to XML file";
$lang_str['l_import_xml'] = 				    "import from XML file";

$lang_str['msg_at_imported_s'] =                "Attribute types imported";
$lang_str['msg_at_imported_l'] =                "Attribute types has been successfully imported";

/* ------------------------------------------------------------*/
/*      credentials                                            */
/* ------------------------------------------------------------*/


$lang_str['change_credentials_of_user'] = 		"Change credentials of user";

$lang_str['th_password'] = 						"password";
$lang_str['th_for_ser'] = 						"for SER";
$lang_str['th_for_serweb'] = 					"for SerWeb";

$lang_str['err_credential_changed_domain'] = 	"Domain of user has been changed. You have to also fill new password";
$lang_str['warning_credential_changed_domain'] =		"SerWeb is configured to not store plain-text passwords. This means, if you change a user's domain, the hashed password becomes invalid. Hence, in this case,  you also have to populate the password field.";

$lang_str['realy_want_you_delete_this_credential'] = 	"Really want you delete this credential?";


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
$lang_str['msg_greeting_stored_l'] = 			"Your greeting has been successfully stored";
$lang_str['msg_greeting_deleted_s'] = 			"Greeting removed";
$lang_str['msg_greeting_deleted_l'] = 			"Your greeting has been successfully removed";

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

$lang_str['realy_delete_domain'] = 				"Do you really want delete this domain?";
$lang_str['realy_purge_domain'] =               "Do you really want purge this domain?";
$lang_str['l_create_new_domain'] = 				"create new domain";
$lang_str['l_reload_ser'] = 					"reload SER and web server";
$lang_str['no_domain_name_is_set'] = 			"Enter at least one domain name";
$lang_str['prohibited_domain_name'] = 			"Sorry, this domain name is prohibited";
$lang_str['can_not_del_last_dom_name'] = 		"Can not delete the only domain name";

$lang_str['msg_domain_reload_s'] = 				"Config reloaded";
$lang_str['msg_domain_reload_l'] = 				"Configuration of SER and web serwer has been reloaded";
$lang_str['msg_domain_deleted_s'] = 			"Domain deleted";
$lang_str['msg_domain_deleted_l'] = 			"This domain is no longer served and all associated records including subscriber data will be deleted soon. Make sure that DNS records no longer refer to this server";
$lang_str['msg_domain_undeleted_s'] = 			"Domain undeleted";
$lang_str['msg_domain_undeleted_l'] = 			"Domain has been undeleted succesfuly";
$lang_str['msg_domain_purged_s'] = 				"Domain purged";
$lang_str['msg_domain_purged_l'] = 				"Domain has been purged succesfuly";

$lang_str['assigned_domains'] = 				"Assigned domains";
$lang_str['unassigned_domains'] = 				"Unassigned domains";
$lang_str['l_assign_domain'] = 					"assign domain";
$lang_str['l_unassign_domain'] = 				"unassign domain";
$lang_str['l_assign'] =                                  "assign";
$lang_str['l_unassign'] =                                "unassign";
$lang_str['l_assigned_domains'] = 				"Domains";
$lang_str['l_change_layout'] = 					"Layout";
$lang_str['l_domain_attributes'] = 				"Attributes";
$lang_str['l_unassign_admin'] = 				"unassign admin";
$lang_str['l_set_canon'] = 						"set canonical";

$lang_str['admins_of_domain'] = 				"Admins of this domain";
$lang_str['no_admins'] = 						"No admins";

$lang_str['ff_address'] = 						"address";

$lang_str['lf_terms_and_conditions'] =			"terms and conditions";
$lang_str['lf_mail_register_by_admin'] = 		"mail which is send to user when is created by admin";
$lang_str['lf_mail_register'] = 				"mail confirmation registration";
$lang_str['lf_mail_register_conf'] = 			"mail confirmation registration (email validation required)";
$lang_str['lf_mail_fp_conf'] = 					"mail confirmation of reset password when an old one was forgotten";
$lang_str['lf_mail_fp_pass'] = 					"mail new password whan an old one was forgotten";
$lang_str['lf_mail_mmissed_calls'] = 			"mail with missed calls";
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
$lang_str['ff_show_deleted_domains'] =          "show deleted domains";
$lang_str['deleted_domain'] = 					"DELETED";


$lang_str['err_dns_lookup'] =                   "Error during DNS lookup. Can not check the DNS setting";
$lang_str['err_no_srv_record'] =                "There is no SRV record for hostname <hostname>";
$lang_str['err_wrong_srv_record'] =             "SRV record(s) found, but it has wrong target host or port. Following SRV records have been found: ";
$lang_str['err_domain_already_hosted'] = 		"This domain is already hosted on this server";
$lang_str['err_cannot_delete_own_domain'] = 	"You can't delete domain of your own account";
$lang_str['err_cannot_disable_own_domain'] = 	"You can't disable domain of your own account";



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

/* ------------------------------------------------------------*/
/*      wizard - have a domain                                 */
/* ------------------------------------------------------------*/

$lang_str['have_a_domain_head'] = 				"Have-my-domain!";
$lang_str['have_a_domain_introduction'] = 		"On this page you could register your own domain to be hosted on ".$config->domain." server. If you would like to have your domain hosted on ".$config->domain." server you have to set DNS for your domain in proper way first. There has to be a SRV record for the service 'SIP' with the protocol 'UDP' pointing to host <srv_host> and port <srv_port>.";
$lang_str['have_a_domain_introduction2'] = 		"Register your domain in two steps:";
$lang_str['have_a_domain_step1'] = 				"Check DNS record for your domain";
$lang_str['have_a_domain_step2'] = 				"Create account for administrator of the domain";
$lang_str['have_a_domain_introduction3'] = 		"Check DNS record of your domain by filling the form below.";
$lang_str[''] = 							"";
$lang_str[''] = 							"";
$lang_str[''] = 							"";


?>
