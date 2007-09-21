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

$lang_str['user_management'] = 					"U�ivatelsk� rozhran�";
$lang_str['admin_interface'] = 					"Administr�torsk� rozhran�";
$lang_str['user'] = 							"u�ivatel";
$lang_str['from'] = 							"z";
$lang_str['no_records'] = 						"��dn� z�znamy";
$lang_str['l_logout'] = 						"Odhl�en�";
$lang_str['l_edit'] = 							"zm�nit";
$lang_str['l_extended'] = 						"extended";	//to translate 
$lang_str['l_change'] = 						"zm�nit";
$lang_str['l_delete'] = 						"smazat";
$lang_str['l_back_to_main'] = 					"zp�t na hlavn� str�nku";
$lang_str['l_back'] = 							"zp�t";
$lang_str['l_disable'] = 						"znep��stupnit";
$lang_str['l_enable'] = 						"zp��stupnit";
$lang_str['l_disable_all'] = 					"znep��stupnit v�e";
$lang_str['l_enable_all'] = 					"zp��stupnit v�e";
$lang_str['status_unknown'] = 					"nezn�m�";
$lang_str['status_nonlocal'] = 					"nelok�ln�";
$lang_str['status_nonexists'] = 				"neexistuj�c�";
$lang_str['status_online'] = 					"on line";
$lang_str['status_offline'] = 					"off line";
$lang_str['search_filter'] = 					"filtr";
$lang_str['showed_users'] = 					"Zobrazen� u�ivatel�";
$lang_str['displaying_records'] = 				"Zobrazen� z�znamy";
$lang_str['no_users_found'] = 					"��dn� u�ivatel� nenalezeni";
$lang_str['no_records_found'] = 				"��dn� z�znamy nebyly nalezeny";
$lang_str['none'] = 							"��dn�";	
$lang_str['warning'] = 							"Varov�n�!";
$lang_str['domain'] = 							"tabulka";
$lang_str['yes'] = 								"ANO";	
$lang_str['no'] = 								"NE";	
$lang_str['not_exists'] = 						"neexistuje";

/* ------------------------------------------------------------*/
/*      error messages                                         */
/* ------------------------------------------------------------*/

$lang_str['fe_not_valid_email'] =	 			"neplatn� emailov� adresa";
$lang_str['fe_is_not_valid_email'] =	 		"nen� platn� emailov� adresa";
$lang_str['fe_not_valid_sip'] = 				"neplatn� SIP adresa";
$lang_str['fe_not_valid_phonenumber'] = 		"neplatn� telefonn� ��slo";
$lang_str['fe_not_filled_sip'] = 				"SIP adresu mus�te vyplnit";
$lang_str['fe_passwords_not_match'] =			"hesla si neodpov�daj�";
$lang_str['fe_not_filled_username'] = 			"u�ivatelsk� jm�no mus�te vyplnit";
$lang_str['fe_not_allowed_uri'] = 				"Nepovolen� SIP adresa";
$lang_str['fe_max_entries_reached'] = 			"Dosa�en maxim�ln� povolen� po�et polo�ek";
$lang_str['fe_not_valid_username'] = 			"neplatn� u�ivatelsk� jm�no";
$lang_str['fe_not_valid_domainname'] = 			"neplatn� dom�nov� jm�no";

/* ------------------------------------------------------------*/
/*      buttons                                                */
/* ------------------------------------------------------------*/

$lang_str['b_add'] =		 					"P�idat";
$lang_str['b_back'] =		 					"Zp�t";
$lang_str['b_delete_calls'] =		 			"Vymazat hovory";
$lang_str['b_dial_your_voicemail'] =		 	"Vyto�it hlasovou schr�nku";
$lang_str['b_download_greeting'] =		 		"P�ehr�t V� pozdrav";
$lang_str['b_edit_items_of_the_list'] =		 	"Zm�nit polo�ky seznamu";
$lang_str['b_find'] = 							"Vyhledat";
$lang_str['b_forgot_pass_submit'] = 			"Zjistit heslo";
$lang_str['b_login'] =		 					"P�ihl�sit";
$lang_str['b_next'] =		 					"Dal��";
$lang_str['b_register'] = 						"Registrovat se";
$lang_str['b_send'] =		 					"Odeslat";
$lang_str['b_submit'] =		 					"Ulo�it";
$lang_str['b_cancel'] =		 					"Zru�it";
$lang_str['b_select'] =		 					"Vyber";
$lang_str['b_test_firewall_NAT'] =		 		"Otestovat firewall/NAT";
$lang_str['b_upload_greeting'] =		 		"Nahr�t nov� pozdrav";
$lang_str['b_extended_settings'] =		 		"Roz���en� nastaven�";
$lang_str['b_search'] =		 					"Hledej";


/* ------------------------------------------------------------*/
/*      tabs                                                   */
/* ------------------------------------------------------------*/

$lang_str['tab_my_account'] =		 			"m�j ��et";
$lang_str['tab_phonebook'] =		 			"telefonn� seznam";
$lang_str['tab_missed_calls'] =	 				"zme�kan� hovory";
$lang_str['tab_accounting'] =	 				"p�ehled vol�n�";
$lang_str['tab_send_im'] =	 					"poslat zpr�vu";
$lang_str['tab_message_store'] =	 			"ulo�en� zpr�vy";
$lang_str['tab_voicemail'] =	 				"hlasov� schr�nka";
$lang_str['tab_user_preferences'] =	 			"nastaven�";
$lang_str['tab_speed_dial'] =	 				"rychl� volba";

$lang_str['tab_users'] =	 					"u�ivatel�";
$lang_str['tab_admin_privileges'] =	 			"opr�vn�n� spr�vc�";
$lang_str['tab_domains'] =	 					"dom�ny";
$lang_str['tab_customers'] =	 				"z�kazn�ci";
$lang_str['tab_global_attributes'] =	 		"glob�ln� atributy";
$lang_str['tab_attr_types'] =	 				"typy atribut�";

/* ------------------------------------------------------------*/
/*      form fields                                            */
/* ------------------------------------------------------------*/

$lang_str['ff_first_name'] = 					"jm�no";
$lang_str['ff_last_name'] = 					"p��jmen�";
$lang_str['ff_sip_address'] = 					"SIP adresa";
$lang_str['ff_your_timezone'] = 				"�asov� z�na";
$lang_str['ff_username'] = 						"u�ivatelsk� jm�no";
$lang_str['ff_email'] = 						"email";
$lang_str['ff_show_online_only'] = 				"zobraz jenom on-line u�ivatele";
$lang_str['ff_language'] = 						"jazyk";
$lang_str['ff_reg_confirmation'] = 				"vy�adovat potvrzen� registrace";
$lang_str['ff_uid'] = 							"uid";
$lang_str['ff_for_ser'] = 						"pro SER";
$lang_str['ff_for_serweb'] = 					"pro SerWeb";
$lang_str['ff_contact_email'] = 				"kontaktn� email";

/* ------------------------------------------------------------*/
/*      table heading                                          */
/* ------------------------------------------------------------*/

$lang_str['th_name'] = 							"jm�no";
$lang_str['th_sip_address'] = 					"SIP adresa";
$lang_str['th_aliases'] = 						"alias";
$lang_str['th_status'] = 						"stav";
$lang_str['th_timezone'] = 						"�asov� z�na";
$lang_str['th_calling_subscriber'] = 			"volaj�c�";
$lang_str['th_time'] = 							"�as";
$lang_str['th_username'] = 						"u�ivatelsk� jm�no";
$lang_str['th_email'] = 						"email";
$lang_str['th_uid'] = 							"uid";

/* ------------------------------------------------------------*/
/*      login messages                                         */
/* ------------------------------------------------------------*/

$lang_str['bad_username'] = 					"Chybn� u�ivatelsk� jm�no nebo heslo";
$lang_str['account_disabled'] = 				"V� ��et byl znep��stupn�n";
$lang_str['domain_not_found'] = 				"Va�e dom�na nebyla nalezena";
$lang_str['msg_logout_s'] = 					"Odhl�en";
$lang_str['msg_logout_l'] = 					"Byl jste odhl�en. Pro nov� p�ihl�en� vypl�te u�ivatelsk� jm�no a heslo";
$lang_str['userlogin'] = 						"p�ihl�en� u�ivatele";
$lang_str['adminlogin'] = 						"p�ihl�en� administr�tora";
$lang_str['enter_username_and_passw'] = 		"Pros�m vypl�te va�e u�ivatelsk� jm�no a heslo";
$lang_str['ff_password'] = 						"heslo";
$lang_str['l_forgot_passw'] = 					"Zapom�li jste heslo?";
$lang_str['l_register'] = 						"Registrace!";
$lang_str['l_have_my_domain'] = 				"Have-my-domain!";	//to translate 
$lang_str['remember_uname'] = 					"Zapamatuj si m� u�ivatelsk� jm�no na tomto po��ta�i";
$lang_str['session_expired'] = 					"Relace vypr�ela";
$lang_str['session_expired_relogin'] = 			"Va�e relace vypr�ela, pros�m p�ihla�te se znovu.";

/* ------------------------------------------------------------*/
/*      my account                                             */
/* ------------------------------------------------------------*/

$lang_str['msg_changes_saved_s'] = 				"Zm�ny ulo�eny";
$lang_str['msg_changes_saved_l'] = 				"Va�e zm�ny byly ulo�eny";
$lang_str['msg_loc_contact_deleted_s'] = 		"Kontakt vymaz�n";
$lang_str['msg_loc_contact_deleted_l'] = 		"Kontakt byl vymaz�n";
$lang_str['msg_loc_contact_added_s'] = 			"Kontakt p�id�n";
$lang_str['msg_loc_contact_added_l'] = 			"Kontakt byl p�id�n";
$lang_str['ff_your_email'] = 					"v� email";
$lang_str['ff_fwd_to_voicemail'] = 				"p�esm�rov�n� do hlasov� schr�nky";
$lang_str['ff_allow_lookup_for_me'] = 			"umo�nit ostatn�m vyhledat moj� SIP adresu";
$lang_str['ff_status_visibility'] = 			"umo�nit ostatn�m zjistit zda-li jsem on-line";
$lang_str['ff_your_password'] = 				"va�e heslo";
$lang_str['ff_retype_password'] = 				"heslo pro kontrolu";
$lang_str['your_aliases'] = 					"va�e aliasy";
$lang_str['your_acl'] = 						"vol�n� povoleno do";
$lang_str['th_contact'] = 						"kontakt";
$lang_str['th_expires'] = 						"vypr�� za";
$lang_str['th_priority'] = 						"priorita";
$lang_str['th_location'] = 						"um�st�n�";
$lang_str['add_new_contact'] = 					"p�idat nov� kontakt";
$lang_str['ff_expires'] = 						"vypr�� za";
$lang_str['contact_expire_hour'] = 				"1 hodinu";
$lang_str['contact_expire_day'] = 				"1 den";
$lang_str['contact_will_not_expire'] = 			"nikdy";
$lang_str['acl_err_local_forward'] = 			"lok�ln� p�esm�rov�n� je zak�z�no";
$lang_str['acl_err_gateway_forward'] = 			"p�esm�rov�n� na br�nu je zak�z�no";

/* ------------------------------------------------------------*/
/*      phonebook                                              */
/* ------------------------------------------------------------*/

$lang_str['msg_pb_contact_deleted_s'] = 		"Kontakt vymaz�n";
$lang_str['msg_pb_contact_deleted_l'] = 		"Kontakt byl vymaz�n z telefonn�ho seznamu";
$lang_str['msg_pb_contact_updated_s'] = 		"Kontakt aktualizov�n";
$lang_str['msg_pb_contact_updated_l'] = 		"Zm�ny byly ulo�eny";
$lang_str['msg_pb_contact_added_s'] = 			"Kontakt p�id�n";
$lang_str['msg_pb_contact_added_l'] = 			"Kontakt byl p�id�n do telefonn�ho seznamu";
$lang_str['phonebook_records'] = 				"Zobrazeny kontakty";
$lang_str['l_find_user'] = 						"Vyhled�v�n� u�ivatel�";

/* ------------------------------------------------------------*/
/*      find user                                              */
/* ------------------------------------------------------------*/

$lang_str['find_user'] = 						"Najdi u�ivatele";
$lang_str['l_add_to_phonebook'] = 				"p�idej do telefonn�ho seznamu";
$lang_str['l_back_to_phonebook'] = 				"zp�t do telefonn�ho seznamu";
$lang_str['found_users'] = 						"U�ivatel�";

/* ------------------------------------------------------------*/
/*      missed calls                                           */
/* ------------------------------------------------------------*/

$lang_str['th_reply_status'] = 					"odpov��";
$lang_str['missed_calls'] = 					"zme�kan� hovory";
$lang_str['no_missed_calls'] = 					"��dn� zme�kan� hovory";

/* ------------------------------------------------------------*/
/*      accounting                                             */
/* ------------------------------------------------------------*/

$lang_str['th_destination'] = 					"volan�";
$lang_str['th_length_of_call'] = 				"d�lka hovoru";
$lang_str['th_hangup'] = 						"zav�sil";
$lang_str['calls_count'] = 						"Zobrazeny hovory";
$lang_str['no_calls'] = 						"��dn� hovory";
$lang_str['msg_calls_deleted_s'] = 				"Hovory vymaz�ny";
$lang_str['msg_calls_deleted_l'] = 				"Hovory byly �sp�n� vymaz�ny z datab�ze";


/* ------------------------------------------------------------*/
/*      send IM                                                */
/* ------------------------------------------------------------*/

$lang_str['fe_no_im'] = 						"nenapsal jste ��dnou zpr�vu";
$lang_str['fe_im_too_long'] = 					"zpr�va je p��li� dlouh�";
$lang_str['msg_im_send_s'] = 					"Zpr�va odesl�na";
$lang_str['msg_im_send_l'] = 					"Zpr�va byla usp�n� odesl�na na adresu";
$lang_str['max_length_of_im'] = 				"Maxim�ln� d�lka zpr�vy je";
$lang_str['sending_message'] = 					"pos�l�m zpr�vu";
$lang_str['please_wait'] = 						"pros�m �ekejte!";
$lang_str['ff_sip_address_of_recipient'] = 		"SIP adresa p��jemce";
$lang_str['ff_text_of_message'] = 				"text zpr�vy";
$lang_str['im_remaining'] = 					"Zb�v�";
$lang_str['im_characters'] = 					"znak�";


/* ------------------------------------------------------------*/
/*      message store                                          */
/* ------------------------------------------------------------*/

$lang_str['instant_messages_store'] = 			"Ulo�en� textov� zpr�vy";
$lang_str['voicemail_messages_store'] = 		"Ulo�en� hlasov� zpr�vy";
$lang_str['no_stored_instant_messages'] = 		"Nejsou ulo�eny ��dn� textov� zpr�vy";
$lang_str['no_stored_voicemail_messages'] = 	"Nejsou ulo�eny ��dn� hlasov� zpr�vy";
$lang_str['th_subject'] = 						"p�edm�t";
$lang_str['l_reply'] = 							"odpov�d�t";
$lang_str['err_can_not_open_message'] = 		"Nelze otev��t zpr�vu";
$lang_str['err_voice_msg_not_found'] = 			"Zpr�va nenalezena nebo nem�te p��stup k p�e�ten� zpr�vy";
$lang_str['msg_im_deleted_s'] = 				"Zpr�va vymaz�na";
$lang_str['msg_im_deleted_l'] = 				"Zpr�va byla �sp�n� vymaz�na";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['customize_greetings'] = 				"P�izp�sobit pozdrav";
$lang_str['err_can_not_open_greeting'] = 		"Nelze otev��t pozdrav";

/* ------------------------------------------------------------*/
/*      attributes                                             */
/* ------------------------------------------------------------*/

$lang_str['fe_invalid_value_of_attribute'] = 	"neplatn� hodnota";
$lang_str['fe_is_not_number'] = 				"nen� platn� ��slo";
$lang_str['fe_is_not_sip_adr'] = 				"nen� platn� SIP adresa";
$lang_str['no_attributes_defined'] = 			"��dn� nastaven� nen� povoleno administr�torem";

$lang_str['ff_send_daily_missed_calls'] =		"pos�lejte mi denn� seznam zme�kan�ch hovor� na email";

$lang_str['ff_uri_def_f'] =						"defaultn� flagy pro uri";
$lang_str['ff_credential_def_f'] =				"defaultn� flagy pro kredenci�ly";
$lang_str['ff_domain_def_f'] =					"defaultn� flagy pro dom�ny";


/* ------------------------------------------------------------*/
/*      speed dial                                             */
/* ------------------------------------------------------------*/

$lang_str['th_speed_dial'] = 					"zkr�cen� volba";
$lang_str['th_new_uri'] = 						"nov� SIP adresa";




/* ------------------------------------------------------------*/
/*      registration                                           */
/* ------------------------------------------------------------*/

$lang_str['fe_not_accepted_terms'] = 			"Nep�ijal jste na�e podm�nky a po�adavky";
$lang_str['choose_timezone'] = 					"--- pros�m vyberte va�i �asovou z�nu ---";
$lang_str['choose_timezone_of_user'] = 			"--- pros�m vyberte �asovou z�nu u�ivatele ---";
$lang_str['fe_not_choosed_timezone'] = 			"vyberte va�i �asovou z�nu";
$lang_str['fe_uname_not_follow_conventions'] = 	"u�ivatelsk� jm�no neodpov�d� doporu�ovan�m konvenc�m";
$lang_str['fe_not_filled_password'] = 			"mus�te vyplnit heslo";
$lang_str['fe_not_filled_your_fname'] = 		"mus�te vyplnit k�estn� jm�no";
$lang_str['fe_not_filled_your_lname'] = 		"mus�te vyplnit p��jmen�";
$lang_str['fe_uname_already_choosen_1'] = 		"Promi�te, u�ivatelsk� jm�no";
$lang_str['fe_uname_already_choosen_2'] = 		"u� bylo vybr�no n�k�m jin�m. Zkuste n�jak� jin�.";
$lang_str['err_sending_mail'] = 				"Promi�te, do�lo k chyb� p�i odes�l�n� registra�n�ho emailu.";
$lang_str['registration_introduction_1'] = 		"Pros�m vypl�te formul�� pro registraci a klikn�te na tla��tko 'Registrovat se'. Bude v�m zasl�n email potvrzuj�c� va�� registraci. Pros�m napi�te n�m na adresu";
$lang_str['registration_introduction_2'] = 		"pokud m�te n�jak� dotazy ohledn� registrace nebo na�ich slu�eb.";
$lang_str['reg_email_desc'] = 					"Adresa na kterou bude odesl�no potvrzen� o registraci. (Pokud vypln�te neplatnou adresu, ��dn� potvrzen� nedostanete a ��et v�m nebude vytvo�en.)";
$lang_str['reg_email_uname_desc'] = 			"Your SIP address will be same as your email address. Subscription confirmation request will be sent to this address. (If an invalid address is given, no confirmation will be sent and no SIP account will be created.) Your email address have to be from domain ".$config->domain.".";	//to translate 
$lang_str['ff_phone'] = 						"telefonn� ��slo";
$lang_str['reg_phone_desc'] = 					"Telefonn� ��slo na kter�m jste k zasti�en�.";
$lang_str['ff_pick_username'] = 				"zvolte si u�ivatelsk� jm�no";
$lang_str['reg_username_desc'] = 				"Va�e SIP adresa bude: u�ivatelsk�_jm�no@".$config->domain.". U�ivatelsk� jm�no m��e b�t bu� numerick� za��naj�c� '8' (nap�., '8910') nebo alfanumerick� ps�no mal�mi p�smeny za��naj�c� p�smenem (nap�., john.doe01). Nezapome�te va�e u�ivatelsk� jm�no -- budete ho pot�ebovat k nastaven� va�eho telefonu!";
$lang_str['ff_pick_password'] = 				"zvolte si heslo";
$lang_str['reg_password_desc'] = 				"Nezapme�te va�e heslo -- budete ho pot�ebovat k nastaven� va�eho telefonu!";
$lang_str['ff_confirm_password'] = 				"heslo znovu pro potvrzen�";
$lang_str['ff_terms_and_conditions'] = 			"podm�nky a po�adavky";
$lang_str['ff_i_accept'] = 						"souhlas�m";
$lang_str['ff_timezone'] = 						"�asov� z�na";
$lang_str['ff_uname_assign_mode'] =             "Username assignment mode";	//to translate 
$lang_str['l_back_to_loginform'] = 				"Zp�t na p�ihla�ovac� str�nku";
$lang_str['msg_user_registered_s'] = 			"U�ivatel zaregistrov�n";
$lang_str['msg_user_registered_l'] = 			"Nov� u�ivatel byl �sp�n� zaregistrov�n";
$lang_str['register_new_user'] = 				"zaregistrovat nov�ho u�ivatele";
$lang_str["err_domain_of_email_not_match"] =    "Your email address is not from same domain as into which you are registering";

/* ------------------------------------------------------------*/
/*      registration - finished                                */
/* ------------------------------------------------------------*/

$lang_str['reg_finish_thanks'] = 				"D�kujeme za registraci v ".$config->domain;
$lang_str['reg_finish_app_forwarded'] = 		"Va�e ��dost byla odesl�na ke schv�len�.";
$lang_str['reg_finish_confirm_msg'] = 			"O�ek�vejte potvrzuj�c� zpr�vu v kr�tk� dob�..";
$lang_str['reg_finish_sip_address'] = 			"Rezervujeme pro V�s tuto SIP adresu:";
$lang_str['reg_finish_questions_1'] = 			"Pokud m�te n�jak� dal�� dotazy, po�lete n�m";
$lang_str['reg_finish_questions_2'] = 			"email na adresu";

/* ------------------------------------------------------------*/
/*      registration - confirmation                            */
/* ------------------------------------------------------------*/

$lang_str['reg_conf_congratulations'] = 		"Gratulujeme! V� ".$config->domain." ��et je p�ipraven!";
$lang_str['reg_conf_set_up'] = 					"V� ".$config->domain." ��et je p�ipraven!";
$lang_str['reg_conf_jabber_failed'] = 			"Ale va�e registrace v Jabber br�n� ".$config->domain." selhala.";
$lang_str['reg_conf_contact_infomail_1'] = 		"Pros�m kontaktujte";
$lang_str['reg_conf_contact_infomail_2'] = 		"pro dal�� podporu.";
$lang_str['reg_conf_failed'] = 					"Omlouv�me se ale v� pokus o potvrzen� selhal.";
$lang_str['reg_conf_nr_not_exists'] = 			"Bu� je �patn� potvrzovac� ��slo nebo ji� va�e konto bylo vytvo�eno!";	
$lang_str['err_reg_conf_not_exists_conf_num'] = "Litujeme. Toto potvrzovac� ��slo neexistuje";

/* ------------------------------------------------------------*/
/*      registration - forgot password                         */
/* ------------------------------------------------------------*/

$lang_str['forgot_pass_head'] = 				"Zapom�l jste heslo?";
$lang_str['forgot_pass_introduction'] = 		"Jestli�e jste zapom�l va�e heslo, pros�m vypl�te n�e va�e heslo. Email obsahuj�c� va�e heslo bude odesl�n na emailovou adresu kterou jste zadal p�i registraci!";
$lang_str['forgot_pass_sended'] = 				"Nov� heslo bylo vytvo�eno a odesl�no na emailovou adresu kterou jste zadal p�i registraci.";
$lang_str['msg_pass_conf_sended_s'] = 			"Informace pro p�ihl�en� odesl�ny";
$lang_str['msg_pass_conf_sended_l'] = 			"Informace pro p�ihl�en� byly odesl�ny na va�� emailovou adresu";
$lang_str['msg_password_sended_s'] = 			"Nov� heslo odesl�no";
$lang_str['msg_password_sended_l'] = 			"Nov� heslo bylo odesl�no na va�� emailovou adresu";
$lang_str['err_no_user'] = 						"Litujeme, to u�ivatelsk� jm�no nen� registrovan�!";

/* ------------------------------------------------------------*/
/*      admin - users management                               */
/* ------------------------------------------------------------*/

$lang_str['err_admin_can_not_delete_user_1'] = 	"Nem��ete vymazat tohoto u�ivatele";
$lang_str['err_admin_can_not_delete_user_2'] = 	"tento u�ivatel pot�� do jin� dom�ny";
$lang_str['msg_acl_updated_s'] = 				"ACL aktualizov�n";	
$lang_str['msg_acl_updated_l'] = 				"Access control list byl aktualizov�n";
$lang_str['msg_user_deleted_s'] = 				"U�ivatel vymaz�n";
$lang_str['msg_user_deleted_l'] = 				"U�ivatel byl �sp�n� vymaz�n";
$lang_str['th_phone'] = 						"telefon";
$lang_str['l_acl'] = 							"ACL";
$lang_str['l_aliases'] = 						"aliasy";
$lang_str['l_account'] = 						"��et";
$lang_str['l_accounting'] = 					"p�ehled vol�n�";
$lang_str['realy_you_want_delete_this_user'] =	"Opravdu chcete smazat tohoto u�ivatele?";
$lang_str['l_credentials'] = 					"kredenci�ly";
$lang_str['user_has_no_credentials'] = 			"U�ivatel nem� ��dn� kredenci�ly";

/* ------------------------------------------------------------*/
/*      admin - ACL, aliases                                   */
/* ------------------------------------------------------------*/

$lang_str['access_control_list_of_user'] = 		"Access control list u�ivatele";
$lang_str['have_not_privileges_to_acl'] = 		"Nem�te opr�vn�n� ke zm�n� ACL";
$lang_str['err_alias_already_exists_1'] = 		"alias:";
$lang_str['err_alias_already_exists_2'] = 		"u� existuje";
$lang_str['msg_alias_deleted_s'] = 				"Alias vymaz�n";
$lang_str['msg_alias_deleted_l'] = 				"Alias u�ivatele byl vymaz�n";
$lang_str['msg_alias_updated_s'] = 				"Alias aktualizov�n";
$lang_str['msg_alias_updated_l'] = 				"Va�e zm�ny byly ulo�eny";
$lang_str['msg_alias_added_s'] = 				"Alias p�id�n";
$lang_str['msg_alias_added_l'] = 				"Alias byl p�id�n u�ivateli";
$lang_str['change_aliases_of_user'] = 			"Zm�na alias� u�ivatele";
$lang_str['ff_alias'] = 						"alias";
$lang_str['th_alias'] = 						"alias";
$lang_str['realy_you_want_delete_this_alias'] = "Opravdu chcete smazat tento alias?";
$lang_str['user_have_not_any_aliases'] = 		"U�ivatel nem� ��dn� aliasy";
$lang_str['ff_is_canon'] = 						"je kanonick�"; 
$lang_str['ff_is_enabled'] = 					"je zp��stupn�n�"; 
$lang_str['ff_uri_is_to'] = 					"m��e b�t pou�ito jako 'to' uri";
$lang_str['ff_uri_is_from'] = 					"m��e b�t pou�ito jako 'from' uri";
$lang_str['th_is_canon'] = 						"kanonick�";
$lang_str['th_uri_is_to'] = 					"to";
$lang_str['th_uri_is_from'] = 					"from";
$lang_str['l_ack'] = 							"potvrdit";
$lang_str['l_deny'] = 							"zam�tnout";
$lang_str['uris_with_same_uname_did'] = 		"existuje URI kter� m� stejn� u�ivatelsk� jm�no a dom�nu";
$lang_str['ack_values'] = 						"Potvr�te hodnoty";
$lang_str['uri_already_exists'] = 				"URI s vybran�m u�ivatelsk�m jm�nem a dom�nou ji� existuje. Pros�m potvr�te zadan� hodnoty.";
$lang_str['is_to_warning'] = 					"VAROV�N�: flag 'IS TO' je nastaven pro jin� URI. Pokud budete pokra�ovat, tento flag bude smaz�n ve zm�n�n�m URI";
$lang_str['err_canon_uri_exists'] = 			"Nelze nastavit URI jako kanonick� proto�e existuje jin� kanonick� URI kter� nem��ete m�nit";
$lang_str['uid_with_alias'] = 					"Seznam UID s aliasem";	

/* ------------------------------------------------------------*/
/*      admin privileges                                       */
/* ------------------------------------------------------------*/

$lang_str['admin_privileges_of'] = 				"Opr�vn�n� administr�tora ";
$lang_str['admin_competence'] = 				"p�sobnost administr�tora";
$lang_str['ff_is_admin'] = 						"je administr�tor";
$lang_str['ff_change_privileges'] = 			"zm�na opr�vn�n� jin�ch administr�tor�";
$lang_str['ff_is_hostmaster'] = 				"je hostmaster"; 
$lang_str['acl_control'] = 						"zm�ny ACL";
$lang_str['msg_privileges_updated_s'] = 		"Opr�vn�n� aktualizov�ny";
$lang_str['msg_privileges_updated_l'] = 		"Opr�vn�n� u�ivatele byly aktualizov�ny";
$lang_str['list_of_users'] = 					"Seznam u�ivatel�";
$lang_str['th_domain'] = 						"dom�na";
$lang_str['l_change_privileges'] = 				"Opr�vn�n�";
$lang_str['ff_domain'] = 						"dom�na";
$lang_str['ff_realm'] = 						"realm";
$lang_str['th_realm'] = 						"realm";
$lang_str['ff_show_admins_only'] = 				"zobrazit jenom administr�tory";
$lang_str['err_cant_ch_priv_of_hostmaster'] = 	"Tento u�ivatel je hostmaster. Nem��ete m�nit opr�vn�n� hostmastra proto�e vy s�m nejste hostmaster!";


/* ------------------------------------------------------------*/
/*      attribute types                                        */
/* ------------------------------------------------------------*/

$lang_str['fe_not_filled_name_of_attribute'] = 	"mus�te vyplnit jm�no atributu";
$lang_str['fe_empty_not_allowed'] = 			"nem��e b�t pr�zdn�"; 
$lang_str['ff_order'] = 						"po�ad�";
$lang_str['ff_att_name'] = 						"jm�no atributu";
$lang_str['ff_att_type'] = 						"typ atributu";
$lang_str['ff_att_access'] = 					"p��stup";
$lang_str['ff_label'] = 						"popisek";
$lang_str['ff_att_group'] = 					"group";	//to translate 
$lang_str['ff_att_uri'] = 						"uri";	//to translate 
$lang_str['ff_att_user'] = 						"u�ivatelsk�";
$lang_str['ff_att_domain'] = 					"dom�nov�";
$lang_str['ff_att_global'] = 					"glob�ln�";
$lang_str['ff_multivalue'] = 					"v�cehodnotov�";
$lang_str['ff_att_reg'] = 						"vy�adovan� p�i registraci";
$lang_str['ff_att_req'] = 						"vy�adovan� (nesm� b�t pr�zdn�)";
$lang_str['ff_fr_timer'] = 						"final response timer";	//to translate 
$lang_str['ff_fr_inv_timer'] = 					"final response invite timer";	//to translate 
$lang_str['ff_uid_format'] = 					"form�t nov� vytv��en�ch UID";
$lang_str['ff_did_format'] = 					"form�t nov� vytv��en�ch DID";

$lang_str['at_access_0'] = 						"pln� p��stup";
$lang_str['at_access_1'] = 						"pro u�ivatele je pouze pro �ten�";
$lang_str['at_access_3'] = 						"pouze pro administr�tory (R/W)";


$lang_str['th_att_name'] = 						"jm�no atributu";
$lang_str['th_att_type'] = 						"typ atributu";
$lang_str['th_order'] = 						"po�ad�";
$lang_str['th_label'] = 						"popisek";
$lang_str['th_att_group'] = 					"group";	//to translate 
$lang_str['fe_order_is_not_number'] = 			"V poli 'po�ad�' mus� b�t ��slo";

$lang_str['fe_not_filled_item_label'] = 		"mus�te vyplnit jm�no polo�ky";
$lang_str['fe_not_filled_item_value'] = 		"mus�te vyplnit hodnotu polo�ky";
$lang_str['ff_item_label'] = 					"jm�no polo�ky";
$lang_str['ff_item_value'] = 					"hodnota polo�ky";
$lang_str['th_item_label'] = 					"jm�no polo�ky";
$lang_str['th_item_value'] = 					"hodnota polo�ky";
$lang_str['l_back_to_editing_attributes'] = 	"zp�t k editaci atribut�";
$lang_str['realy_want_you_delete_this_attr'] = 	"Opravdu chcete smazat tento atribut?";
$lang_str['realy_want_you_delete_this_item'] = 	"Opravdu chcete smazat tuto polo�ku?";


$lang_str['attr_type_warning'] = 				"Na t�to str�nce m��ete definovat nov� atributy a zm�nit jejich typy, jejich flagy, atd. P�eddefinovan� atributy jsou hojn� u��van� uvnit� SerWebu nebo SERu. Nem��te je, pokud si nejste jist� t�m co d�l�te!!!";	
$lang_str['at_hint_order'] = 					"Atributy jsou v SerWebu uspo��d�ny v tomto po�ad�";
$lang_str['at_hint_label'] = 					"Popisek atributu zobrazovan� v SerWebu. Pokud za��n� znakem '@', SerWeb se jej pokus� p�elo�it do jazyka dan�ho u�ivatele pomoc� soubor� v adres��i 'lang'. Je na va�� zodpov�dnosti aby byly p��sli�n� fr�ze p��tomn� v souborech pro v�echny jazyky.";
$lang_str['at_hint_for_ser'] = 					"Atribut je nahr�van� SERem. Pouze nov� vytvo�en� atributy budou ovlivn�n� zm�nou tohoto pole.";
$lang_str['at_hint_for_serweb'] = 				"Atribut je nahr�van� SerWebem. Pouze nov� vytvo�en� atributy budou ovlivn�n� zm�nou tohoto pole.";
$lang_str['at_hint_user'] = 					"Atribut je zobrazovan� na str�nce u�ivatelsk�ch nastaven�";
$lang_str['at_hint_domain'] = 					"Atribut je zobrazovan� na str�nce dom�nov�ch nastaven�";
$lang_str['at_hint_global'] = 					"Atribut je zobrazovan� na str�nce glob�ln�ch nastaven�";
$lang_str['at_hint_multivalue'] = 				"Atribut m��e obsahovat v�ce hodnot";
$lang_str['at_hint_registration'] = 			"Atribut je zobrazovan� na str�nce registrace nov�ho u�ivatele";
$lang_str['at_hint_required'] = 				"Atribut mus� m�t n�jakou nepr�zdnou hodnotu. Toto nastaven� nen� zohledn�no u v�ech typ� atribut�. Je pou��v�no nap�. u t�chto typ�: int, email_adr, sip_adr, atd.";


$lang_str['ff_att_default_value'] = 			"defaultn� hodnota";
$lang_str['th_att_default_value'] = 			"defaultn� hodnota";
$lang_str['ff_set_as_default'] = 				"nastavit jako defaultn�";
$lang_str['edit_items_of_the_list'] = 			"zm�nit seznam polo�ek";

$lang_str['o_lang_not_selected'] = 				"nen� vybran�";

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


$lang_str['change_credentials_of_user'] = 		"Zm�na kredenci�l� u�ivatele";

$lang_str['th_password'] = 						"heslo";
$lang_str['th_for_ser'] = 						"pro SER";
$lang_str['th_for_serweb'] = 					"pro SerWeb";

$lang_str['err_credential_changed_domain'] = 	"Dom�na u�ivatele byla zm�n�na. Mus�te tak� vyplnit nov� heslo";
$lang_str['warning_credential_changed_domain'] =		"Serweb je nakonfigurov�n aby neukl�dal hesla v �ist�m textu. To znamen� �e v p��pad� �e zm�n�te dom�nu u�ivatele, mus�te tak� vyplnit nov� heslo. V opa�n�m p��pad� by za�ifrovan� heslo p�estalo platit.";

$lang_str['realy_want_you_delete_this_credential'] = 	"Opravdu chcete smazat tyto kredenci�ly?";


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

$lang_str['sel_item_all_calls'] = 				"V�echny hovory";
$lang_str['sel_item_outgoing_calls'] = 			"Jenom odchoz� hovory";
$lang_str['sel_item_incoming_cals'] = 			"Jenom p��choz� hovory";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['fe_no_greeeting_file'] = 			"nevybral jste soubor s pozdravem";
$lang_str['fe_invalid_greeting_file'] = 		"soubor s pozdravem je neplatn�";
$lang_str['fe_greeting_file_no_wav'] = 			"typ souboru s pozdravem mus� b�t audio/wav";
$lang_str['fe_greeting_file_too_big'] = 		"soubor s pozdravem je p��li� velk�";
$lang_str['msg_greeting_stored_s'] = 			"Pozdrav ulo�en";
$lang_str['msg_greeting_stored_l'] = 			"V� pozdrav byl �sp�n� ulo�en";
$lang_str['msg_greeting_deleted_s'] = 			"Pozdrav vymaz�n";
$lang_str['msg_greeting_deleted_l'] = 			"V� pozdrav byl �sp�n� vymaz�n";

/* ------------------------------------------------------------*/
/*      whitelist                                              */
/* ------------------------------------------------------------*/

$lang_str['err_whitelist_already_exists'] = 	"Polo�ka ji� existuje";

/* ------------------------------------------------------------*/
/*      multidomain                                            */
/* ------------------------------------------------------------*/

$lang_str['fe_not_customer_name'] = 			"Mus�te vyplnit jm�no z�kazn�ka"; 
$lang_str['ff_customer_name'] = 				"jm�no z�kazn�ka"; 
$lang_str['no_customers'] = 					"��dn� z�kazn�ci";
$lang_str['customer'] = 						"Z�kazn�k";

$lang_str['msg_customer_updated_s'] = 			"Z�kazn�k aktualizov�n";
$lang_str['msg_customer_updated_l'] = 			"Jm�no z�kazn�ka bylo aktualizov�no";
$lang_str['msg_customer_deleted_s'] = 			"Z�kazn�k vymaz�n";
$lang_str['msg_customer_deleted_l'] = 			"Z�kazn�k byl vymaz�n";
$lang_str['msg_customer_added_s'] = 			"Z�kazn�k zalo�en";
$lang_str['msg_customer_added_l'] = 			"Nov� z�kazn�k byl zalo�en";
$lang_str['err_customer_own_domains'] = 		"Z�kazn�k vlastn� n�jak� dom�ny, nem��ete jej smazat";

$lang_str['d_id'] = 							"ID dom�ny";
$lang_str['d_name'] = 							"jm�no dom�ny";
$lang_str['list_of_domains'] = 					"Seznam dom�n";
$lang_str['showed_domains'] = 					"Zobrazen� dom�ny";
$lang_str['no_domains_found'] = 				"��dn� dom�ny nebyly nalezeny";
$lang_str['new_dom_name'] = 					"P�idat nov� dom�nov� jm�no";
$lang_str['owner'] = 							"Vlastn�k";

$lang_str['realy_delete_domain'] = 				"Opravdu chcete smazat tuto dom�nu?";
$lang_str['l_create_new_domain'] = 				"vytvo�it novou dom�nu";
$lang_str['l_reload_ser'] = 					"reload SER and web server";	//to translate 
$lang_str['no_domain_name_is_set'] = 			"Vlo�te nejm�n� jedno jm�no dom�ny";
$lang_str['prohibited_domain_name'] = 			"Toto dom�nov� jm�no nen� povoleno";
$lang_str['can_not_del_last_dom_name'] = 		"Nelze smazat jedin� dom�nov� jm�no";

$lang_str['msg_domain_reload_s'] = 				"Konfigurace znovu na�tena";
$lang_str['msg_domain_reload_l'] = 				"Konfigurace SERu a webov�ho serveru byla znovu na�tena";

$lang_str['msg_domain_deleted_s'] = 			"Dom�na smaz�na";
$lang_str['msg_domain_deleted_l'] = 			"Tato dom�na nen� nad�le obsluhov�na a v�echny asociovan� z�znamy v�etn� u�ivatelsk�ch dat budou smaz�ny. Ujist�te se �e DNS z�znamy nad�le neukazuj� na tento server";

$lang_str['assigned_domains'] = 				"P�i�azen� dom�ny";
$lang_str['unassigned_domains'] = 				"Nep�i�azen� dom�ny";
$lang_str['l_assign_domain'] = 					"p�i�adit dom�nu";
$lang_str['l_unassign_domain'] = 				"odebrat dom�nu";
$lang_str['l_assign'] = 						"p�i�adit";
$lang_str['l_unassign'] = 						"odebrat";
$lang_str['l_assigned_domains'] = 				"Dom�ny";
$lang_str['l_change_layout'] = 					"Rozvr�en�";
$lang_str['l_domain_attributes'] = 				"Atributy";
$lang_str['l_unassign_admin'] = 				"odebrat administr�tora";
$lang_str['l_set_canon'] = 						"nastavit jako kanonick�";

$lang_str['admins_of_domain'] = 				"Administr�to�i pro tuto dom�nu";
$lang_str['no_admins'] = 						"��dn� adminsitr�to�i";

$lang_str['ff_address'] = 						"adresa";

$lang_str['lf_terms_and_conditions'] =			"podm�nky a po�adavky";
$lang_str['lf_mail_register_by_admin'] = 		"text emailu zas�lan�ho u�ivateli pokud je nov� zaregistrov�n administr�torem";
$lang_str['lf_mail_register'] = 				"text emailu potvrzuj�c�ho registraci";
$lang_str['lf_mail_fp_conf'] = 					"text emailu pro potvrzen� znovu nasteven� hesla pot� co jej u�vatel zapomn�l";
$lang_str['lf_mail_fp_pass'] = 					"text emailu obsahuj�c� nov� vygenerovan� heslo";
$lang_str['lf_config'] = 						"konfigura�n� soubor dom�ny";

$lang_str['l_toggle_wysiwyg'] = 				"p�epnout WYSIWYG";	
$lang_str['l_upload_images'] = 					"nahr�t obr�zky";
$lang_str['l_back_to_default'] = 				"obnovit p�vodn� obsah";

$lang_str['wysiwyg_warning'] = 					"Pros�m bu�te opatrn� p�i pou��v�n� WYSIWYG editoru. Soubor prolog.html mus� za��nat elementem &lt;body&gt; a soubor epilog.html mus� kon�it elementem &lt;/body&gt;. Ale WYSIWYG editor tyto elementy odstra�uje!";

$lang_str['choose_one'] = 						"vyberte";

$lang_str['layout_files'] = 					"Soubory rozv�en�";
$lang_str['text_files'] = 						"Textov� soubory";

$lang_str['fe_domain_not_selected']	= 			"Dom�na u�ivatele nen� vybr�na";

$lang_str['th_old_versions'] = 					"P�edchoz� verze tohoto souboru";
$lang_str['initial_ver'] = 						"po��te�n�";


$lang_str['err_cant_run_host_command'] =        "Error when executing 'host' command. Can not check the DNS setting";	//to translate 
$lang_str['err_no_output_of_host_command'] =    "Error when executing 'host' command. There is no output. Can not check the DNS setting";	//to translate 
$lang_str['err_wrong_srv_record'] =             "SRV record(s) found, but it has wrong target host or port. Following SRV records have been found: ";	//to translate 
$lang_str['err_unrecognized_output_of_host'] =  "DNS is not set in correct way. Here is output of 'host' comamnd: ";	//to translate 
$lang_str['err_domain_already_hosted'] = 		"This domain is already hosted on this server";	//to translate 


/* ------------------------------------------------------------*/
/*      wizard - create new domain                             */
/* ------------------------------------------------------------*/

$lang_str['register_new_admin'] = 				"Registrace nov�ho administr�tora pro dom�nu";
$lang_str['assign_existing_admin'] = 			"P�i�azen� existuj�c�ho administr�tora k dom�n�";
$lang_str['assign_admin_to_domain'] = 			"P�i�azen� administr�tora k dom�n�";
$lang_str['create_new_domain'] = 				"Vytvo�en� nov� dom�ny";
$lang_str['l_create_new_customer'] = 			"vytvo�en� nov�ho z�kazn�ka";
$lang_str['create_new_customer'] = 				"Vytvo�en� nov�ho z�kazn�ka";
$lang_str['l_close_window'] = 					"zav��t toto okno";
$lang_str['step'] = 							"krok";
$lang_str['l_select'] = 						"vybrat";
$lang_str['domain_setup_success'] = 			"Nov� dom�na byla �sp�n� zalo�ena!";
$lang_str['l_skip_asignment_of_admin'] = 		"p�esko�it p�i�azen� administr�tora";

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

$lang_str['reg_finish_questions'] = 			"Pokud m�te n�jak� dotazy neost�chejte se n�m napsat";
$lang_str['tab_caller_screening'] =	 			"filtrov�n� volaj�c�ch";
$lang_str['msg_caller_screening_deleted_s'] = 	"Adresa vymaz�na";
$lang_str['msg_caller_screening_deleted_l'] = 	"Adresa byla vymaz�na ze seznamu filtrovan�ch adres";
$lang_str['msg_caller_screening_updated_s'] = 	"Adresa zm�n�na";
$lang_str['msg_caller_screening_updated_l'] = 	"Filtrovan� adresa byla zm�n�na";
$lang_str['msg_caller_screening_added_s'] = 	"Adresa p�id�na";
$lang_str['msg_caller_screening_added_l'] = 	"Adresa byla p�id�na do seznamu filtrovan�ch adres";
$lang_str['fe_not_caller_uri'] = 				"mus�te vyplnit adresu volaj�c�ho";
$lang_str['ff_screening_caller_uri'] = 			"adresa volaj�c�ho (regul�rn� v�raz)";
$lang_str['ff_action'] = 						"akce";
$lang_str['th_caller_uri'] = 					"adresa volaj�c�ho";
$lang_str['th_action'] = 						"akce";
$lang_str['no_caller_screenings_defined'] = 	"��dn� filtrov�n� volaj�c�ch nen� definov�no";
$lang_str['err_screening_already_exists'] = 	"z�znam s touto adresou volaj�c�ho u� existuje";
$lang_str['cs_decline'] = 						"odm�tnout";
$lang_str['cs_reply_busy'] = 					"odpov�d�t: jsem zanepr�zdn�n";
$lang_str['cs_fw_to_voicemail'] = 				"p�esm�rovat do hlasov� schr�nky";
$lang_str['tab_ser_moni'] =	 					"monitorov�n� serveru";
$lang_str['err_reg_conf_already_created'] = 	"V� ��et ji� byl vytvo�en";
$lang_str['ser_moni_current'] = 				"aktu�ln�";
$lang_str['ser_moni_average'] = 				"pr�m�rn�";
$lang_str['ser_moni_waiting_cur'] = 			"aktu�ln� �ekaj�c�ch";
$lang_str['ser_moni_waiting_avg'] = 			"pr�m�rn� �ekaj�c�ch";
$lang_str['ser_moni_total_cur'] = 				"aktu�ln� souhrn";
$lang_str['ser_moni_total_avg'] = 				"pr�m�rn� souhrn";
$lang_str['ser_moni_local_cur'] = 				"aktu�ln� lok�ln�ch";
$lang_str['ser_moni_local_avg'] = 				"pr�m�rn� lok�ln�ch";
$lang_str['ser_moni_replies_cur'] = 			"akt. lok�l. odpov�d�";
$lang_str['ser_moni_replies_avg'] = 			"pr�m. lok�l. odpov�d�";
$lang_str['ser_moni_registered_cur'] = 			"akt. registrov�no";
$lang_str['ser_moni_registered_avg'] = 			"pr�m. registrov�no";
$lang_str['ser_moni_expired_cur'] = 			"aktu�ln� vypr�en�ch";
$lang_str['ser_moni_expired_avg'] = 			"pr�m�rn� vypr�en�ch";
$lang_str['ser_moni_general_values'] = 			"obecn� hodnoty";
$lang_str['ser_moni_diferencial_values'] = 		"rozd�lov� hodnoty";
$lang_str['ser_moni_transaction_statistics'] = 	"Transak�n� statistiky";
$lang_str['ser_moni_completion_status'] = 		"Stav dokon�en�";
$lang_str['ser_moni_stateless_server_statis'] = "Statistiky bezestavov�ho serveru";
$lang_str['ser_moni_usrLoc_stats'] = 			"Statistiky UsrLoc";
$lang_str['l_domain_preferences'] = 			"dom�nov� nastaven�";
?>
