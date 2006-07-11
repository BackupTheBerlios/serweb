<?
/*
 * $Id: czech-utf-8.php,v 1.35 2006/07/11 12:14:59 kozlik Exp $
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
$lang_set['date_time_format'] = "d.m.Y H:i";
$lang_set['date_format'] = 		"d.m.Y";
$lang_set['time_format'] = 		"H:i";


/* ------------------------------------------------------------*/
/*      common messages                                        */
/* ------------------------------------------------------------*/

$lang_str['user_management'] = 					"Uživatelské rozhraní";
$lang_str['admin_interface'] = 					"Administrátorské rozhraní";
$lang_str['user'] = 							"uživatel";
$lang_str['from'] = 							"z";
$lang_str['no_records'] = 						"Žádné záznamy";
$lang_str['l_logout'] = 						"Odhlášení";
$lang_str['l_edit'] = 							"změnit";
$lang_str['l_change'] = 						"změnit";
$lang_str['l_delete'] = 						"smazat";
$lang_str['l_back_to_main'] = 					"zpět na hlavní stránku";
$lang_str['l_back'] = 							"back";	//to translate 
$lang_str['l_disable'] = 						"disable";	//to translate 
$lang_str['l_enable'] = 						"enable";	//to translate 
$lang_str['l_disable_all'] = 					"disable all";	//to translate 
$lang_str['l_enable_all'] = 					"enable all";	//to translate 
$lang_str['status_unknown'] = 					"neznámý";
$lang_str['status_nonlocal'] = 					"nelokální";
$lang_str['status_nonexists'] = 				"neexistující";
$lang_str['status_online'] = 					"on line";
$lang_str['status_offline'] = 					"off line";
$lang_str['search_filter'] = 					"filtr";
$lang_str['showed_users'] = 					"Zobrazení uživatelé";
$lang_str['no_users_found'] = 					"Žádní uživatelé nenalezeni";
$lang_str['none'] = 							"none";	//to translate 
$lang_str['warning'] = 							"Warning!";	//to translate 
$lang_str['domain'] = 							"tabulka";
$lang_str['yes'] = 								"YES";	//to translate 
$lang_str['no'] = 								"NO";	//to translate 

/* ------------------------------------------------------------*/
/*      error messages                                         */
/* ------------------------------------------------------------*/

$lang_str['fe_not_valid_email'] =	 			"neplatná emailová adresa";
$lang_str['fe_is_not_valid_email'] =	 		"is not valid email address";	//to translate 
$lang_str['fe_not_valid_sip'] = 				"neplatná SIP adresa";
$lang_str['fe_not_valid_phonenumber'] = 		"neplatné telefonní číslo";
$lang_str['fe_not_filled_sip'] = 				"SIP adresu musíte vyplnit";
$lang_str['fe_passwords_not_match'] =			"hesla si neodpovídají";
$lang_str['fe_not_filled_username'] = 			"uživatelské jméno musíte vyplnit";
$lang_str['fe_not_allowed_uri'] = 				"Nepovolená SIP adresa";
$lang_str['fe_max_entries_reached'] = 			"Dosažen maximální povolený počet položek";
$lang_str['fe_not_valid_username'] = 			"not valid username";	//to translate 
$lang_str['fe_not_valid_domainname'] = 			"not valid domainname";	//to translate 

/* ------------------------------------------------------------*/
/*      buttons                                                */
/* ------------------------------------------------------------*/

$lang_str['b_add'] =		 					"Přidat";
$lang_str['b_back'] =		 					"Zpět";
$lang_str['b_delete_calls'] =		 			"Vymazat hovory";
$lang_str['b_dial_your_voicemail'] =		 	"Vytočit hlasovou schránku";
$lang_str['b_download_greeting'] =		 		"Přehrát Váš pozdrav";
$lang_str['b_edit_items_of_the_list'] =		 	"Změnit položky seznamu";
$lang_str['b_find'] = 							"Vyhledat";
$lang_str['b_forgot_pass_submit'] = 			"Zjistit heslo";
$lang_str['b_login'] =		 					"Přihlásit";
$lang_str['b_next'] =		 					"Další";
$lang_str['b_register'] = 						"Registrovat se";
$lang_str['b_send'] =		 					"Odeslat";
$lang_str['b_submit'] =		 					"Uložit";
$lang_str['b_select'] =		 					"Vyber";
$lang_str['b_test_firewall_NAT'] =		 		"Otestovat firewall/NAT";
$lang_str['b_upload_greeting'] =		 		"Nahrát nový pozdrav";
$lang_str['b_extended_settings'] =		 		"Rozšířené nastavení";


/* ------------------------------------------------------------*/
/*      tabs                                                   */
/* ------------------------------------------------------------*/

$lang_str['tab_my_account'] =		 			"můj účet";
$lang_str['tab_phonebook'] =		 			"telefonní seznam";
$lang_str['tab_missed_calls'] =	 				"zmeškané hovory";
$lang_str['tab_accounting'] =	 				"přehled volání";
$lang_str['tab_send_im'] =	 					"poslat zprávu";
$lang_str['tab_message_store'] =	 			"uložené zprávy";
$lang_str['tab_voicemail'] =	 				"hlasová schránka";
$lang_str['tab_user_preferences'] =	 			"nastavení";
$lang_str['tab_speed_dial'] =	 				"rychlá volba";

$lang_str['tab_users'] =	 					"uživatelé";
$lang_str['tab_admin_privileges'] =	 			"oprávnění správců";
$lang_str['tab_domains'] =	 					"domains";	//to translate 
$lang_str['tab_customers'] =	 				"customers";	//to translate 
$lang_str['tab_global_attributes'] =	 		"global attributes";	//to translate 
$lang_str['tab_attr_types'] =	 				"types of attributes";	//to translate 

/* ------------------------------------------------------------*/
/*      form fields                                            */
/* ------------------------------------------------------------*/

$lang_str['ff_first_name'] = 					"jméno";
$lang_str['ff_last_name'] = 					"příjmení";
$lang_str['ff_sip_address'] = 					"SIP adresa";
$lang_str['ff_your_timezone'] = 				"časová zóna";
$lang_str['ff_username'] = 						"uživatelské jméno";
$lang_str['ff_email'] = 						"email";
$lang_str['ff_show_online_only'] = 				"zobraz jenom on-line uživatele";
$lang_str['ff_language'] = 						"language";	//to translate 
$lang_str['ff_reg_confirmation'] = 				"require confirmation of registration";	//to translate 
$lang_str['ff_uid'] = 							"uid";	//to translate 
$lang_str['ff_for_ser'] = 						"for SER";	//to translate 
$lang_str['ff_for_serweb'] = 					"for SerWeb";	//to translate 
$lang_str['ff_contact_email'] = 				"contact email";	//to translate 

/* ------------------------------------------------------------*/
/*      table heading                                          */
/* ------------------------------------------------------------*/

$lang_str['th_name'] = 							"jméno";
$lang_str['th_sip_address'] = 					"SIP adresa";
$lang_str['th_aliases'] = 						"alias";
$lang_str['th_status'] = 						"stav";
$lang_str['th_timezone'] = 						"časová zóna";
$lang_str['th_calling_subscriber'] = 			"volající";
$lang_str['th_time'] = 							"čas";
$lang_str['th_username'] = 						"uživatelské jméno";
$lang_str['th_email'] = 						"email";
$lang_str['th_uid'] = 							"uid";	//to translate 

/* ------------------------------------------------------------*/
/*      login messages                                         */
/* ------------------------------------------------------------*/

$lang_str['bad_username'] = 					"Chybné uživatelské jméno nebo heslo";
$lang_str['account_disabled'] = 				"Your account was disabled";	//to translate 
$lang_str['domain_not_found'] = 				"Your domain not found";	//to translate 
$lang_str['msg_logout_s'] = 					"Odhlášen";
$lang_str['msg_logout_l'] = 					"Byl jste odhlášen. Pro nové přihlášení vyplňte uživatelské jméno a heslo";
$lang_str['userlogin'] = 						"přihlášení uživatele";
$lang_str['adminlogin'] = 						"přihlášení administrátora";
$lang_str['enter_username_and_passw'] = 		"Prosím vyplňte vaše uživatelské jméno a heslo";
$lang_str['ff_password'] = 						"heslo";
$lang_str['l_forgot_passw'] = 					"Zapoměli jste heslo?";
$lang_str['l_register'] = 						"Registrace!";
$lang_str['remember_uname'] = 					"Zapamatuj si mé uživatelské jméno na tomto počítači";
$lang_str['session_expired'] = 					"Session expired";	//to translate 
$lang_str['session_expired_relogin'] = 			"Your session expired, please relogin.";	//to translate 

/* ------------------------------------------------------------*/
/*      my account                                             */
/* ------------------------------------------------------------*/

$lang_str['msg_changes_saved_s'] = 				"Změny uloženy";
$lang_str['msg_changes_saved_l'] = 				"Vaše změny byly uloženy";
$lang_str['msg_loc_contact_deleted_s'] = 		"Kontakt vymazán";
$lang_str['msg_loc_contact_deleted_l'] = 		"Kontakt byl vymazán";
$lang_str['msg_loc_contact_added_s'] = 			"Kontakt přidán";
$lang_str['msg_loc_contact_added_l'] = 			"Kontakt byl přidán";
$lang_str['ff_your_email'] = 					"váš email";
$lang_str['ff_fwd_to_voicemail'] = 				"přesměrování do hlasové schránky";
$lang_str['ff_allow_lookup_for_me'] = 			"umožnit ostatním vyhledat mojí SIP adresu";
$lang_str['ff_status_visibility'] = 			"umožnit ostatním zjistit zda-li jsem on-line";
$lang_str['ff_your_password'] = 				"vaše heslo";
$lang_str['ff_retype_password'] = 				"heslo pro kontrolu";
$lang_str['your_aliases'] = 					"vaše aliasy";
$lang_str['your_acl'] = 						"volání povoleno do";
$lang_str['th_contact'] = 						"kontakt";
$lang_str['th_expires'] = 						"vyprší za";
$lang_str['th_priority'] = 						"priorita";
$lang_str['th_location'] = 						"umístění";
$lang_str['add_new_contact'] = 					"přidat nový kontakt";
$lang_str['ff_expires'] = 						"vyprší za";
$lang_str['contact_expire_hour'] = 				"1 hodinu";
$lang_str['contact_expire_day'] = 				"1 den";
$lang_str['contact_will_not_expire'] = 			"nikdy";
$lang_str['acl_err_local_forward'] = 			"lokální přesměrování je zakázáno";
$lang_str['acl_err_gateway_forward'] = 			"přesměrování na bránu je zakázáno";

/* ------------------------------------------------------------*/
/*      phonebook                                              */
/* ------------------------------------------------------------*/

$lang_str['msg_pb_contact_deleted_s'] = 		"Kontakt vymazán";
$lang_str['msg_pb_contact_deleted_l'] = 		"Kontakt byl vymazán z telefonního seznamu";
$lang_str['msg_pb_contact_updated_s'] = 		"Kontakt aktualizován";
$lang_str['msg_pb_contact_updated_l'] = 		"Změny byly uloženy";
$lang_str['msg_pb_contact_added_s'] = 			"Kontakt přidán";
$lang_str['msg_pb_contact_added_l'] = 			"Kontakt byl přidán do telefonního seznamu";
$lang_str['phonebook_records'] = 				"Zobrazeny kontakty";
$lang_str['l_find_user'] = 						"Vyhledávání uživatelů";

/* ------------------------------------------------------------*/
/*      find user                                              */
/* ------------------------------------------------------------*/

$lang_str['find_user'] = 						"Najdi uživatele";
$lang_str['l_add_to_phonebook'] = 				"přidej do telefonního seznamu";
$lang_str['l_back_to_phonebook'] = 				"zpět do telefonního seznamu";
$lang_str['found_users'] = 						"Uživatelé";

/* ------------------------------------------------------------*/
/*      missed calls                                           */
/* ------------------------------------------------------------*/

$lang_str['th_reply_status'] = 					"odpověď";
$lang_str['missed_calls'] = 					"zmeškané hovory";
$lang_str['no_missed_calls'] = 					"žádné zmeškané hovory";

/* ------------------------------------------------------------*/
/*      accounting                                             */
/* ------------------------------------------------------------*/

$lang_str['th_destination'] = 					"volaný";
$lang_str['th_length_of_call'] = 				"délka hovoru";
$lang_str['th_hangup'] = 						"zavěsil";
$lang_str['calls_count'] = 						"Zobrazeny hovory";
$lang_str['no_calls'] = 						"Žádné hovory";
$lang_str['msg_calls_deleted_s'] = 				"Hovory vymazány";
$lang_str['msg_calls_deleted_l'] = 				"Hovory byly úspěšně vymazány z databáze";


/* ------------------------------------------------------------*/
/*      send IM                                                */
/* ------------------------------------------------------------*/

$lang_str['fe_no_im'] = 						"nenapsal jste žádnou zprávu";
$lang_str['fe_im_too_long'] = 					"zpráva je příliš dlouhá";
$lang_str['msg_im_send_s'] = 					"Zpráva odeslána";
$lang_str['msg_im_send_l'] = 					"Zpráva byla uspěšně odeslána na adresu";
$lang_str['max_length_of_im'] = 				"Maximální délka zprávy je";
$lang_str['sending_message'] = 					"posílám zprávu";
$lang_str['please_wait'] = 						"prosím čekejte!";
$lang_str['ff_sip_address_of_recipient'] = 		"SIP adresa příjemce";
$lang_str['ff_text_of_message'] = 				"text zprávy";
$lang_str['im_remaining'] = 					"Zbývá";
$lang_str['im_characters'] = 					"znaků";


/* ------------------------------------------------------------*/
/*      message store                                          */
/* ------------------------------------------------------------*/

$lang_str['instant_messages_store'] = 			"Uložené textové zprávy";
$lang_str['voicemail_messages_store'] = 		"Uložené hlasové zprávy";
$lang_str['no_stored_instant_messages'] = 		"Nejsou uloženy žádné textové zprávy";
$lang_str['no_stored_voicemail_messages'] = 	"Nejsou uloženy žádné hlasové zprávy";
$lang_str['th_subject'] = 						"předmět";
$lang_str['l_reply'] = 							"odpovědět";
$lang_str['err_can_not_open_message'] = 		"Nelze otevřít zprávu";
$lang_str['err_voice_msg_not_found'] = 			"Zpráva nenalezena nebo nemáte přístup k přečtení zprávy";
$lang_str['msg_im_deleted_s'] = 				"Zpráva vymazána";
$lang_str['msg_im_deleted_l'] = 				"Zpráva byla úspěšně vymazána";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['customize_greetings'] = 				"Přizpůsobit pozdrav";
$lang_str['err_can_not_open_greeting'] = 		"Nelze otevřít pozdrav";

/* ------------------------------------------------------------*/
/*      attributes                                             */
/* ------------------------------------------------------------*/

$lang_str['fe_invalid_value_of_attribute'] = 	"neplatná hodnota";
$lang_str['fe_is_not_number'] = 				"není platné číslo";
$lang_str['fe_is_not_sip_adr'] = 				"není platná SIP adresa";
$lang_str['no_attributes_defined'] = 			"Žádné nastavení není povoleno administrátorem";

$lang_str['ff_send_daily_missed_calls'] =		"posílejte mi denně seznam zmeškaných hovorů na email";

$lang_str['ff_uri_def_f'] =						"default flags for uri";	//to translate 
$lang_str['ff_credential_def_f'] =				"default flags for credentials";	//to translate 
$lang_str['ff_domain_def_f'] =					"default flags for domain";	//to translate 


/* ------------------------------------------------------------*/
/*      speed dial                                             */
/* ------------------------------------------------------------*/

$lang_str['th_speed_dial'] = 					"zkrácená volba";
$lang_str['th_new_uri'] = 						"nová SIP adresa";




/* ------------------------------------------------------------*/
/*      registration                                           */
/* ------------------------------------------------------------*/

$lang_str['fe_not_accepted_terms'] = 			"Nepřijal jste naše podmínky a požadavky";
$lang_str['choose_timezone'] = 					"--- prosím vyberte vaši časovou zónu ---";
$lang_str['choose_timezone_of_user'] = 			"--- please select timezone of user ---";	//to translate 
$lang_str['fe_not_choosed_timezone'] = 			"vyberte vaši časovou zónu";
$lang_str['fe_uname_not_follow_conventions'] = 	"uživatelské jméno neodpovídá doporučovaným konvencím";
$lang_str['fe_not_filled_password'] = 			"musíte vyplnit heslo";
$lang_str['fe_not_filled_your_fname'] = 		"musíte vyplnit křestní jméno";
$lang_str['fe_not_filled_your_lname'] = 		"musíte vyplnit příjmení";
$lang_str['fe_uname_already_choosen_1'] = 		"Promiňte, uživatelské jméno";
$lang_str['fe_uname_already_choosen_2'] = 		"už bylo vybráno někým jiným. Zkuste nějaké jiné.";
$lang_str['err_sending_mail'] = 				"Promiňte, došlo k chybě při odesílání registračního emailu.";
$lang_str['registration_introduction_1'] = 		"To register, please fill out the form below and click the submit button at the bottom of the page. An email message will be sent to you confirming your registration. Please contact";	//to translate 
$lang_str['registration_introduction_2'] = 		"if you have any questions concerning registration and our free trial SIP services.";	//to translate 
$lang_str['reg_email_desc'] = 					"Adresa na kterou bude odesláno potvrzení o registraci. (Pokud vyplníte neplatnou adresu, žádné potvrzení nedostanete a účet vám nebude vytvořen.)";
$lang_str['ff_phone'] = 						"telefonní číslo";
$lang_str['reg_phone_desc'] = 					"Telefonní číslo na kterém jste k zastižení.";
$lang_str['ff_pick_username'] = 				"zvolte si uživatelské jméno";
$lang_str['reg_username_desc'] = 				"Vaše SIP adresa bude: uživatelské_jméno@".$config->domain.". Uživatelské jméno může být buď numerické začínající '8' (např., '8910') nebo alfanumerické psáno malými písmeny začínající písmenem (např., john.doe01). Nezapomeňte vaše uživatelské jméno -- budete ho potřebovat k nastavení vašeho telefonu!";
$lang_str['ff_pick_password'] = 				"zvolte si heslo";
$lang_str['reg_password_desc'] = 				"Nezapmeňte vaše heslo -- budete ho potřebovat k nastavení vašeho telefonu!";
$lang_str['ff_confirm_password'] = 				"heslo znovu pro potvrzení";
$lang_str['ff_terms_and_conditions'] = 			"podmínky a požadavky";
$lang_str['ff_i_accept'] = 						"souhlasím";
$lang_str['ff_timezone'] = 						"timezone";	//to translate 
$lang_str['l_back_to_loginform'] = 				"Zpět na přihlašovací stránku";
$lang_str['msg_user_registered_s'] = 			"User registered";	//to translate 
$lang_str['msg_user_registered_l'] = 			"New user has been successfully registered";	//to translate 
$lang_str['register_new_user'] = 				"register new user";	//to translate 

/* ------------------------------------------------------------*/
/*      registration - finished                                */
/* ------------------------------------------------------------*/

$lang_str['reg_finish_thanks'] = 				"Děkujeme za registraci v ".$config->domain;
$lang_str['reg_finish_app_forwarded'] = 		"Vaše žádost byla odeslána ke schválení.";
$lang_str['reg_finish_confirm_msg'] = 			"Očekávejte potvrzující zprávu v krátké době..";
$lang_str['reg_finish_sip_address'] = 			"Rezervujeme pro Vás tuto SIP adresu:";
$lang_str['reg_finish_questions_1'] = 			"If you have any further questions please feel free to send";	//to translate 
$lang_str['reg_finish_questions_2'] = 			"an email to";	//to translate 

/* ------------------------------------------------------------*/
/*      registration - confirmation                            */
/* ------------------------------------------------------------*/

$lang_str['reg_conf_congratulations'] = 		"Gratulujeme! Váš ".$config->domain." účet je připraven!";
$lang_str['reg_conf_set_up'] = 					"Váš ".$config->domain." účet je připraven!";
$lang_str['reg_conf_jabber_failed'] = 			"Ale vaše registrace v Jabber bráně ".$config->domain." selhala.";
$lang_str['reg_conf_contact_infomail_1'] = 		"Please contact";	//to translate 
$lang_str['reg_conf_contact_infomail_2'] = 		"for further assistance.";	//to translate 
$lang_str['reg_conf_failed'] = 					"Omlouváme se ale váš pokus o potvrzení selhal.";
$lang_str['reg_conf_nr_not_exists'] = 			"Either your confirmation number is wrong or your account has been already created!";	//to translate 
$lang_str['err_reg_conf_not_exists_conf_num'] = "Litujeme. Toto potvrzovací číslo neexistuje";

/* ------------------------------------------------------------*/
/*      registration - forgot password                         */
/* ------------------------------------------------------------*/

$lang_str['forgot_pass_head'] = 				"Zapoměl jste heslo?";
$lang_str['forgot_pass_introduction'] = 		"Jestliže jste zapoměl vaše heslo, prosím vyplňte níže vaše heslo. Email obsahující vaše heslo bude odeslán na emailovou adresu kterou jste zadal při registraci!";
$lang_str['forgot_pass_sended'] = 				"Nové heslo bylo vytvořeno a odesláno na emailovou adresu kterou jste zadal při registraci.";
$lang_str['msg_pass_conf_sended_s'] = 			"Informace pro přihlášení odeslány";
$lang_str['msg_pass_conf_sended_l'] = 			"Informace pro přihlášení byly odeslány na vaší emailovou adresu";
$lang_str['msg_password_sended_s'] = 			"Nové heslo odesláno";
$lang_str['msg_password_sended_l'] = 			"Nové heslo bylo odesláno na vaší emailovou adresu";
$lang_str['err_no_user'] = 						"Litujeme, to uživatelské jméno není registrované!";

/* ------------------------------------------------------------*/
/*      admin - users management                               */
/* ------------------------------------------------------------*/

$lang_str['err_admin_can_not_delete_user_1'] = 	"Nemůžete vymazat tohoto uživatele";
$lang_str['err_admin_can_not_delete_user_2'] = 	"tento uživatel potří do jiné domény";
$lang_str['msg_acl_updated_s'] = 				"ACL aktualizován";	
$lang_str['msg_acl_updated_l'] = 				"Access control list byl aktualizován";
$lang_str['msg_user_deleted_s'] = 				"Uživatel vymazán";
$lang_str['msg_user_deleted_l'] = 				"Uživatel byl úspěšně vymazán";
$lang_str['th_phone'] = 						"telefon";
$lang_str['l_acl'] = 							"ACL";
$lang_str['l_aliases'] = 						"aliasy";
$lang_str['l_account'] = 						"účet";
$lang_str['l_accounting'] = 					"přehled volání";
$lang_str['realy_you_want_delete_this_user'] =	"Realy you want delete this user?";	//to translate 
$lang_str['l_credentials'] = 					"credentials";	//to translate 
$lang_str['user_has_no_credentials'] = 			"User has no credentials";	//to translate 

/* ------------------------------------------------------------*/
/*      admin - ACL, aliases                                   */
/* ------------------------------------------------------------*/

$lang_str['access_control_list_of_user'] = 		"Access control list uživatele";
$lang_str['have_not_privileges_to_acl'] = 		"Nemáte oprávnění ke změně ACL";
$lang_str['err_alias_already_exists_1'] = 		"alias:";
$lang_str['err_alias_already_exists_2'] = 		"už existuje";
$lang_str['msg_alias_deleted_s'] = 				"Alias vymazán";
$lang_str['msg_alias_deleted_l'] = 				"Alias uživatele byl vymazán";
$lang_str['msg_alias_updated_s'] = 				"Alias aktualizován";
$lang_str['msg_alias_updated_l'] = 				"Vaše změny byly uloženy";
$lang_str['msg_alias_added_s'] = 				"Alias přidán";
$lang_str['msg_alias_added_l'] = 				"Alias byl přidán uživateli";
$lang_str['change_aliases_of_user'] = 			"Změna aliasů uživatele";
$lang_str['ff_alias'] = 						"alias";
$lang_str['th_alias'] = 						"alias";
$lang_str['realy_you_want_delete_this_alias'] = "Opravdu chcete smazat tento alias?";
$lang_str['user_have_not_any_aliases'] = 		"Uživatel nemá žádné aliasy";
$lang_str['ff_is_canon'] = 						"is canonical";	//to translate 
$lang_str['ff_is_enabled'] = 					"is enabled";	//to translate 
$lang_str['ff_uri_is_to'] = 					"can be used as 'to' uri";	//to translate 
$lang_str['ff_uri_is_from'] = 					"can be used as 'from' uri";	//to translate 
$lang_str['th_is_canon'] = 						"canonical";	//to translate 
$lang_str['th_uri_is_to'] = 					"to";	//to translate 
$lang_str['th_uri_is_from'] = 					"from";	//to translate 
$lang_str['l_ack'] = 							"acknowledge";	//to translate 
$lang_str['l_deny'] = 							"deny";	//to translate 
$lang_str['uris_with_same_uname_did'] = 		"existing URIs with same username and domain";	//to translate 
$lang_str['ack_values'] = 						"Acknowledge values";	//to translate 
$lang_str['uri_already_exists'] = 				"URI with selected username and domain already exists. Please acknowledge the values.";	//to translate 
$lang_str['is_to_warning'] = 					"WARNING: flag 'IS TO' is set for another URI. If you will continue, this flag will be cleared in the URI";	//to translate 
$lang_str['err_canon_uri_exists'] = 			"Can not set URI canonical because there is another canonical URI which you can not affect";	//to translate 
$lang_str['uid_with_alias'] = 					"List of UID with alias";	//to translate 

/* ------------------------------------------------------------*/
/*      admin privileges                                       */
/* ------------------------------------------------------------*/

$lang_str['admin_privileges_of'] = 				"Oprávnění administrátora ";
$lang_str['admin_competence'] = 				"působnost administrátora";
$lang_str['ff_is_admin'] = 						"je administrátor";
$lang_str['ff_change_privileges'] = 			"změna oprávnění jiných administrátorů";
$lang_str['ff_is_hostmaster'] = 				"is hostmaster";	//to translate 
$lang_str['acl_control'] = 						"změny ACL";
$lang_str['msg_privileges_updated_s'] = 		"Oprávnění aktualizovány";
$lang_str['msg_privileges_updated_l'] = 		"Oprávnění uživatele byly aktualizovány";
$lang_str['list_of_users'] = 					"Seznam uživatelů";
$lang_str['th_domain'] = 						"doména";
$lang_str['l_change_privileges'] = 				"Privileges";	//to translate 
$lang_str['ff_domain'] = 						"doména";
$lang_str['ff_realm'] = 						"realm";	//to translate 
$lang_str['th_realm'] = 						"realm";	//to translate 
$lang_str['ff_show_admins_only'] = 				"zobrazit jenom administrátory";
$lang_str['err_cant_ch_priv_of_hostmaster'] = 	"This user is hostmaster. You can't change privileges of hostmaster because you are not hostmaster!";	//to translate 


/* ------------------------------------------------------------*/
/*      attribute types                                        */
/* ------------------------------------------------------------*/

$lang_str['fe_not_filled_name_of_attribute'] = 	"musíte vyplnit jméno atributu";
$lang_str['fe_empty_not_allowed'] = 			"can not be empty";	//to translate 
$lang_str['ff_order'] = 						"order";	//to translate 
$lang_str['ff_att_name'] = 						"jméno atributu";
$lang_str['ff_att_type'] = 						"typ atributu";
$lang_str['ff_att_access'] = 					"access";	//to translate 
$lang_str['ff_label'] = 						"label";	//to translate 
$lang_str['ff_att_user'] = 						"user";	//to translate 
$lang_str['ff_att_domain'] = 					"domain";	//to translate 
$lang_str['ff_att_global'] = 					"global";	//to translate 
$lang_str['ff_multivalue'] = 					"multivalue";	//to translate 
$lang_str['ff_att_reg'] = 						"required on registration";	//to translate 
$lang_str['ff_att_req'] = 						"required (not empty)";	//to translate 
$lang_str['ff_fr_timer'] = 						"final response timer";	//to translate 
$lang_str['ff_fr_inv_timer'] = 					"final response invite timer";	//to translate 
$lang_str['ff_uid_format'] = 					"format of newly created UIDs";	//to translate 
$lang_str['ff_did_format'] = 					"format of newly created DIDs";	//to translate 

$lang_str['at_access_0'] = 						"full access";	//to translate 
$lang_str['at_access_1'] = 						"read only for users";	//to translate 
$lang_str['at_access_3'] = 						"for admins only (R/W)";	//to translate 


$lang_str['th_att_name'] = 						"jméno atributu";
$lang_str['th_att_type'] = 						"typ atributu";
$lang_str['th_order'] = 						"order";	//to translate 
$lang_str['th_label'] = 						"label";	//to translate 
$lang_str['fe_order_is_not_number'] = 			"'order' is not valid number";	//to translate 

$lang_str['fe_not_filled_item_label'] = 		"musíte vyplnit jméno položky";
$lang_str['fe_not_filled_item_value'] = 		"musíte vyplnit hodnotu položky";
$lang_str['ff_item_label'] = 					"jméno položky";
$lang_str['ff_item_value'] = 					"hodnota položky";
$lang_str['th_item_label'] = 					"jméno položky";
$lang_str['th_item_value'] = 					"hodnota položky";
$lang_str['l_back_to_editing_attributes'] = 	"zpět k editaci atributů";
$lang_str['realy_want_you_delete_this_attr'] = 	"Realy want you delete this attribute?";	//to translate 
$lang_str['realy_want_you_delete_this_item'] = 	"Realy want you delete this item?";	//to translate 


$lang_str['attr_type_warning'] = 				"On this page you may define new attributes and change types of them, their flags, etc. Preddefined attributes are mostly used internaly by SerWeb or by SER. Do not change them if you do not know what are you doing!!!";	//to translate 
$lang_str['at_hint_order'] = 					"Attributes are arranged in this order in SerWeb";	//to translate 
$lang_str['at_hint_label'] = 					"Label of attribute displayed in SerWeb. If starts with '@', the string is translated into user language with files in directory 'lang'. It is your responsibility that all used phrases are present in files for all languages.";	//to translate 
$lang_str['at_hint_for_ser'] = 					"Attribute is loaded by SER. Only newly created attributes are affected by change of this.";	//to translate 
$lang_str['at_hint_for_serweb'] = 				"Attribute is loaded by SerWeb. Only newly created attributes are affected by change of this.";	//to translate 
$lang_str['at_hint_user'] = 					"Attribute is displayed on user preferences page";	//to translate 
$lang_str['at_hint_domain'] = 					"Attribute is displayed on domain preferences page";	//to translate 
$lang_str['at_hint_global'] = 					"Attribute is displayed on global preferences page";	//to translate 
$lang_str['at_hint_multivalue'] = 				"Attribute may have multiple values";	//to translate 
$lang_str['at_hint_registration'] = 			"Attribute is displayed on user registration form";	//to translate 
$lang_str['at_hint_required'] = 				"Attribute has to have any not empty value. Not used for all types. Used for types: int, email_adr, sip_adr, etc.";	//to translate 


$lang_str['ff_att_default_value'] = 			"defaultní hodnota";
$lang_str['th_att_default_value'] = 			"defaultní hodnota";
$lang_str['ff_set_as_default'] = 				"nastavit jako defaultní";
$lang_str['edit_items_of_the_list'] = 			"změnit seznam položek";

$lang_str['o_lang_not_selected'] = 				"not selected";	//to translate 


/* ------------------------------------------------------------*/
/*      credentials                                            */
/* ------------------------------------------------------------*/


$lang_str['change_credentials_of_user'] = 		"Change credentials of user";	//to translate 

$lang_str['th_password'] = 						"password";	//to translate 
$lang_str['th_for_ser'] = 						"for SER";	//to translate 
$lang_str['th_for_serweb'] = 					"for SerWeb";	//to translate 

$lang_str['realy_want_you_delete_this_credential'] = 	"Realy want you delete this credential?";	//to translate 


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

$lang_str['sel_item_all_calls'] = 				"Všechny hovory";
$lang_str['sel_item_outgoing_calls'] = 			"Jenom odchozí hovory";
$lang_str['sel_item_incoming_cals'] = 			"Jenom příchozí hovory";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['fe_no_greeeting_file'] = 			"nevybral jste soubor s pozdravem";
$lang_str['fe_invalid_greeting_file'] = 		"soubor s pozdravem je neplatný";
$lang_str['fe_greeting_file_no_wav'] = 			"typ souboru s pozdravem musí být audio/wav";
$lang_str['fe_greeting_file_too_big'] = 		"soubor s pozdravem je příliš velký";
$lang_str['msg_greeting_stored_s'] = 			"Pozdrav uložen";
$lang_str['msg_greeting_stored_l'] = 			"Váš pozdrav byl úspěšně uložen";
$lang_str['msg_greeting_deleted_s'] = 			"Pozdrav vymazán";
$lang_str['msg_greeting_deleted_l'] = 			"Váš pozdrav byl úspěšně vymazán";

/* ------------------------------------------------------------*/
/*      whitelist                                              */
/* ------------------------------------------------------------*/

$lang_str['err_whitelist_already_exists'] = 	"Položka již existuje";

/* ------------------------------------------------------------*/
/*      multidomain                                            */
/* ------------------------------------------------------------*/

$lang_str['fe_not_customer_name'] = 			"You must fill name of customer";	//to translate 
$lang_str['ff_customer_name'] = 				"name of customer";	//to translate 
$lang_str['no_customers'] = 					"No customer";	//to translate 
$lang_str['customer'] = 						"Customer";	//to translate 

$lang_str['msg_customer_updated_s'] = 			"Customer updated";	//to translate 
$lang_str['msg_customer_updated_l'] = 			"Customer name has been updated";	//to translate 
$lang_str['msg_customer_deleted_s'] = 			"Customer deleted";	//to translate 
$lang_str['msg_customer_deleted_l'] = 			"Customer has been deleted";	//to translate 
$lang_str['msg_customer_added_s'] = 			"Customer created";	//to translate 
$lang_str['msg_customer_added_l'] = 			"New customer has been created";	//to translate 
$lang_str['err_customer_own_domains'] = 		"Customer is owning some domains, can't delete him";	//to translate 

$lang_str['d_id'] = 							"Domain ID";	//to translate 
$lang_str['d_name'] = 							"Domain name";	//to translate 
$lang_str['list_of_domains'] = 					"List of domains";	//to translate 
$lang_str['showed_domains'] = 					"Showed domains";	//to translate 
$lang_str['no_domains_found'] = 				"No domains found";	//to translate 
$lang_str['new_dom_name'] = 					"Add new domain name";	//to translate 
$lang_str['owner'] = 							"Owner";	//to translate 

$lang_str['realy_delete_domain'] = 				"Realy you want delete this domain?";	//to translate 
$lang_str['l_create_new_domain'] = 				"create new domain";	//to translate 
$lang_str['l_reload_ser'] = 					"reload SER and web server";	//to translate 
$lang_str['no_domain_name_is_set'] = 			"Enter at least one domain name";	//to translate 
$lang_str['can_not_del_last_dom_name'] = 		"Can not delete the only domain name";	//to translate 

$lang_str['msg_domain_reload_s'] = 				"Config reloaded";	//to translate 
$lang_str['msg_domain_reload_l'] = 				"Configuration of SER and web serwer has been reloaded";	//to translate 

$lang_str['msg_domain_deleted_s'] = 			"Domain deleted";	//to translate 
$lang_str['msg_domain_deleted_l'] = 			"This domain is no longer served and all associated records including subscriber data will be deleted soon. Make sure that DNS records no longer refer to this server";	//to translate 

$lang_str['assigned_domains'] = 				"Assigned domains";	//to translate 
$lang_str['unassigned_domains'] = 				"Unassigned domains";	//to translate 
$lang_str['l_assign_domain'] = 					"assign domain";	//to translate 
$lang_str['l_unassign_domain'] = 				"unassign domain";	//to translate 
$lang_str['l_assign'] =                                  "assign";	//to translate 
$lang_str['l_unassign'] =                                "unassign";	//to translate 
$lang_str['l_assigned_domains'] = 				"Domains";	//to translate 
$lang_str['l_change_layout'] = 					"Layout";	//to translate 
$lang_str['l_domain_attributes'] = 				"Attributes";	//to translate 
$lang_str['l_unassign_admin'] = 				"unassign admin";	//to translate 
$lang_str['l_set_canon'] = 						"set canonical";	//to translate 

$lang_str['admins_of_domain'] = 				"Admins of this domain";	//to translate 
$lang_str['no_admins'] = 						"No admins";	//to translate 

$lang_str['ff_address'] = 						"address";	//to translate 

$lang_str['lf_terms_and_conditions'] =			"terms and conditions";	//to translate 
$lang_str['lf_mail_register_by_admin'] = 		"mail which is send to user when is created by admin";	//to translate 
$lang_str['lf_mail_register'] = 				"mail confirmation registration";	//to translate 
$lang_str['lf_mail_fp_conf'] = 					"mail confirmation of reset password whan an old one was forgotten";	//to translate 
$lang_str['lf_mail_fp_pass'] = 					"mail new password whan an old one was forgotten";	//to translate 
$lang_str['lf_config'] = 						"domain configuration";	//to translate 

$lang_str['l_toggle_wysiwyg'] = 				"toggle WYSIWYG";	//to translate 
$lang_str['l_upload_images'] = 					"upload images";	//to translate 
$lang_str['l_back_to_default'] = 				"restore default content";	//to translate 

$lang_str['wysiwyg_warning'] = 					"Please be careful when useing WYSIWYG editor. Prolog.html must start by &lt;body&gt; element and epilog.html must end by &lt;/body&gt; element. WYSIWYG editor may strip them!";	//to translate 

$lang_str['choose_one'] = 						"choose one";	//to translate 

$lang_str['layout_files'] = 					"Layout files";	//to translate 
$lang_str['text_files'] = 						"Text files";	//to translate 

$lang_str['fe_domain_not_selected']	= 			"Domain for user isn't selected";	//to translate 

$lang_str['th_old_versions'] = 					"Old versions of this file";	//to translate 
$lang_str['initial_ver'] = 						"initial";	//to translate 

/* ------------------------------------------------------------*/
/*      wizard - create new domain                             */
/* ------------------------------------------------------------*/

$lang_str['register_new_admin'] = 				"Register new admin for domain";	//to translate 
$lang_str['assign_existing_admin'] = 			"Assign an existing admin to domain";	//to translate 
$lang_str['assign_admin_to_domain'] = 			"Assign admin to domain";	//to translate 
$lang_str['create_new_domain'] = 				"Create new domain";	//to translate 
$lang_str['l_create_new_customer'] = 			"create new customer";	//to translate 
$lang_str['create_new_customer'] = 				"Create new customer";	//to translate 
$lang_str['l_close_window'] = 					"close window";	//to translate 
$lang_str['step'] = 							"step";	//to translate 
$lang_str['l_select'] = 						"select";	//to translate 
$lang_str['domain_setup_success'] = 			"New domain has been set up successfully!";	//to translate 
$lang_str['l_skip_asignment_of_admin'] = 		"skip assignment of admin";	//to translate 




/****************************************************/
/* strings which are missing in reference lang file */
/****************************************************/

$lang_str['registration_introduction'] = 		"Pro registraci prosím vyplňte formulář níže a klikněte na tlačítko &quot;registrovat&quot; na spodku stránky. Bude vám zaslán email potvrzující registraci. Prosím kontaktujte <a href=\"mailto:".$config->regmail."\">".$config->regmail."</a> pokud máte nějaké otázky ohledně registrace nebo našich SIP služeb.";
$lang_str['reg_finish_questions'] = 			"Pokud máte nějaké dotazy neostýchejte se nám napsat";
$lang_str['reg_finish_infomail'] = 				"email na adresu <a href=\"mailto:".$config->infomail."\">".$config->infomail."</a>.";
$lang_str['reg_conf_contact_infomail'] = 		"Prosím kontaktujte <a href=\"mailto:".$config->infomail."\">".$config->infomail."</a> pro další pomoc.";
$lang_str['tab_caller_screening'] =	 			"filtrování volajících";
$lang_str['msg_caller_screening_deleted_s'] = 	"Adresa vymazána";
$lang_str['msg_caller_screening_deleted_l'] = 	"Adresa byla vymazána ze seznamu filtrovaných adres";
$lang_str['msg_caller_screening_updated_s'] = 	"Adresa změněna";
$lang_str['msg_caller_screening_updated_l'] = 	"Filtrovaná adresa byla změněna";
$lang_str['msg_caller_screening_added_s'] = 	"Adresa přidána";
$lang_str['msg_caller_screening_added_l'] = 	"Adresa byla přidána do seznamu filtrovaných adres";
$lang_str['fe_not_caller_uri'] = 				"musíte vyplnit adresu volajícího";
$lang_str['ff_screening_caller_uri'] = 			"adresa volajícího (regulární výraz)";
$lang_str['ff_action'] = 						"akce";
$lang_str['th_caller_uri'] = 					"adresa volajícího";
$lang_str['th_action'] = 						"akce";
$lang_str['no_caller_screenings_defined'] = 	"žádné filtrování volajících není definováno";
$lang_str['err_screening_already_exists'] = 	"záznam s touto adresou volajícího už existuje";
$lang_str['cs_decline'] = 						"odmítnout";
$lang_str['cs_reply_busy'] = 					"odpovědět: jsem zaneprázdněn";
$lang_str['cs_fw_to_voicemail'] = 				"přesměrovat do hlasové schránky";
$lang_str['tab_ser_moni'] =	 					"monitorování serveru";
$lang_str['err_reg_conf_already_created'] = 	"Váš účet již byl vytvořen";
$lang_str['ser_moni_current'] = 				"aktuálně";
$lang_str['ser_moni_average'] = 				"průměrně";
$lang_str['ser_moni_waiting_cur'] = 			"aktuálně čekajících";
$lang_str['ser_moni_waiting_avg'] = 			"průměrně čekajících";
$lang_str['ser_moni_total_cur'] = 				"aktuální souhrn";
$lang_str['ser_moni_total_avg'] = 				"průměrný souhrn";
$lang_str['ser_moni_local_cur'] = 				"aktuálně lokálních";
$lang_str['ser_moni_local_avg'] = 				"průměrně lokálních";
$lang_str['ser_moni_replies_cur'] = 			"akt. lokál. odpovědí";
$lang_str['ser_moni_replies_avg'] = 			"prům. lokál. odpovědí";
$lang_str['ser_moni_registered_cur'] = 			"akt. registrováno";
$lang_str['ser_moni_registered_avg'] = 			"prům. registrováno";
$lang_str['ser_moni_expired_cur'] = 			"aktuálně vypršených";
$lang_str['ser_moni_expired_avg'] = 			"průměrně vypršených";
$lang_str['ser_moni_general_values'] = 			"obecné hodnoty";
$lang_str['ser_moni_diferencial_values'] = 		"rozdílové hodnoty";
$lang_str['ser_moni_transaction_statistics'] = 	"Transakční statistiky";
$lang_str['ser_moni_completion_status'] = 		"Stav dokončení";
$lang_str['ser_moni_stateless_server_statis'] = "Statistiky bezestavového serveru";
$lang_str['ser_moni_usrLoc_stats'] = 			"Statistiky UsrLoc";
$lang_str['l_domain_preferences'] = 			"domain preferences";	//to translate 
?>
