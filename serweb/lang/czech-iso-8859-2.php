<?
/*
 * $Id: czech-iso-8859-2.php,v 1.44 2007/09/21 14:21:20 kozlik Exp $
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

$lang_set['charset'] = 			"iso-8859-2";
$lang_set['date_time_format'] = "d.m.Y H:i";
$lang_set['date_format'] = 		"d.m.Y";
$lang_set['time_format'] = 		"H:i";


/* ------------------------------------------------------------*/
/*      common messages                                        */
/* ------------------------------------------------------------*/

$lang_str['user_management'] = 					"U¾ivatelské rozhraní";
$lang_str['admin_interface'] = 					"Administrátorské rozhraní";
$lang_str['user'] = 							"u¾ivatel";
$lang_str['from'] = 							"z";
$lang_str['no_records'] = 						"®ádné záznamy";
$lang_str['l_logout'] = 						"Odhlá¹ení";
$lang_str['l_edit'] = 							"zmìnit";
$lang_str['l_extended'] = 						"extended";	//to translate 
$lang_str['l_change'] = 						"zmìnit";
$lang_str['l_delete'] = 						"smazat";
$lang_str['l_back_to_main'] = 					"zpìt na hlavní stránku";
$lang_str['l_back'] = 							"zpìt";
$lang_str['l_disable'] = 						"znepøístupnit";
$lang_str['l_enable'] = 						"zpøístupnit";
$lang_str['l_disable_all'] = 					"znepøístupnit v¹e";
$lang_str['l_enable_all'] = 					"zpøístupnit v¹e";
$lang_str['status_unknown'] = 					"neznámý";
$lang_str['status_nonlocal'] = 					"nelokální";
$lang_str['status_nonexists'] = 				"neexistující";
$lang_str['status_online'] = 					"on line";
$lang_str['status_offline'] = 					"off line";
$lang_str['search_filter'] = 					"filtr";
$lang_str['showed_users'] = 					"Zobrazení u¾ivatelé";
$lang_str['displaying_records'] = 				"Zobrazené záznamy";
$lang_str['no_users_found'] = 					"®ádní u¾ivatelé nenalezeni";
$lang_str['no_records_found'] = 				"®ádné záznamy nebyly nalezeny";
$lang_str['none'] = 							"¾ádný";	
$lang_str['warning'] = 							"Varování!";
$lang_str['domain'] = 							"tabulka";
$lang_str['yes'] = 								"ANO";	
$lang_str['no'] = 								"NE";	
$lang_str['not_exists'] = 						"neexistuje";

/* ------------------------------------------------------------*/
/*      error messages                                         */
/* ------------------------------------------------------------*/

$lang_str['fe_not_valid_email'] =	 			"neplatná emailová adresa";
$lang_str['fe_is_not_valid_email'] =	 		"není platná emailová adresa";
$lang_str['fe_not_valid_sip'] = 				"neplatná SIP adresa";
$lang_str['fe_not_valid_phonenumber'] = 		"neplatné telefonní èíslo";
$lang_str['fe_not_filled_sip'] = 				"SIP adresu musíte vyplnit";
$lang_str['fe_passwords_not_match'] =			"hesla si neodpovídají";
$lang_str['fe_not_filled_username'] = 			"u¾ivatelské jméno musíte vyplnit";
$lang_str['fe_not_allowed_uri'] = 				"Nepovolená SIP adresa";
$lang_str['fe_max_entries_reached'] = 			"Dosa¾en maximální povolený poèet polo¾ek";
$lang_str['fe_not_valid_username'] = 			"neplatné u¾ivatelské jméno";
$lang_str['fe_not_valid_domainname'] = 			"neplatné doménové jméno";

/* ------------------------------------------------------------*/
/*      buttons                                                */
/* ------------------------------------------------------------*/

$lang_str['b_add'] =		 					"Pøidat";
$lang_str['b_back'] =		 					"Zpìt";
$lang_str['b_delete_calls'] =		 			"Vymazat hovory";
$lang_str['b_dial_your_voicemail'] =		 	"Vytoèit hlasovou schránku";
$lang_str['b_download_greeting'] =		 		"Pøehrát Vá¹ pozdrav";
$lang_str['b_edit_items_of_the_list'] =		 	"Zmìnit polo¾ky seznamu";
$lang_str['b_find'] = 							"Vyhledat";
$lang_str['b_forgot_pass_submit'] = 			"Zjistit heslo";
$lang_str['b_login'] =		 					"Pøihlásit";
$lang_str['b_next'] =		 					"Dal¹í";
$lang_str['b_register'] = 						"Registrovat se";
$lang_str['b_send'] =		 					"Odeslat";
$lang_str['b_submit'] =		 					"Ulo¾it";
$lang_str['b_cancel'] =		 					"Zru¹it";
$lang_str['b_select'] =		 					"Vyber";
$lang_str['b_test_firewall_NAT'] =		 		"Otestovat firewall/NAT";
$lang_str['b_upload_greeting'] =		 		"Nahrát nový pozdrav";
$lang_str['b_extended_settings'] =		 		"Roz¹íøené nastavení";
$lang_str['b_search'] =		 					"Hledej";


/* ------------------------------------------------------------*/
/*      tabs                                                   */
/* ------------------------------------------------------------*/

$lang_str['tab_my_account'] =		 			"mùj úèet";
$lang_str['tab_phonebook'] =		 			"telefonní seznam";
$lang_str['tab_missed_calls'] =	 				"zme¹kané hovory";
$lang_str['tab_accounting'] =	 				"pøehled volání";
$lang_str['tab_send_im'] =	 					"poslat zprávu";
$lang_str['tab_message_store'] =	 			"ulo¾ené zprávy";
$lang_str['tab_voicemail'] =	 				"hlasová schránka";
$lang_str['tab_user_preferences'] =	 			"nastavení";
$lang_str['tab_speed_dial'] =	 				"rychlá volba";

$lang_str['tab_users'] =	 					"u¾ivatelé";
$lang_str['tab_admin_privileges'] =	 			"oprávnìní správcù";
$lang_str['tab_domains'] =	 					"domény";
$lang_str['tab_customers'] =	 				"zákazníci";
$lang_str['tab_global_attributes'] =	 		"globální atributy";
$lang_str['tab_attr_types'] =	 				"typy atributù";

/* ------------------------------------------------------------*/
/*      form fields                                            */
/* ------------------------------------------------------------*/

$lang_str['ff_first_name'] = 					"jméno";
$lang_str['ff_last_name'] = 					"pøíjmení";
$lang_str['ff_sip_address'] = 					"SIP adresa";
$lang_str['ff_your_timezone'] = 				"èasová zóna";
$lang_str['ff_username'] = 						"u¾ivatelské jméno";
$lang_str['ff_email'] = 						"email";
$lang_str['ff_show_online_only'] = 				"zobraz jenom on-line u¾ivatele";
$lang_str['ff_language'] = 						"jazyk";
$lang_str['ff_reg_confirmation'] = 				"vy¾adovat potvrzení registrace";
$lang_str['ff_uid'] = 							"uid";
$lang_str['ff_for_ser'] = 						"pro SER";
$lang_str['ff_for_serweb'] = 					"pro SerWeb";
$lang_str['ff_contact_email'] = 				"kontaktní email";

/* ------------------------------------------------------------*/
/*      table heading                                          */
/* ------------------------------------------------------------*/

$lang_str['th_name'] = 							"jméno";
$lang_str['th_sip_address'] = 					"SIP adresa";
$lang_str['th_aliases'] = 						"alias";
$lang_str['th_status'] = 						"stav";
$lang_str['th_timezone'] = 						"èasová zóna";
$lang_str['th_calling_subscriber'] = 			"volající";
$lang_str['th_time'] = 							"èas";
$lang_str['th_username'] = 						"u¾ivatelské jméno";
$lang_str['th_email'] = 						"email";
$lang_str['th_uid'] = 							"uid";

/* ------------------------------------------------------------*/
/*      login messages                                         */
/* ------------------------------------------------------------*/

$lang_str['bad_username'] = 					"Chybné u¾ivatelské jméno nebo heslo";
$lang_str['account_disabled'] = 				"Vá¹ úèet byl znepøístupnìn";
$lang_str['domain_not_found'] = 				"Va¹e doména nebyla nalezena";
$lang_str['msg_logout_s'] = 					"Odhlá¹en";
$lang_str['msg_logout_l'] = 					"Byl jste odhlá¹en. Pro nové pøihlá¹ení vyplòte u¾ivatelské jméno a heslo";
$lang_str['userlogin'] = 						"pøihlá¹ení u¾ivatele";
$lang_str['adminlogin'] = 						"pøihlá¹ení administrátora";
$lang_str['enter_username_and_passw'] = 		"Prosím vyplòte va¹e u¾ivatelské jméno a heslo";
$lang_str['ff_password'] = 						"heslo";
$lang_str['l_forgot_passw'] = 					"Zapomìli jste heslo?";
$lang_str['l_register'] = 						"Registrace!";
$lang_str['l_have_my_domain'] = 				"Have-my-domain!";	//to translate 
$lang_str['remember_uname'] = 					"Zapamatuj si mé u¾ivatelské jméno na tomto poèítaèi";
$lang_str['session_expired'] = 					"Relace vypr¹ela";
$lang_str['session_expired_relogin'] = 			"Va¹e relace vypr¹ela, prosím pøihla¹te se znovu.";

/* ------------------------------------------------------------*/
/*      my account                                             */
/* ------------------------------------------------------------*/

$lang_str['msg_changes_saved_s'] = 				"Zmìny ulo¾eny";
$lang_str['msg_changes_saved_l'] = 				"Va¹e zmìny byly ulo¾eny";
$lang_str['msg_loc_contact_deleted_s'] = 		"Kontakt vymazán";
$lang_str['msg_loc_contact_deleted_l'] = 		"Kontakt byl vymazán";
$lang_str['msg_loc_contact_added_s'] = 			"Kontakt pøidán";
$lang_str['msg_loc_contact_added_l'] = 			"Kontakt byl pøidán";
$lang_str['ff_your_email'] = 					"vá¹ email";
$lang_str['ff_fwd_to_voicemail'] = 				"pøesmìrování do hlasové schránky";
$lang_str['ff_allow_lookup_for_me'] = 			"umo¾nit ostatním vyhledat mojí SIP adresu";
$lang_str['ff_status_visibility'] = 			"umo¾nit ostatním zjistit zda-li jsem on-line";
$lang_str['ff_your_password'] = 				"va¹e heslo";
$lang_str['ff_retype_password'] = 				"heslo pro kontrolu";
$lang_str['your_aliases'] = 					"va¹e aliasy";
$lang_str['your_acl'] = 						"volání povoleno do";
$lang_str['th_contact'] = 						"kontakt";
$lang_str['th_expires'] = 						"vypr¹í za";
$lang_str['th_priority'] = 						"priorita";
$lang_str['th_location'] = 						"umístìní";
$lang_str['add_new_contact'] = 					"pøidat nový kontakt";
$lang_str['ff_expires'] = 						"vypr¹í za";
$lang_str['contact_expire_hour'] = 				"1 hodinu";
$lang_str['contact_expire_day'] = 				"1 den";
$lang_str['contact_will_not_expire'] = 			"nikdy";
$lang_str['acl_err_local_forward'] = 			"lokální pøesmìrování je zakázáno";
$lang_str['acl_err_gateway_forward'] = 			"pøesmìrování na bránu je zakázáno";

/* ------------------------------------------------------------*/
/*      phonebook                                              */
/* ------------------------------------------------------------*/

$lang_str['msg_pb_contact_deleted_s'] = 		"Kontakt vymazán";
$lang_str['msg_pb_contact_deleted_l'] = 		"Kontakt byl vymazán z telefonního seznamu";
$lang_str['msg_pb_contact_updated_s'] = 		"Kontakt aktualizován";
$lang_str['msg_pb_contact_updated_l'] = 		"Zmìny byly ulo¾eny";
$lang_str['msg_pb_contact_added_s'] = 			"Kontakt pøidán";
$lang_str['msg_pb_contact_added_l'] = 			"Kontakt byl pøidán do telefonního seznamu";
$lang_str['phonebook_records'] = 				"Zobrazeny kontakty";
$lang_str['l_find_user'] = 						"Vyhledávání u¾ivatelù";

/* ------------------------------------------------------------*/
/*      find user                                              */
/* ------------------------------------------------------------*/

$lang_str['find_user'] = 						"Najdi u¾ivatele";
$lang_str['l_add_to_phonebook'] = 				"pøidej do telefonního seznamu";
$lang_str['l_back_to_phonebook'] = 				"zpìt do telefonního seznamu";
$lang_str['found_users'] = 						"U¾ivatelé";

/* ------------------------------------------------------------*/
/*      missed calls                                           */
/* ------------------------------------------------------------*/

$lang_str['th_reply_status'] = 					"odpovìï";
$lang_str['missed_calls'] = 					"zme¹kané hovory";
$lang_str['no_missed_calls'] = 					"¾ádné zme¹kané hovory";

/* ------------------------------------------------------------*/
/*      accounting                                             */
/* ------------------------------------------------------------*/

$lang_str['th_destination'] = 					"volaný";
$lang_str['th_length_of_call'] = 				"délka hovoru";
$lang_str['th_hangup'] = 						"zavìsil";
$lang_str['calls_count'] = 						"Zobrazeny hovory";
$lang_str['no_calls'] = 						"®ádné hovory";
$lang_str['msg_calls_deleted_s'] = 				"Hovory vymazány";
$lang_str['msg_calls_deleted_l'] = 				"Hovory byly úspì¹nì vymazány z databáze";


/* ------------------------------------------------------------*/
/*      send IM                                                */
/* ------------------------------------------------------------*/

$lang_str['fe_no_im'] = 						"nenapsal jste ¾ádnou zprávu";
$lang_str['fe_im_too_long'] = 					"zpráva je pøíli¹ dlouhá";
$lang_str['msg_im_send_s'] = 					"Zpráva odeslána";
$lang_str['msg_im_send_l'] = 					"Zpráva byla uspì¹nì odeslána na adresu";
$lang_str['max_length_of_im'] = 				"Maximální délka zprávy je";
$lang_str['sending_message'] = 					"posílám zprávu";
$lang_str['please_wait'] = 						"prosím èekejte!";
$lang_str['ff_sip_address_of_recipient'] = 		"SIP adresa pøíjemce";
$lang_str['ff_text_of_message'] = 				"text zprávy";
$lang_str['im_remaining'] = 					"Zbývá";
$lang_str['im_characters'] = 					"znakù";


/* ------------------------------------------------------------*/
/*      message store                                          */
/* ------------------------------------------------------------*/

$lang_str['instant_messages_store'] = 			"Ulo¾ené textové zprávy";
$lang_str['voicemail_messages_store'] = 		"Ulo¾ené hlasové zprávy";
$lang_str['no_stored_instant_messages'] = 		"Nejsou ulo¾eny ¾ádné textové zprávy";
$lang_str['no_stored_voicemail_messages'] = 	"Nejsou ulo¾eny ¾ádné hlasové zprávy";
$lang_str['th_subject'] = 						"pøedmìt";
$lang_str['l_reply'] = 							"odpovìdìt";
$lang_str['err_can_not_open_message'] = 		"Nelze otevøít zprávu";
$lang_str['err_voice_msg_not_found'] = 			"Zpráva nenalezena nebo nemáte pøístup k pøeètení zprávy";
$lang_str['msg_im_deleted_s'] = 				"Zpráva vymazána";
$lang_str['msg_im_deleted_l'] = 				"Zpráva byla úspì¹nì vymazána";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['customize_greetings'] = 				"Pøizpùsobit pozdrav";
$lang_str['err_can_not_open_greeting'] = 		"Nelze otevøít pozdrav";

/* ------------------------------------------------------------*/
/*      attributes                                             */
/* ------------------------------------------------------------*/

$lang_str['fe_invalid_value_of_attribute'] = 	"neplatná hodnota";
$lang_str['fe_is_not_number'] = 				"není platné èíslo";
$lang_str['fe_is_not_sip_adr'] = 				"není platná SIP adresa";
$lang_str['no_attributes_defined'] = 			"®ádné nastavení není povoleno administrátorem";

$lang_str['ff_send_daily_missed_calls'] =		"posílejte mi dennì seznam zme¹kaných hovorù na email";

$lang_str['ff_uri_def_f'] =						"defaultní flagy pro uri";
$lang_str['ff_credential_def_f'] =				"defaultní flagy pro kredenciály";
$lang_str['ff_domain_def_f'] =					"defaultní flagy pro domény";


/* ------------------------------------------------------------*/
/*      speed dial                                             */
/* ------------------------------------------------------------*/

$lang_str['th_speed_dial'] = 					"zkrácená volba";
$lang_str['th_new_uri'] = 						"nová SIP adresa";




/* ------------------------------------------------------------*/
/*      registration                                           */
/* ------------------------------------------------------------*/

$lang_str['fe_not_accepted_terms'] = 			"Nepøijal jste na¹e podmínky a po¾adavky";
$lang_str['choose_timezone'] = 					"--- prosím vyberte va¹i èasovou zónu ---";
$lang_str['choose_timezone_of_user'] = 			"--- prosím vyberte èasovou zónu u¾ivatele ---";
$lang_str['fe_not_choosed_timezone'] = 			"vyberte va¹i èasovou zónu";
$lang_str['fe_uname_not_follow_conventions'] = 	"u¾ivatelské jméno neodpovídá doporuèovaným konvencím";
$lang_str['fe_not_filled_password'] = 			"musíte vyplnit heslo";
$lang_str['fe_not_filled_your_fname'] = 		"musíte vyplnit køestní jméno";
$lang_str['fe_not_filled_your_lname'] = 		"musíte vyplnit pøíjmení";
$lang_str['fe_uname_already_choosen_1'] = 		"Promiòte, u¾ivatelské jméno";
$lang_str['fe_uname_already_choosen_2'] = 		"u¾ bylo vybráno nìkým jiným. Zkuste nìjaké jiné.";
$lang_str['err_sending_mail'] = 				"Promiòte, do¹lo k chybì pøi odesílání registraèního emailu.";
$lang_str['registration_introduction_1'] = 		"Prosím vyplòte formuláø pro registraci a kliknìte na tlaèítko 'Registrovat se'. Bude vám zaslán email potvrzující va¹í registraci. Prosím napi¹te nám na adresu";
$lang_str['registration_introduction_2'] = 		"pokud máte nìjaké dotazy ohlednì registrace nebo na¹ich slu¾eb.";
$lang_str['reg_email_desc'] = 					"Adresa na kterou bude odesláno potvrzení o registraci. (Pokud vyplníte neplatnou adresu, ¾ádné potvrzení nedostanete a úèet vám nebude vytvoøen.)";
$lang_str['reg_email_uname_desc'] = 			"Your SIP address will be same as your email address. Subscription confirmation request will be sent to this address. (If an invalid address is given, no confirmation will be sent and no SIP account will be created.) Your email address have to be from domain ".$config->domain.".";	//to translate 
$lang_str['ff_phone'] = 						"telefonní èíslo";
$lang_str['reg_phone_desc'] = 					"Telefonní èíslo na kterém jste k zasti¾ení.";
$lang_str['ff_pick_username'] = 				"zvolte si u¾ivatelské jméno";
$lang_str['reg_username_desc'] = 				"Va¹e SIP adresa bude: u¾ivatelské_jméno@".$config->domain.". U¾ivatelské jméno mù¾e být buï numerické zaèínající '8' (napø., '8910') nebo alfanumerické psáno malými písmeny zaèínající písmenem (napø., john.doe01). Nezapomeòte va¹e u¾ivatelské jméno -- budete ho potøebovat k nastavení va¹eho telefonu!";
$lang_str['ff_pick_password'] = 				"zvolte si heslo";
$lang_str['reg_password_desc'] = 				"Nezapmeòte va¹e heslo -- budete ho potøebovat k nastavení va¹eho telefonu!";
$lang_str['ff_confirm_password'] = 				"heslo znovu pro potvrzení";
$lang_str['ff_terms_and_conditions'] = 			"podmínky a po¾adavky";
$lang_str['ff_i_accept'] = 						"souhlasím";
$lang_str['ff_timezone'] = 						"èasová zóna";
$lang_str['ff_uname_assign_mode'] =             "Username assignment mode";	//to translate 
$lang_str['l_back_to_loginform'] = 				"Zpìt na pøihla¹ovací stránku";
$lang_str['msg_user_registered_s'] = 			"U¾ivatel zaregistrován";
$lang_str['msg_user_registered_l'] = 			"Nový u¾ivatel byl úspì¹nì zaregistrován";
$lang_str['register_new_user'] = 				"zaregistrovat nového u¾ivatele";
$lang_str["err_domain_of_email_not_match"] =    "Your email address is not from same domain as into which you are registering";

/* ------------------------------------------------------------*/
/*      registration - finished                                */
/* ------------------------------------------------------------*/

$lang_str['reg_finish_thanks'] = 				"Dìkujeme za registraci v ".$config->domain;
$lang_str['reg_finish_app_forwarded'] = 		"Va¹e ¾ádost byla odeslána ke schválení.";
$lang_str['reg_finish_confirm_msg'] = 			"Oèekávejte potvrzující zprávu v krátké dobì..";
$lang_str['reg_finish_sip_address'] = 			"Rezervujeme pro Vás tuto SIP adresu:";
$lang_str['reg_finish_questions_1'] = 			"Pokud máte nìjaké dal¹í dotazy, po¹lete nám";
$lang_str['reg_finish_questions_2'] = 			"email na adresu";

/* ------------------------------------------------------------*/
/*      registration - confirmation                            */
/* ------------------------------------------------------------*/

$lang_str['reg_conf_congratulations'] = 		"Gratulujeme! Vá¹ ".$config->domain." úèet je pøipraven!";
$lang_str['reg_conf_set_up'] = 					"Vá¹ ".$config->domain." úèet je pøipraven!";
$lang_str['reg_conf_jabber_failed'] = 			"Ale va¹e registrace v Jabber bránì ".$config->domain." selhala.";
$lang_str['reg_conf_contact_infomail_1'] = 		"Prosím kontaktujte";
$lang_str['reg_conf_contact_infomail_2'] = 		"pro dal¹í podporu.";
$lang_str['reg_conf_failed'] = 					"Omlouváme se ale vá¹ pokus o potvrzení selhal.";
$lang_str['reg_conf_nr_not_exists'] = 			"Buï je ¹patné potvrzovací èíslo nebo ji¾ va¹e konto bylo vytvoøeno!";	
$lang_str['err_reg_conf_not_exists_conf_num'] = "Litujeme. Toto potvrzovací èíslo neexistuje";

/* ------------------------------------------------------------*/
/*      registration - forgot password                         */
/* ------------------------------------------------------------*/

$lang_str['forgot_pass_head'] = 				"Zapomìl jste heslo?";
$lang_str['forgot_pass_introduction'] = 		"Jestli¾e jste zapomìl va¹e heslo, prosím vyplòte ní¾e va¹e heslo. Email obsahující va¹e heslo bude odeslán na emailovou adresu kterou jste zadal pøi registraci!";
$lang_str['forgot_pass_sended'] = 				"Nové heslo bylo vytvoøeno a odesláno na emailovou adresu kterou jste zadal pøi registraci.";
$lang_str['msg_pass_conf_sended_s'] = 			"Informace pro pøihlá¹ení odeslány";
$lang_str['msg_pass_conf_sended_l'] = 			"Informace pro pøihlá¹ení byly odeslány na va¹í emailovou adresu";
$lang_str['msg_password_sended_s'] = 			"Nové heslo odesláno";
$lang_str['msg_password_sended_l'] = 			"Nové heslo bylo odesláno na va¹í emailovou adresu";
$lang_str['err_no_user'] = 						"Litujeme, to u¾ivatelské jméno není registrované!";

/* ------------------------------------------------------------*/
/*      admin - users management                               */
/* ------------------------------------------------------------*/

$lang_str['err_admin_can_not_delete_user_1'] = 	"Nemù¾ete vymazat tohoto u¾ivatele";
$lang_str['err_admin_can_not_delete_user_2'] = 	"tento u¾ivatel potøí do jiné domény";
$lang_str['msg_acl_updated_s'] = 				"ACL aktualizován";	
$lang_str['msg_acl_updated_l'] = 				"Access control list byl aktualizován";
$lang_str['msg_user_deleted_s'] = 				"U¾ivatel vymazán";
$lang_str['msg_user_deleted_l'] = 				"U¾ivatel byl úspì¹nì vymazán";
$lang_str['th_phone'] = 						"telefon";
$lang_str['l_acl'] = 							"ACL";
$lang_str['l_aliases'] = 						"aliasy";
$lang_str['l_account'] = 						"úèet";
$lang_str['l_accounting'] = 					"pøehled volání";
$lang_str['realy_you_want_delete_this_user'] =	"Opravdu chcete smazat tohoto u¾ivatele?";
$lang_str['l_credentials'] = 					"kredenciály";
$lang_str['user_has_no_credentials'] = 			"U¾ivatel nemá ¾ádné kredenciály";

/* ------------------------------------------------------------*/
/*      admin - ACL, aliases                                   */
/* ------------------------------------------------------------*/

$lang_str['access_control_list_of_user'] = 		"Access control list u¾ivatele";
$lang_str['have_not_privileges_to_acl'] = 		"Nemáte oprávnìní ke zmìnì ACL";
$lang_str['err_alias_already_exists_1'] = 		"alias:";
$lang_str['err_alias_already_exists_2'] = 		"u¾ existuje";
$lang_str['msg_alias_deleted_s'] = 				"Alias vymazán";
$lang_str['msg_alias_deleted_l'] = 				"Alias u¾ivatele byl vymazán";
$lang_str['msg_alias_updated_s'] = 				"Alias aktualizován";
$lang_str['msg_alias_updated_l'] = 				"Va¹e zmìny byly ulo¾eny";
$lang_str['msg_alias_added_s'] = 				"Alias pøidán";
$lang_str['msg_alias_added_l'] = 				"Alias byl pøidán u¾ivateli";
$lang_str['change_aliases_of_user'] = 			"Zmìna aliasù u¾ivatele";
$lang_str['ff_alias'] = 						"alias";
$lang_str['th_alias'] = 						"alias";
$lang_str['realy_you_want_delete_this_alias'] = "Opravdu chcete smazat tento alias?";
$lang_str['user_have_not_any_aliases'] = 		"U¾ivatel nemá ¾ádné aliasy";
$lang_str['ff_is_canon'] = 						"je kanonický"; 
$lang_str['ff_is_enabled'] = 					"je zpøístupnìný"; 
$lang_str['ff_uri_is_to'] = 					"mù¾e být pou¾ito jako 'to' uri";
$lang_str['ff_uri_is_from'] = 					"mù¾e být pou¾ito jako 'from' uri";
$lang_str['th_is_canon'] = 						"kanonický";
$lang_str['th_uri_is_to'] = 					"to";
$lang_str['th_uri_is_from'] = 					"from";
$lang_str['l_ack'] = 							"potvrdit";
$lang_str['l_deny'] = 							"zamítnout";
$lang_str['uris_with_same_uname_did'] = 		"existuje URI které má stejné u¾ivatelské jméno a doménu";
$lang_str['ack_values'] = 						"Potvrïte hodnoty";
$lang_str['uri_already_exists'] = 				"URI s vybraným u¾ivatelským jménem a doménou ji¾ existuje. Prosím potvrïte zadané hodnoty.";
$lang_str['is_to_warning'] = 					"VAROVÁNÍ: flag 'IS TO' je nastaven pro jiné URI. Pokud budete pokraèovat, tento flag bude smazán ve zmínìném URI";
$lang_str['err_canon_uri_exists'] = 			"Nelze nastavit URI jako kanonické proto¾e existuje jiné kanonické URI které nemù¾ete mìnit";
$lang_str['uid_with_alias'] = 					"Seznam UID s aliasem";	

/* ------------------------------------------------------------*/
/*      admin privileges                                       */
/* ------------------------------------------------------------*/

$lang_str['admin_privileges_of'] = 				"Oprávnìní administrátora ";
$lang_str['admin_competence'] = 				"pùsobnost administrátora";
$lang_str['ff_is_admin'] = 						"je administrátor";
$lang_str['ff_change_privileges'] = 			"zmìna oprávnìní jiných administrátorù";
$lang_str['ff_is_hostmaster'] = 				"je hostmaster"; 
$lang_str['acl_control'] = 						"zmìny ACL";
$lang_str['msg_privileges_updated_s'] = 		"Oprávnìní aktualizovány";
$lang_str['msg_privileges_updated_l'] = 		"Oprávnìní u¾ivatele byly aktualizovány";
$lang_str['list_of_users'] = 					"Seznam u¾ivatelù";
$lang_str['th_domain'] = 						"doména";
$lang_str['l_change_privileges'] = 				"Oprávnìní";
$lang_str['ff_domain'] = 						"doména";
$lang_str['ff_realm'] = 						"realm";
$lang_str['th_realm'] = 						"realm";
$lang_str['ff_show_admins_only'] = 				"zobrazit jenom administrátory";
$lang_str['err_cant_ch_priv_of_hostmaster'] = 	"Tento u¾ivatel je hostmaster. Nemù¾ete mìnit oprávnìní hostmastra proto¾e vy sám nejste hostmaster!";


/* ------------------------------------------------------------*/
/*      attribute types                                        */
/* ------------------------------------------------------------*/

$lang_str['fe_not_filled_name_of_attribute'] = 	"musíte vyplnit jméno atributu";
$lang_str['fe_empty_not_allowed'] = 			"nemù¾e být prázdný"; 
$lang_str['ff_order'] = 						"poøadí";
$lang_str['ff_att_name'] = 						"jméno atributu";
$lang_str['ff_att_type'] = 						"typ atributu";
$lang_str['ff_att_access'] = 					"pøístup";
$lang_str['ff_label'] = 						"popisek";
$lang_str['ff_att_group'] = 					"group";	//to translate 
$lang_str['ff_att_uri'] = 						"uri";	//to translate 
$lang_str['ff_att_user'] = 						"u¾ivatelský";
$lang_str['ff_att_domain'] = 					"doménový";
$lang_str['ff_att_global'] = 					"globální";
$lang_str['ff_multivalue'] = 					"vícehodnotový";
$lang_str['ff_att_reg'] = 						"vy¾adovaný pøi registraci";
$lang_str['ff_att_req'] = 						"vy¾adovaný (nesmí být prázdný)";
$lang_str['ff_fr_timer'] = 						"final response timer";	//to translate 
$lang_str['ff_fr_inv_timer'] = 					"final response invite timer";	//to translate 
$lang_str['ff_uid_format'] = 					"formát novì vytváøených UID";
$lang_str['ff_did_format'] = 					"formát novì vytváøených DID";

$lang_str['at_access_0'] = 						"plný pøístup";
$lang_str['at_access_1'] = 						"pro u¾ivatele je pouze pro ètení";
$lang_str['at_access_3'] = 						"pouze pro administrátory (R/W)";


$lang_str['th_att_name'] = 						"jméno atributu";
$lang_str['th_att_type'] = 						"typ atributu";
$lang_str['th_order'] = 						"poøadí";
$lang_str['th_label'] = 						"popisek";
$lang_str['th_att_group'] = 					"group";	//to translate 
$lang_str['fe_order_is_not_number'] = 			"V poli 'poøadí' musí být èíslo";

$lang_str['fe_not_filled_item_label'] = 		"musíte vyplnit jméno polo¾ky";
$lang_str['fe_not_filled_item_value'] = 		"musíte vyplnit hodnotu polo¾ky";
$lang_str['ff_item_label'] = 					"jméno polo¾ky";
$lang_str['ff_item_value'] = 					"hodnota polo¾ky";
$lang_str['th_item_label'] = 					"jméno polo¾ky";
$lang_str['th_item_value'] = 					"hodnota polo¾ky";
$lang_str['l_back_to_editing_attributes'] = 	"zpìt k editaci atributù";
$lang_str['realy_want_you_delete_this_attr'] = 	"Opravdu chcete smazat tento atribut?";
$lang_str['realy_want_you_delete_this_item'] = 	"Opravdu chcete smazat tuto polo¾ku?";


$lang_str['attr_type_warning'] = 				"Na této stránce mù¾ete definovat nové atributy a zmìnit jejich typy, jejich flagy, atd. Pøeddefinované atributy jsou hojnì u¾ívané uvnitø SerWebu nebo SERu. Nemìòte je, pokud si nejste jistí tím co dìláte!!!";	
$lang_str['at_hint_order'] = 					"Atributy jsou v SerWebu uspoøádány v tomto poøadí";
$lang_str['at_hint_label'] = 					"Popisek atributu zobrazovaný v SerWebu. Pokud zaèíná znakem '@', SerWeb se jej pokusí pøelo¾it do jazyka daného u¾ivatele pomocí souborù v adresáøi 'lang'. Je na va¹í zodpovìdnosti aby byly pøísli¹né fráze pøítomné v souborech pro v¹echny jazyky.";
$lang_str['at_hint_for_ser'] = 					"Atribut je nahrávaný SERem. Pouze novì vytvoøené atributy budou ovlivnìné zmìnou tohoto pole.";
$lang_str['at_hint_for_serweb'] = 				"Atribut je nahrávaný SerWebem. Pouze novì vytvoøené atributy budou ovlivnìné zmìnou tohoto pole.";
$lang_str['at_hint_user'] = 					"Atribut je zobrazovaný na stránce u¾ivatelských nastavení";
$lang_str['at_hint_domain'] = 					"Atribut je zobrazovaný na stránce doménových nastavení";
$lang_str['at_hint_global'] = 					"Atribut je zobrazovaný na stránce globálních nastavení";
$lang_str['at_hint_multivalue'] = 				"Atribut mù¾e obsahovat více hodnot";
$lang_str['at_hint_registration'] = 			"Atribut je zobrazovaný na stránce registrace nového u¾ivatele";
$lang_str['at_hint_required'] = 				"Atribut musí mít nìjakou neprázdnou hodnotu. Toto nastavení není zohlednìno u v¹ech typù atributù. Je pou¾íváno napø. u tìchto typù: int, email_adr, sip_adr, atd.";


$lang_str['ff_att_default_value'] = 			"defaultní hodnota";
$lang_str['th_att_default_value'] = 			"defaultní hodnota";
$lang_str['ff_set_as_default'] = 				"nastavit jako defaultní";
$lang_str['edit_items_of_the_list'] = 			"zmìnit seznam polo¾ek";

$lang_str['o_lang_not_selected'] = 				"není vybraný";

$lang_str['at_int_title'] = 					"Change extended settings of int attribute";	//to translate 
$lang_str['ff_at_int_min'] = 					"min value";	//to translate 
$lang_str['ff_at_int_max'] = 					"max value";	//to translate 
$lang_str['ff_at_int_err'] = 					"error message";	//to translate 

$lang_str['ff_at_int_min_hint'] = 				"Minimum allowed value. Leave this field empty to disable check.";	//to translate 
$lang_str['ff_at_int_max_hint'] = 				"Maximum allowed value. Leave this field empty to disable check.";	//to translate 
$lang_str['ff_at_int_err_hint'] = 				"Customize error message displayed when value is not in specified range. Leave this field empty for default error message. If message starts with '@', the string is translated into user language with files in directory 'lang'. It is your responsibility that all used phrases are present in files for all languages.";	//to translate 

$lang_str['err_at_int_range'] = 				"must be in interval %d and %d";	//to translate 
$lang_str['err_at_int_range_min'] = 			"must be great then %d";	//to translate 
$lang_str['err_at_int_range_max'] = 			"must be less then %d";	//to translate 

$lang_str['attr_grp_general'] = 				"general";	//to translate 
$lang_str['attr_grp_privacy'] = 				"privacy";	//to translate 
$lang_str['attr_grp_other'] = 					"other";	//to translate 
$lang_str['err_at_grp_empty'] = 				"Attribute group can't be empty";	//to translate 
$lang_str['attr_grp_create_new'] = 				"create new group";	//to translate 

$lang_str['l_attr_grp_toggle'] = 				"toggle displaying of attribute groups";	//to translate 


/* ------------------------------------------------------------*/
/*      credentials                                            */
/* ------------------------------------------------------------*/


$lang_str['change_credentials_of_user'] = 		"Zmìna kredenciálù u¾ivatele";

$lang_str['th_password'] = 						"heslo";
$lang_str['th_for_ser'] = 						"pro SER";
$lang_str['th_for_serweb'] = 					"pro SerWeb";

$lang_str['err_credential_changed_domain'] = 	"Doména u¾ivatele byla zmìnìna. Musíte také vyplnit nové heslo";
$lang_str['warning_credential_changed_domain'] =		"Serweb je nakonfigurován aby neukládal hesla v èistém textu. To znamená ¾e v pøípadì ¾e zmìníte doménu u¾ivatele, musíte také vyplnit nové heslo. V opaèném pøípadì by za¹ifrované heslo pøestalo platit.";

$lang_str['realy_want_you_delete_this_credential'] = 	"Opravdu chcete smazat tyto kredenciály?";


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

$lang_str['sel_item_all_calls'] = 				"V¹echny hovory";
$lang_str['sel_item_outgoing_calls'] = 			"Jenom odchozí hovory";
$lang_str['sel_item_incoming_cals'] = 			"Jenom pøíchozí hovory";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['fe_no_greeeting_file'] = 			"nevybral jste soubor s pozdravem";
$lang_str['fe_invalid_greeting_file'] = 		"soubor s pozdravem je neplatný";
$lang_str['fe_greeting_file_no_wav'] = 			"typ souboru s pozdravem musí být audio/wav";
$lang_str['fe_greeting_file_too_big'] = 		"soubor s pozdravem je pøíli¹ velký";
$lang_str['msg_greeting_stored_s'] = 			"Pozdrav ulo¾en";
$lang_str['msg_greeting_stored_l'] = 			"Vá¹ pozdrav byl úspì¹nì ulo¾en";
$lang_str['msg_greeting_deleted_s'] = 			"Pozdrav vymazán";
$lang_str['msg_greeting_deleted_l'] = 			"Vá¹ pozdrav byl úspì¹nì vymazán";

/* ------------------------------------------------------------*/
/*      whitelist                                              */
/* ------------------------------------------------------------*/

$lang_str['err_whitelist_already_exists'] = 	"Polo¾ka ji¾ existuje";

/* ------------------------------------------------------------*/
/*      multidomain                                            */
/* ------------------------------------------------------------*/

$lang_str['fe_not_customer_name'] = 			"Musíte vyplnit jméno zákazníka"; 
$lang_str['ff_customer_name'] = 				"jméno zákazníka"; 
$lang_str['no_customers'] = 					"®ádní zákazníci";
$lang_str['customer'] = 						"Zákazník";

$lang_str['msg_customer_updated_s'] = 			"Zákazník aktualizován";
$lang_str['msg_customer_updated_l'] = 			"Jméno zákazníka bylo aktualizováno";
$lang_str['msg_customer_deleted_s'] = 			"Zákazník vymazán";
$lang_str['msg_customer_deleted_l'] = 			"Zákazník byl vymazán";
$lang_str['msg_customer_added_s'] = 			"Zákazník zalo¾en";
$lang_str['msg_customer_added_l'] = 			"Nový zákazník byl zalo¾en";
$lang_str['err_customer_own_domains'] = 		"Zákazník vlastní nìjaké domény, nemù¾ete jej smazat";

$lang_str['d_id'] = 							"ID domény";
$lang_str['d_name'] = 							"jméno domény";
$lang_str['list_of_domains'] = 					"Seznam domén";
$lang_str['showed_domains'] = 					"Zobrazené domény";
$lang_str['no_domains_found'] = 				"®ádné domény nebyly nalezeny";
$lang_str['new_dom_name'] = 					"Pøidat nové doménové jméno";
$lang_str['owner'] = 							"Vlastník";

$lang_str['realy_delete_domain'] = 				"Opravdu chcete smazat tuto doménu?";
$lang_str['l_create_new_domain'] = 				"vytvoøit novou doménu";
$lang_str['l_reload_ser'] = 					"reload SER and web server";	//to translate 
$lang_str['no_domain_name_is_set'] = 			"Vlo¾te nejménì jedno jméno domény";
$lang_str['prohibited_domain_name'] = 			"Toto doménové jméno není povoleno";
$lang_str['can_not_del_last_dom_name'] = 		"Nelze smazat jediné doménové jméno";

$lang_str['msg_domain_reload_s'] = 				"Konfigurace znovu naètena";
$lang_str['msg_domain_reload_l'] = 				"Konfigurace SERu a webového serveru byla znovu naètena";

$lang_str['msg_domain_deleted_s'] = 			"Doména smazána";
$lang_str['msg_domain_deleted_l'] = 			"Tato doména není nadále obsluhována a v¹echny asociované záznamy vèetnì u¾ivatelských dat budou smazány. Ujistìte se ¾e DNS záznamy nadále neukazují na tento server";

$lang_str['assigned_domains'] = 				"Pøiøazené domény";
$lang_str['unassigned_domains'] = 				"Nepøiøazené domény";
$lang_str['l_assign_domain'] = 					"pøiøadit doménu";
$lang_str['l_unassign_domain'] = 				"odebrat doménu";
$lang_str['l_assign'] = 						"pøiøadit";
$lang_str['l_unassign'] = 						"odebrat";
$lang_str['l_assigned_domains'] = 				"Domény";
$lang_str['l_change_layout'] = 					"Rozvr¾ení";
$lang_str['l_domain_attributes'] = 				"Atributy";
$lang_str['l_unassign_admin'] = 				"odebrat administrátora";
$lang_str['l_set_canon'] = 						"nastavit jako kanonické";

$lang_str['admins_of_domain'] = 				"Administrátoøi pro tuto doménu";
$lang_str['no_admins'] = 						"®ádní adminsitrátoøi";

$lang_str['ff_address'] = 						"adresa";

$lang_str['lf_terms_and_conditions'] =			"podmínky a po¾adavky";
$lang_str['lf_mail_register_by_admin'] = 		"text emailu zasílaného u¾ivateli pokud je novì zaregistrován administrátorem";
$lang_str['lf_mail_register'] = 				"text emailu potvrzujícího registraci";
$lang_str['lf_mail_fp_conf'] = 					"text emailu pro potvrzení znovu nastevení hesla poté co jej u¾vatel zapomnìl";
$lang_str['lf_mail_fp_pass'] = 					"text emailu obsahující novì vygenerované heslo";
$lang_str['lf_config'] = 						"konfiguraèní soubor domény";

$lang_str['l_toggle_wysiwyg'] = 				"pøepnout WYSIWYG";	
$lang_str['l_upload_images'] = 					"nahrát obrázky";
$lang_str['l_back_to_default'] = 				"obnovit pùvodní obsah";

$lang_str['wysiwyg_warning'] = 					"Prosím buïte opatrní pøi pou¾ívání WYSIWYG editoru. Soubor prolog.html musí zaèínat elementem &lt;body&gt; a soubor epilog.html musí konèit elementem &lt;/body&gt;. Ale WYSIWYG editor tyto elementy odstraòuje!";

$lang_str['choose_one'] = 						"vyberte";

$lang_str['layout_files'] = 					"Soubory rozv¾ení";
$lang_str['text_files'] = 						"Textové soubory";

$lang_str['fe_domain_not_selected']	= 			"Doména u¾ivatele není vybrána";

$lang_str['th_old_versions'] = 					"Pøedchozí verze tohoto souboru";
$lang_str['initial_ver'] = 						"poèáteèní";


$lang_str['err_cant_run_host_command'] =        "Error when executing 'host' command. Can not check the DNS setting";	//to translate 
$lang_str['err_no_output_of_host_command'] =    "Error when executing 'host' command. There is no output. Can not check the DNS setting";	//to translate 
$lang_str['err_wrong_srv_record'] =             "SRV record(s) found, but it has wrong target host or port. Following SRV records have been found: ";	//to translate 
$lang_str['err_unrecognized_output_of_host'] =  "DNS is not set in correct way. Here is output of 'host' comamnd: ";	//to translate 
$lang_str['err_domain_already_hosted'] = 		"This domain is already hosted on this server";	//to translate 


/* ------------------------------------------------------------*/
/*      wizard - create new domain                             */
/* ------------------------------------------------------------*/

$lang_str['register_new_admin'] = 				"Registrace nového administrátora pro doménu";
$lang_str['assign_existing_admin'] = 			"Pøiøazení existujícího administrátora k doménì";
$lang_str['assign_admin_to_domain'] = 			"Pøiøazení administrátora k doménì";
$lang_str['create_new_domain'] = 				"Vytvoøení nové domény";
$lang_str['l_create_new_customer'] = 			"vytvoøení nového zákazníka";
$lang_str['create_new_customer'] = 				"Vytvoøení nového zákazníka";
$lang_str['l_close_window'] = 					"zavøít toto okno";
$lang_str['step'] = 							"krok";
$lang_str['l_select'] = 						"vybrat";
$lang_str['domain_setup_success'] = 			"Nová doména byla úspì¹nì zalo¾ena!";
$lang_str['l_skip_asignment_of_admin'] = 		"pøeskoèit pøiøazení administrátora";

/* ------------------------------------------------------------*/
/*      wizard - have a domain                                 */
/* ------------------------------------------------------------*/

$lang_str['have_a_domain_head'] = 				"Have-my-domain!";	//to translate 
$lang_str['have_a_domain_introduction'] = 		"On this page you could register your own domain to be hosted on ".$config->domain." server. If you would like to have your domain hosted on ".$config->domain." server you have to set DNS for your domain in proper way first. There have to be a SRV record for service 'SIP' and protocol 'UDP' pointing to host <srv_host> and port <srv_port>.";	//to translate 
$lang_str['have_a_domain_introduction2'] = 		"Register your domain in two steps:";	//to translate 
$lang_str['have_a_domain_step1'] = 				"Check DNS record for your domain";	//to translate 
$lang_str['have_a_domain_step2'] = 				"Create account for administrator of the domain";	//to translate 
$lang_str['have_a_domain_introduction3'] = 		"Check DNS record of your domain by filling the form below.";	//to translate 
$lang_str[''] = 							"";
$lang_str[''] = 							"";
$lang_str[''] = 							"";




/****************************************************/
/* strings which are missing in reference lang file */
/****************************************************/

$lang_str['reg_finish_questions'] = 			"Pokud máte nìjaké dotazy neostýchejte se nám napsat";
$lang_str['tab_caller_screening'] =	 			"filtrování volajících";
$lang_str['msg_caller_screening_deleted_s'] = 	"Adresa vymazána";
$lang_str['msg_caller_screening_deleted_l'] = 	"Adresa byla vymazána ze seznamu filtrovaných adres";
$lang_str['msg_caller_screening_updated_s'] = 	"Adresa zmìnìna";
$lang_str['msg_caller_screening_updated_l'] = 	"Filtrovaná adresa byla zmìnìna";
$lang_str['msg_caller_screening_added_s'] = 	"Adresa pøidána";
$lang_str['msg_caller_screening_added_l'] = 	"Adresa byla pøidána do seznamu filtrovaných adres";
$lang_str['fe_not_caller_uri'] = 				"musíte vyplnit adresu volajícího";
$lang_str['ff_screening_caller_uri'] = 			"adresa volajícího (regulární výraz)";
$lang_str['ff_action'] = 						"akce";
$lang_str['th_caller_uri'] = 					"adresa volajícího";
$lang_str['th_action'] = 						"akce";
$lang_str['no_caller_screenings_defined'] = 	"¾ádné filtrování volajících není definováno";
$lang_str['err_screening_already_exists'] = 	"záznam s touto adresou volajícího u¾ existuje";
$lang_str['cs_decline'] = 						"odmítnout";
$lang_str['cs_reply_busy'] = 					"odpovìdìt: jsem zaneprázdnìn";
$lang_str['cs_fw_to_voicemail'] = 				"pøesmìrovat do hlasové schránky";
$lang_str['tab_ser_moni'] =	 					"monitorování serveru";
$lang_str['err_reg_conf_already_created'] = 	"Vá¹ úèet ji¾ byl vytvoøen";
$lang_str['ser_moni_current'] = 				"aktuálnì";
$lang_str['ser_moni_average'] = 				"prùmìrnì";
$lang_str['ser_moni_waiting_cur'] = 			"aktuálnì èekajících";
$lang_str['ser_moni_waiting_avg'] = 			"prùmìrnì èekajících";
$lang_str['ser_moni_total_cur'] = 				"aktuální souhrn";
$lang_str['ser_moni_total_avg'] = 				"prùmìrný souhrn";
$lang_str['ser_moni_local_cur'] = 				"aktuálnì lokálních";
$lang_str['ser_moni_local_avg'] = 				"prùmìrnì lokálních";
$lang_str['ser_moni_replies_cur'] = 			"akt. lokál. odpovìdí";
$lang_str['ser_moni_replies_avg'] = 			"prùm. lokál. odpovìdí";
$lang_str['ser_moni_registered_cur'] = 			"akt. registrováno";
$lang_str['ser_moni_registered_avg'] = 			"prùm. registrováno";
$lang_str['ser_moni_expired_cur'] = 			"aktuálnì vypr¹ených";
$lang_str['ser_moni_expired_avg'] = 			"prùmìrnì vypr¹ených";
$lang_str['ser_moni_general_values'] = 			"obecné hodnoty";
$lang_str['ser_moni_diferencial_values'] = 		"rozdílové hodnoty";
$lang_str['ser_moni_transaction_statistics'] = 	"Transakèní statistiky";
$lang_str['ser_moni_completion_status'] = 		"Stav dokonèení";
$lang_str['ser_moni_stateless_server_statis'] = "Statistiky bezestavového serveru";
$lang_str['ser_moni_usrLoc_stats'] = 			"Statistiky UsrLoc";
$lang_str['l_domain_preferences'] = 			"doménové nastavení";
?>
