<?
/*
 * $Id: czech-windows-1250.php,v 1.30 2006/04/18 08:44:28 kozlik Exp $
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

$lang_set['charset'] = 			"windows-1250";
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
$lang_str['l_change'] = 						"zm�nit";
$lang_str['l_delete'] = 						"smazat";
$lang_str['l_back_to_main'] = 					"zp�t na hlavn� str�nku";
$lang_str['l_back'] = 							"back";	//to translate 
$lang_str['l_disable'] = 						"disable";	//to translate 
$lang_str['l_enable'] = 						"enable";	//to translate 
$lang_str['l_disable_all'] = 					"disable all";	//to translate 
$lang_str['l_enable_all'] = 					"enable all";	//to translate 
$lang_str['status_unknown'] = 					"nezn�m�";
$lang_str['status_nonlocal'] = 					"nelok�ln�";
$lang_str['status_nonexists'] = 				"neexistuj�c�";
$lang_str['status_online'] = 					"on line";
$lang_str['status_offline'] = 					"off line";
$lang_str['search_filter'] = 					"filtr";
$lang_str['showed_users'] = 					"Zobrazen� u�ivatel�";
$lang_str['no_users_found'] = 					"��dn� u�ivatel� nenalezeni";
$lang_str['none'] = 							"none";	//to translate 
$lang_str['warning'] = 							"Warning!";	//to translate 
$lang_str['domain'] = 							"tabulka";

/* ------------------------------------------------------------*/
/*      error messages                                         */
/* ------------------------------------------------------------*/

$lang_str['fe_not_valid_email'] =	 			"neplatn� emailov� adresa";
$lang_str['fe_is_not_valid_email'] =	 		"is not valid email address";	//to translate 
$lang_str['fe_not_valid_sip'] = 				"neplatn� SIP adresa";
$lang_str['fe_not_valid_phonenumber'] = 		"neplatn� telefonn� ��slo";
$lang_str['fe_not_filled_sip'] = 				"SIP adresu mus�te vyplnit";
$lang_str['fe_passwords_not_match'] =			"hesla si neodpov�daj�";
$lang_str['fe_not_filled_username'] = 			"u�ivatelsk� jm�no mus�te vyplnit";
$lang_str['fe_not_allowed_uri'] = 				"Nepovolen� SIP adresa";
$lang_str['fe_max_entries_reached'] = 			"Dosa�en maxim�ln� povolen� po�et polo�ek";
$lang_str['fe_not_valid_username'] = 			"not valid username";	//to translate 
$lang_str['fe_not_valid_domainname'] = 			"not valid domainname";	//to translate 

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
$lang_str['b_select'] =		 					"Vyber";
$lang_str['b_test_firewall_NAT'] =		 		"Otestovat firewall/NAT";
$lang_str['b_upload_greeting'] =		 		"Nahr�t nov� pozdrav";
$lang_str['b_extended_settings'] =		 		"Roz���en� nastaven�";


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
$lang_str['tab_domains'] =	 					"domains";	//to translate 
$lang_str['tab_customers'] =	 				"customers";	//to translate 
$lang_str['tab_global_attributes'] =	 		"global attributes";	//to translate 
$lang_str['tab_attr_types'] =	 				"types of attributes";	//to translate 

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
$lang_str['ff_language'] = 						"language";	//to translate 
$lang_str['ff_reg_confirmation'] = 				"require confirmation of registration";	//to translate 
$lang_str['ff_uid'] = 							"uid";	//to translate 
$lang_str['ff_for_ser'] = 						"for SER";	//to translate 
$lang_str['ff_for_serweb'] = 					"for SerWeb";	//to translate 

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
$lang_str['th_uid'] = 							"uid";	//to translate 

/* ------------------------------------------------------------*/
/*      login messages                                         */
/* ------------------------------------------------------------*/

$lang_str['bad_username'] = 					"Chybn� u�ivatelsk� jm�no nebo heslo";
$lang_str['account_disabled'] = 				"Your account was disabled";	//to translate 
$lang_str['domain_not_found'] = 				"Your domain not found";	//to translate 
$lang_str['msg_logout_s'] = 					"Odhl�en";
$lang_str['msg_logout_l'] = 					"Byl jste odhl�en. Pro nov� p�ihl�en� vypl�te u�ivatelsk� jm�no a heslo";
$lang_str['userlogin'] = 						"p�ihl�en� u�ivatele";
$lang_str['adminlogin'] = 						"p�ihl�en� administr�tora";
$lang_str['enter_username_and_passw'] = 		"Pros�m vypl�te va�e u�ivatelsk� jm�no a heslo";
$lang_str['ff_password'] = 						"heslo";
$lang_str['l_forgot_passw'] = 					"Zapom�li jste heslo?";
$lang_str['l_register'] = 						"Registrace!";
$lang_str['remember_uname'] = 					"Zapamatuj si m� u�ivatelsk� jm�no na tomto po��ta�i";
$lang_str['session_expired'] = 					"Session expired";	//to translate 
$lang_str['session_expired_relogin'] = 			"Your session expired, please relogin.";	//to translate 

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

$lang_str['ff_uri_def_f'] =						"default flags for uri";	//to translate 
$lang_str['ff_credential_def_f'] =				"default flags for credentials";	//to translate 
$lang_str['ff_domain_def_f'] =					"default flags for domain";	//to translate 


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
$lang_str['choose_timezone_of_user'] = 			"--- please select timezone of user ---";	//to translate 
$lang_str['fe_not_choosed_timezone'] = 			"vyberte va�i �asovou z�nu";
$lang_str['fe_uname_not_follow_conventions'] = 	"u�ivatelsk� jm�no neodpov�d� doporu�ovan�m konvenc�m";
$lang_str['fe_not_filled_password'] = 			"mus�te vyplnit heslo";
$lang_str['fe_not_filled_your_fname'] = 		"mus�te vyplnit k�estn� jm�no";
$lang_str['fe_not_filled_your_lname'] = 		"mus�te vyplnit p��jmen�";
$lang_str['fe_uname_already_choosen_1'] = 		"Promi�te, u�ivatelsk� jm�no";
$lang_str['fe_uname_already_choosen_2'] = 		"u� bylo vybr�no n�k�m jin�m. Zkuste n�jak� jin�.";
$lang_str['err_sending_mail'] = 				"Promi�te, do�lo k chyb� p�i odes�l�n� registra�n�ho emailu.";
$lang_str['registration_introduction'] = 		"Pro registraci pros�m vypl�te formul�� n�e a klikn�te na tla��tko &quot;registrovat&quot; na spodku str�nky. Bude v�m zasl�n email potvrzuj�c� registraci. Pros�m kontaktujte <a href=\"mailto:".$config->regmail."\">".$config->regmail."</a> pokud m�te n�jak� ot�zky ohledn� registrace nebo na�ich SIP slu�eb.";
$lang_str['reg_email_desc'] = 					"Adresa na kterou bude odesl�no potvrzen� o registraci. (Pokud vypln�te neplatnou adresu, ��dn� potvrzen� nedostanete a ��et v�m nebude vytvo�en.)";
$lang_str['ff_phone'] = 						"telefonn� ��slo";
$lang_str['reg_phone_desc'] = 					"Telefonn� ��slo na kter�m jste k zasti�en�.";
$lang_str['ff_pick_username'] = 				"zvolte si u�ivatelsk� jm�no";
$lang_str['reg_username_desc'] = 				"Va�e SIP adresa bude: u�ivatelsk�_jm�no@".$config->domain.". U�ivatelsk� jm�no m��e b�t bu� numerick� za��naj�c� '8' (nap�., '8910') nebo alfanumerick� ps�no mal�mi p�smeny za��naj�c� p�smenem (nap�., john.doe01). Nezapome�te va�e u�ivatelsk� jm�no -- budete ho pot�ebovat k nastaven� va�eho telefonu!";
$lang_str['ff_pick_password'] = 				"zvolte si heslo";
$lang_str['reg_password_desc'] = 				"Nezapme�te va�e heslo -- budete ho pot�ebovat k nastaven� va�eho telefonu!";
$lang_str['ff_confirm_password'] = 				"heslo znovu pro potvrzen�";
$lang_str['ff_terms_and_conditions'] = 			"podm�nky a po�adavky";
$lang_str['ff_i_accept'] = 						"souhlas�m";
$lang_str['ff_timezone'] = 						"timezone";	//to translate 
$lang_str['l_back_to_loginform'] = 				"Zp�t na p�ihla�ovac� str�nku";
$lang_str['msg_user_registered_s'] = 			"User registered";	//to translate 
$lang_str['msg_user_registered_l'] = 			"New user has been successfully registered";	//to translate 
$lang_str['register_new_user'] = 				"register new user";	//to translate 

/* ------------------------------------------------------------*/
/*      registration - finished                                */
/* ------------------------------------------------------------*/

$lang_str['reg_finish_thanks'] = 				"D�kujeme za registraci v ".$config->domain;
$lang_str['reg_finish_app_forwarded'] = 		"Va�e ��dost byla odesl�na ke schv�len�.";
$lang_str['reg_finish_confirm_msg'] = 			"O�ek�vejte potvrzuj�c� zpr�vu v kr�tk� dob�..";
$lang_str['reg_finish_sip_address'] = 			"Rezervujeme pro V�s tuto SIP adresu:";
$lang_str['reg_finish_questions'] = 			"Pokud m�te n�jak� dotazy neost�chejte se n�m napsat";
$lang_str['reg_finish_infomail'] = 				"email na adresu <a href=\"mailto:".$config->infomail."\">".$config->infomail."</a>.";

/* ------------------------------------------------------------*/
/*      registration - confirmation                            */
/* ------------------------------------------------------------*/

$lang_str['reg_conf_congratulations'] = 		"Gratulujeme! V� ".$config->domain." ��et je p�ipraven!";
$lang_str['reg_conf_set_up'] = 					"V� ".$config->domain." ��et je p�ipraven!";
$lang_str['reg_conf_jabber_failed'] = 			"Ale va�e registrace v Jabber br�n� ".$config->domain." selhala.";
$lang_str['reg_conf_contact_infomail'] = 		"Pros�m kontaktujte <a href=\"mailto:".$config->infomail."\">".$config->infomail."</a> pro dal�� pomoc.";
$lang_str['reg_conf_failed'] = 					"Omlouv�me se ale v� pokus o potvrzen� selhal.";
$lang_str['reg_conf_nr_not_exists'] = 			"Either your confirmation number is wrong or your account has been already created!";	//to translate 
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
$lang_str['realy_you_want_delete_this_user'] =	"Realy you want delete this user?";	//to translate 
$lang_str['l_credentials'] = 					"credentials";	//to translate 
$lang_str['user_has_no_credentials'] = 			"User has no credentials";	//to translate 

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

$lang_str['admin_privileges_of'] = 				"Opr�vn�n� administr�tora ";
$lang_str['admin_competence'] = 				"p�sobnost administr�tora";
$lang_str['ff_is_admin'] = 						"je administr�tor";
$lang_str['ff_change_privileges'] = 			"zm�na opr�vn�n� jin�ch administr�tor�";
$lang_str['ff_is_hostmaster'] = 				"is hostmaster";	//to translate 
$lang_str['acl_control'] = 						"zm�ny ACL";
$lang_str['msg_privileges_updated_s'] = 		"Opr�vn�n� aktualizov�ny";
$lang_str['msg_privileges_updated_l'] = 		"Opr�vn�n� u�ivatele byly aktualizov�ny";
$lang_str['list_of_users'] = 					"Seznam u�ivatel�";
$lang_str['th_domain'] = 						"dom�na";
$lang_str['l_change_privileges'] = 				"zm�na opr�vn�n�";
$lang_str['ff_domain'] = 						"dom�na";
$lang_str['ff_realm'] = 						"realm";	//to translate 
$lang_str['th_realm'] = 						"realm";	//to translate 
$lang_str['ff_show_admins_only'] = 				"zobrazit jenom administr�tory";
$lang_str['err_cant_ch_priv_of_hostmaster'] = 	"This user is hostmaster. You can't change privileges of hostmaster because you are not hostmaster!";	//to translate 


/* ------------------------------------------------------------*/
/*      attribute types                                        */
/* ------------------------------------------------------------*/

$lang_str['fe_not_filled_name_of_attribute'] = 	"mus�te vyplnit jm�no atributu";
$lang_str['ff_order'] = 						"order";	//to translate 
$lang_str['ff_att_name'] = 						"jm�no atributu";
$lang_str['ff_att_type'] = 						"typ atributu";
$lang_str['ff_label'] = 						"label";	//to translate 
$lang_str['ff_att_user'] = 						"user";	//to translate 
$lang_str['ff_att_domain'] = 					"domain";	//to translate 
$lang_str['ff_att_global'] = 					"global";	//to translate 
$lang_str['ff_multivalue'] = 					"multivalue";	//to translate 
$lang_str['ff_att_reg'] = 						"required on registration";	//to translate 
$lang_str['ff_fr_timer'] = 						"final response timer";	//to translate 
$lang_str['ff_fr_inv_timer'] = 					"final response invite timer";	//to translate 

$lang_str['th_att_name'] = 						"jm�no atributu";
$lang_str['th_att_type'] = 						"typ atributu";
$lang_str['th_order'] = 						"order";	//to translate 
$lang_str['th_label'] = 						"label";	//to translate 
$lang_str['fe_order_is_not_number'] = 			"'order' is not valid number";	//to translate 

$lang_str['fe_not_filled_item_label'] = 		"mus�te vyplnit jm�no polo�ky";
$lang_str['fe_not_filled_item_value'] = 		"mus�te vyplnit hodnotu polo�ky";
$lang_str['ff_item_label'] = 					"jm�no polo�ky";
$lang_str['ff_item_value'] = 					"hodnota polo�ky";
$lang_str['th_item_label'] = 					"jm�no polo�ky";
$lang_str['th_item_value'] = 					"hodnota polo�ky";
$lang_str['l_back_to_editing_attributes'] = 	"zp�t k editaci atribut�";
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



$lang_str['ff_att_default_value'] = 			"defaultn� hodnota";
$lang_str['th_att_default_value'] = 			"defaultn� hodnota";
$lang_str['ff_set_as_default'] = 				"nastavit jako defaultn�";
$lang_str['edit_items_of_the_list'] = 			"zm�nit seznam polo�ek";


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
$lang_str['l_assigned_domains'] = 				"assigned domains";	//to translate 
$lang_str['l_change_layout'] = 					"change layout";	//to translate 
$lang_str['l_domain_attributes'] = 				"domain attributes";	//to translate 
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
$lang_str['l_domain_preferences'] = 			"domain preferences";	//to translate 
?>
