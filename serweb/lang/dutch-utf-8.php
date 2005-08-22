<?
/*
 * $Id: dutch-utf-8.php,v 1.3 2005/08/10 13:55:17 kozlik Exp $
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
$lang_set['date_time_format'] = "d-m-Y H:i";
$lang_set['date_format'] = 		"d-m-Y";
$lang_set['time_format'] = 		"H:i";


/* ------------------------------------------------------------*/
/*      common messages                                        */
/* ------------------------------------------------------------*/

$lang_str['user_management'] = 					"Gebruikersbeheer";
$lang_str['admin_interface'] = 					"Admin interface";
$lang_str['user'] = 							"gebruiker";
$lang_str['from'] = 							"van";
$lang_str['no_records'] = 						"Geen gegevens";
$lang_str['l_logout'] = 						"Logout";
$lang_str['l_edit'] = 							"toevoegen";
$lang_str['l_change'] = 						"wijzigen";
$lang_str['l_delete'] = 						"verwijderen";
$lang_str['l_back_to_main'] = 					"terug";
$lang_str['status_unknown'] = 					"onbekend";
$lang_str['status_nonlocal'] = 					"niet lokaal";
$lang_str['status_nonexists'] = 				"non-existent";
$lang_str['status_online'] = 					"on line";
$lang_str['status_offline'] = 					"off line";
$lang_str['search_filter'] = 					"filter";
$lang_str['showed_users'] = 					"Overzicht gebruikers";
$lang_str['no_users_found'] = 					"Geen gebruikers gevonden";

/* ------------------------------------------------------------*/
/*      error messages                                         */
/* ------------------------------------------------------------*/

$lang_str['fe_not_valid_email'] =	 			"ongeldig email adres";
$lang_str['fe_not_valid_sip'] = 				"ongeldig sip adres";
$lang_str['fe_not_valid_phonenumber'] = 		"ongeldig telefoonnummer";
$lang_str['fe_not_filled_sip'] = 				"u moet een SIP adres invullen";
$lang_str['fe_passwords_not_match'] =			"wachtwoord niet gelijk";
$lang_str['fe_not_filled_username'] = 			"U moet een gebruikersnaam invullen";
$lang_str['fe_not_allowed_uri'] = 				"ongeldig SIP adres";
$lang_str['fe_max_entries_reached'] = 			"Maximum aantal pogingen";

/* ------------------------------------------------------------*/
/*      buttons                                                */
/* ------------------------------------------------------------*/

$lang_str['b_add'] =		 					"Toevoegen";
$lang_str['b_back'] =		 					"Terug";
$lang_str['b_delete_calls'] =		 			"Verwijder berichten";
$lang_str['b_dial_your_voicemail'] =		 	"Bel voicemail";
$lang_str['b_download_greeting'] =		 		"Download welkomsbericht";
$lang_str['b_edit_items_of_the_list'] =		 	"Wijzig items in de lijst";
$lang_str['b_find'] = 							"Zoek";
$lang_str['b_forgot_pass_submit'] = 			"Wachtwoord opvragen";
$lang_str['b_login'] =		 					"Login";
$lang_str['b_next'] =		 					"Volgende";
$lang_str['b_register'] = 						"Registreer";
$lang_str['b_send'] =		 					"Verstuur";
$lang_str['b_submit'] =		 					"Bewaar";
$lang_str['b_test_firewall_NAT'] =		 		"Test firewall/NAT";
$lang_str['b_upload_greeting'] =		 		"Upload welkomsbericht";


/* ------------------------------------------------------------*/
/*      tabs                                                   */
/* ------------------------------------------------------------*/

$lang_str['tab_my_account'] =		 			"mijn account";
$lang_str['tab_phonebook'] =		 			"telefoonboek";
$lang_str['tab_missed_calls'] =	 				"gemiste telefoontjes";
$lang_str['tab_accounting'] =	 				"rekening";
$lang_str['tab_send_im'] =	 					"verstuur IM";
$lang_str['tab_message_store'] =	 			"berichten";
$lang_str['tab_voicemail'] =	 				"voicemail";
$lang_str['tab_user_preferences'] =	 			"instellingen";
$lang_str['tab_speed_dial'] =	 				"direct bellen";
$lang_str['tab_caller_screening'] =	 			"caller screening";

$lang_str['tab_users'] =	 					"gebruikers";
$lang_str['tab_admin_privileges'] =	 			"admin rechten";
$lang_str['tab_ser_moni'] =	 					"server status";

/* ------------------------------------------------------------*/
/*      form fields                                            */
/* ------------------------------------------------------------*/

$lang_str['ff_first_name'] = 					"voornaam";
$lang_str['ff_last_name'] = 					"achternaam";
$lang_str['ff_sip_address'] = 					"sip adres";
$lang_str['ff_your_timezone'] = 				"timezone";
$lang_str['ff_username'] = 						"gebruikersnaam";
$lang_str['ff_email'] = 						"email";
$lang_str['ff_show_online_only'] = 				"alleen on-line gebruikers";

/* ------------------------------------------------------------*/
/*      table heading                                          */
/* ------------------------------------------------------------*/

$lang_str['th_name'] = 							"naam";
$lang_str['th_sip_address'] = 					"sip adres";
$lang_str['th_aliases'] = 						"alias";
$lang_str['th_status'] = 						"status";
$lang_str['th_timezone'] = 						"timezone";
$lang_str['th_calling_subscriber'] = 			"calling subscriber";
$lang_str['th_time'] = 							"tijd";
$lang_str['th_username'] = 						"gebruiker";
$lang_str['th_email'] = 						"email";

/* ------------------------------------------------------------*/
/*      login messages                                         */
/* ------------------------------------------------------------*/

$lang_str['bad_username'] = 					"Ongeldige gebruikersnaam of wachtwoord";
$lang_str['msg_logout_s'] = 					"U bent uitgelogd";
$lang_str['msg_logout_l'] = 					"U bent uitgelogd, om opnieuw in te loggen, toets hieronder uw gebruikersnaam en wachtwood in";
$lang_str['userlogin'] = 						"Gebruikers login";
$lang_str['adminlogin'] = 						"Administrator login";
$lang_str['enter_username_and_passw'] = 		"Login met gebruikersnaam en wachtwoord";
$lang_str['ff_password'] = 						"Wachtwoord";
$lang_str['l_forgot_passw'] = 					"Wachtwoord vergeten?";
$lang_str['l_register'] = 						"Inschrijven!";
$lang_str['remember_uname'] = 					"Login gegevens onthouden op de computer?";

/* ------------------------------------------------------------*/
/*      my account                                             */
/* ------------------------------------------------------------*/

$lang_str['msg_changes_saved_s'] = 				"Bewaar";
$lang_str['msg_changes_saved_l'] = 				"Uw wijzigingen zijn opgeslagen";
$lang_str['msg_loc_contact_deleted_s'] = 		"Gebruiker verwijderd";
$lang_str['msg_loc_contact_deleted_l'] = 		"Deze gebruiker is verwijderd";
$lang_str['msg_loc_contact_added_s'] = 			"Gebruiker toevoegen";
$lang_str['msg_loc_contact_added_l'] = 			"Gebruiker is toegevoegd";
$lang_str['ff_your_email'] = 					"uw email";
$lang_str['ff_fwd_to_voicemail'] = 				"doorsturen naar voicemail";
$lang_str['ff_allow_lookup_for_me'] = 			"mijn SIP adres is voor andere zichtbaar";
$lang_str['ff_status_visibility'] = 			"mijn SIP adres zichtbaar als ik online ben";
$lang_str['ff_your_password'] = 				"wachtwoord";
$lang_str['ff_retype_password'] = 				"uw wachtwoord opnieuw intoetsen";
$lang_str['your_aliases'] = 					"uw alias";
$lang_str['your_acl'] = 						"Toegang controlelijst";
$lang_str['th_contact'] = 						"contact";
$lang_str['th_expires'] = 						"verloopt";
$lang_str['th_priority'] = 						"prioriteit";
$lang_str['th_location'] = 						"locatie";
$lang_str['add_new_contact'] = 					"contact toevoegen";
$lang_str['ff_expires'] = 						"verloopt";
$lang_str['contact_expire_hour'] = 				"over een uur";
$lang_str['contact_expire_day'] = 				"over een dag";
$lang_str['contact_will_not_expire'] = 			"permanent";
$lang_str['acl_err_local_forward'] = 			"local forwarding prohibited";
$lang_str['acl_err_gateway_forward'] = 			"gateway contacts prohibited";

/* ------------------------------------------------------------*/
/*      phonebook                                              */
/* ------------------------------------------------------------*/

$lang_str['msg_pb_contact_deleted_s'] = 		"Contact verwijderd";
$lang_str['msg_pb_contact_deleted_l'] = 		"Het contactpersoon is uit uw adresboek verwijderd";
$lang_str['msg_pb_contact_updated_s'] = 		"Contact gewijzigd";
$lang_str['msg_pb_contact_updated_l'] = 		"Uw wijzigingen zijn opgeslagen";
$lang_str['msg_pb_contact_added_s'] = 			"Contact toegevoegd";
$lang_str['msg_pb_contact_added_l'] = 			"Het contactpersoon is toegevoegd aan uw adresboek";
$lang_str['phonebook_records'] = 				"Telefoonboek items";
$lang_str['l_find_user'] = 						"zoek een contact";

/* ------------------------------------------------------------*/
/*      find user                                              */
/* ------------------------------------------------------------*/

$lang_str['find_user'] = 						"Zoek een gebruiker";
$lang_str['l_add_to_phonebook'] = 				"naar telefoonboek";
$lang_str['l_back_to_phonebook'] = 				"terug naar telefoonboek";
$lang_str['found_users'] = 						"Gebruikers";

/* ------------------------------------------------------------*/
/*      missed calls                                           */
/* ------------------------------------------------------------*/

$lang_str['th_reply_status'] = 					"antwoord status";
$lang_str['missed_calls'] = 					"Gemiste gesprekken";
$lang_str['no_missed_calls'] = 					"Gesprekken";

/* ------------------------------------------------------------*/
/*      accounting                                             */
/* ------------------------------------------------------------*/

$lang_str['th_destination'] = 					"bestemming";
$lang_str['th_length_of_call'] = 				"duur van het gesprek";
$lang_str['th_hangup'] = 						"verbroken";
$lang_str['calls_count'] = 						"Gesprek";
$lang_str['no_calls'] = 						"Geen gesprek";
$lang_str['msg_calls_deleted_s'] = 				"Verwijderd gesprek";
$lang_str['msg_calls_deleted_l'] = 				"Gesprekken zijn verwijderd";


/* ------------------------------------------------------------*/
/*      send IM                                                */
/* ------------------------------------------------------------*/

$lang_str['fe_no_im'] = 						"u heeft geen bericht geschreven";
$lang_str['fe_im_too_long'] = 					"het bericht is te lang";
$lang_str['msg_im_send_s'] = 					"Bericht verzonden";
$lang_str['msg_im_send_l'] = 					"Bericht verzonden naar bestemming";
$lang_str['max_length_of_im'] = 				"De lengte van het bericht is";
$lang_str['sending_message'] = 					"bericht versturen";
$lang_str['please_wait'] = 						"moment geduld!";
$lang_str['ff_sip_address_of_recipient'] = 		"sip adres van ontvanger";
$lang_str['ff_text_of_message'] = 				"inhoud van het bericht";
$lang_str['im_remaining'] = 					"nog over";
$lang_str['im_characters'] = 					"tekens";


/* ------------------------------------------------------------*/
/*      message store                                          */
/* ------------------------------------------------------------*/

$lang_str['instant_messages_store'] = 			"Instant berichten overzicht";
$lang_str['voicemail_messages_store'] = 		"Voicemail overzicht";
$lang_str['no_stored_instant_messages'] = 		"Geen instant berichten aanwezig";
$lang_str['no_stored_voicemail_messages'] = 	"Geen voicemail berichten aanwezig";
$lang_str['th_subject'] = 						"onderwerp";
$lang_str['l_reply'] = 							"antwoorden";
$lang_str['err_can_not_open_message'] = 		"Kan dit bericht niet openen";
$lang_str['err_voice_msg_not_found'] = 			"Bericht niet gevonden of te weinig rechten";
$lang_str['msg_im_deleted_s'] = 				"Bericht verwijderd";
$lang_str['msg_im_deleted_l'] = 				"Bericht is verwijderd";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['customize_greetings'] = 				"Welkomsbericht wijzigen";
$lang_str['err_can_not_open_greeting'] = 		"Kan welkomsbericht niet openen";

/* ------------------------------------------------------------*/
/*      user preferences                                       */
/* ------------------------------------------------------------*/

$lang_str['fe_invalid_value_of_attribute'] = 	"ongeldige waarde voor dit attribuut";
$lang_str['fe_is_not_number'] = 				"ongeldig nummer";
$lang_str['fe_is_not_sip_adr'] = 				"ongeldig sip adres";
$lang_str['no_attributes_defined'] = 			"Geen rechten gegeven door de beheerder";

$lang_str['ff_send_daily_missed_calls'] =		"verstuur dagelijks mijn gemiste berichten naar mijn emailadres";

/* ------------------------------------------------------------*/
/*      speed dial                                             */
/* ------------------------------------------------------------*/

$lang_str['th_speed_dial'] = 					"Verkorte code";
$lang_str['th_new_uri'] = 						"Nieuwe uri";




/* ------------------------------------------------------------*/
/*      caller screening                                       */
/* ------------------------------------------------------------*/

$lang_str['msg_caller_screening_deleted_s'] = 	"Gecontroleerde uri verwijderd";
$lang_str['msg_caller_screening_deleted_l'] = 	"Gecontroleerde uri is verwijderd";
$lang_str['msg_caller_screening_updated_s'] = 	"Gecontroleerde uri gewijzigd";
$lang_str['msg_caller_screening_updated_l'] = 	"Gecontroleerde uri is gewijzigd";
$lang_str['msg_caller_screening_added_s'] = 	"Gecontroleerde uri toegevoegd";
$lang_str['msg_caller_screening_added_l'] = 	"Gecontroleerde uri is toegevoegd";
$lang_str['fe_not_caller_uri'] = 				"u moet een beller uri opgeven";
$lang_str['ff_screening_caller_uri'] = 			"beller uri (standaard weergave)";
$lang_str['ff_action'] = 						"actie";
$lang_str['th_caller_uri'] = 					"beller uri";
$lang_str['th_action'] = 						"actie";
$lang_str['no_caller_screenings_defined'] = 	"Geen beller controles gedefinieerd";
$lang_str['err_screening_already_exists'] = 	"Dit beller uri is reeds aanwezig";
$lang_str['cs_decline'] = 						"decline";
$lang_str['cs_reply_busy'] = 					"antwoorden in gesprek";
$lang_str['cs_fw_to_voicemail'] = 				"doorsturen naa voicemail";


/* ------------------------------------------------------------*/
/*      registration                                           */
/* ------------------------------------------------------------*/

$lang_str['fe_not_accepted_terms'] = 			"Uw moet de algemene voorwaarden accepteren";
$lang_str['choose_timezone'] = 					"--- selecteer uw tijdzone ---";
$lang_str['fe_not_choosed_timezone'] = 			"selecteer uw tijdzone SVP";
$lang_str['fe_uname_not_follow_conventions'] = 	"gebruikersnaam voldoet niet aan de regels";
$lang_str['fe_not_filled_password'] = 			"u moet een wachtwoord invullen";
$lang_str['fe_not_filled_your_fname'] = 		"u moet uw voornaam opgeven";
$lang_str['fe_not_filled_your_lname'] = 		"u moet uw achternaam opgeven";
$lang_str['fe_uname_already_choosen_1'] = 		"Sorry, deze gebruikersnaam ";
$lang_str['fe_uname_already_choosen_2'] = 		"is reeds in gebruik. Probeer opnieuw met een andere naam";
$lang_str['err_sending_mail'] = 				"Sorry, er is een fout opgetreden tijdens het versturen van email. Probeer het later nogmaals";
$lang_str['registration_introduction'] = 		"Om te registreren moet u onderstaande gegevens invullen en versturen. U ontvangt van ons een email om u registratie te bevestigen. Neem contact op via <a href=\"mailto:".$config->regmail."\">".$config->regmail."</a> voor vragen en opmerkingen over onze gratus trial SIP dienst.";
$lang_str['reg_email_desc'] = 					"Het email adres waar wij de bevestiging van uw registratie naar toe sturen. Indien het email adres ongeldig is kan er GEEN registratie voor onze SIP dienst plaatsvinden.)";
$lang_str['ff_phone'] = 						"telefoonnummer";
$lang_str['reg_phone_desc'] = 					"Dit is uw telefoonnummer waarop u bereikbaar bent.";
$lang_str['ff_pick_username'] = 				"kies uw gebruikersnaam";
$lang_str['reg_username_desc'] = 				"Uw SIP adres wordt gebruikersnaam@".$config->domain.". dit geeft aan dat u via uw gebruikersnaam bereikbaar bent. U mag zelf een keuze maken, bijvoorbeeld door een nummer te kiezen beginnend met '8' (e.g., '8910') of een naam en nummer combinatie zoals (klaas.bruinsma13). Bij het configureren van uw telefoon heeft u uw gebruikersnaam nodig!";
$lang_str['ff_pick_password'] = 				"kies uw wachtwoord";
$lang_str['reg_password_desc'] = 				"Vergeet uw wachtwoord niet, dit heeft u nodig om uw telefoon te configureren!";
$lang_str['ff_confirm_password'] = 				"bevestig uw wachtwoord";
$lang_str['ff_terms_and_conditions'] = 			"leveringsvoorwaarden";
$lang_str['ff_i_accept'] = 						"accepteren";
$lang_str['l_back_to_loginform'] = 				"terug naar login";

/* ------------------------------------------------------------*/
/*      registration - finished                                */
/* ------------------------------------------------------------*/

$lang_str['reg_finish_thanks'] = 				"Onze hartelijke dank voor uw registratie bij ".$config->domain;
$lang_str['reg_finish_app_forwarded'] = 		"Your application was forwarded for approval.";
$lang_str['reg_finish_confirm_msg'] = 			"U ontvangt spoedig een bevestigingsbericht van ons.";
$lang_str['reg_finish_sip_address'] = 			"Wij hebben het onderstaande SIP adres voor u gereserveerd:";
$lang_str['reg_finish_questions'] = 			"Voor vragen en opmerking maakt u gebruik van onderstaande link";
$lang_str['reg_finish_infomail'] = 				"<a href=\"mailto:".$config->infomail."\">".$config->infomail."</a>.";

/* ------------------------------------------------------------*/
/*      registration - confirmation                            */
/* ------------------------------------------------------------*/

$lang_str['reg_conf_congratulations'] = 		"Gefeliciteerd! Uw ".$config->domain." account is aangemaakt!";
$lang_str['reg_conf_set_up'] = 					"Uw ".$config->domain." account is aangemaakt!";
$lang_str['reg_conf_jabber_failed'] = 			"Helaas uw ".$config->domain." Jabber Gateway registratie is mislukt.";
$lang_str['reg_conf_contact_infomail'] = 		"Neem contact op met <a href=\"mailto:".$config->infomail."\">".$config->infomail."</a> voor verdere ondersteuning.";
$lang_str['reg_conf_failed'] = 					"Helaas uw ".$config->domain." bevestiging is mislukt.";
$lang_str['err_reg_conf_not_exists_conf_num'] = "Sorry. Dit nummer komt niet voor op onze server!";
$lang_str['err_reg_conf_already_created'] = 	"Uw account was al aangemakt";

/* ------------------------------------------------------------*/
/*      registration - forgot password                         */
/* ------------------------------------------------------------*/

$lang_str['forgot_pass_head'] = 				"Wachtwoord vergeten?";
$lang_str['forgot_pass_introduction'] = 		"Indien u uw wachtwoord niet meer weet, vul dan hieronder uw gebruikersnaam in. U ontvangt uw wachtwoord per email op het email adres van uw registratie!";
$lang_str['forgot_pass_sended'] = 				"Een nieuw wachtwoord is aangemaakt en is naar u onderweg.";
$lang_str['msg_pass_conf_sended_s'] = 			"Login informatie verstuurd";
$lang_str['msg_pass_conf_sended_l'] = 			"Uw login informatie is verstuurd naar uw email adres";
$lang_str['msg_password_sended_s'] = 			"Nieuw wachtwoord is verzonden";
$lang_str['msg_password_sended_l'] = 			"Nieuw wachtwoord is verstuurd naar uw email adres";
$lang_str['err_no_user'] = 						"Sorry, deze gebruiker is niet bij ons geregistreerd!";

/* ------------------------------------------------------------*/
/*      admin - users management                               */
/* ------------------------------------------------------------*/

$lang_str['err_admin_can_not_delete_user_1'] = 	"Geen rechten om gebruikers te verwijderen";
$lang_str['err_admin_can_not_delete_user_2'] = 	"deze gebruiker is van een ander domein";
$lang_str['msg_acl_updated_s'] = 				"ACL gewijzigd";
$lang_str['msg_acl_updated_l'] = 				"Rechtentabel van gebruikers is gewijzigd";
$lang_str['msg_user_deleted_s'] = 				"Gebruiker verwijderd";
$lang_str['msg_user_deleted_l'] = 				"Gebruiker is verwijderd";
$lang_str['th_phone'] = 						"telefoonphone";
$lang_str['l_acl'] = 							"ACL";
$lang_str['l_aliases'] = 						"alias";
$lang_str['l_account'] = 						"gebruiker";
$lang_str['l_accounting'] = 					"rekening";

/* ------------------------------------------------------------*/
/*      admin - ACL, aliases                                   */
/* ------------------------------------------------------------*/

$lang_str['access_control_list_of_user'] = 		"Rechtentabel voor gebruikers";
$lang_str['have_not_privileges_to_acl'] = 		"U heeft geen rechten voor ACL";
$lang_str['err_alias_already_exists_1'] = 		"De alias:";
$lang_str['err_alias_already_exists_2'] = 		"is reeds aanwezig";
$lang_str['msg_alias_deleted_s'] = 				"Alias verwijderd";
$lang_str['msg_alias_deleted_l'] = 				"De alias van deze gebruiker is verwijderd";
$lang_str['msg_alias_updated_s'] = 				"Alias gewijzigd";
$lang_str['msg_alias_updated_l'] = 				"Uw wijziging is opgeslagen";
$lang_str['msg_alias_added_s'] = 				"Alias toegevoegd";
$lang_str['msg_alias_added_l'] = 				"De Alias is bij de gebruiker toegevoegd";
$lang_str['change_aliases_of_user'] = 			"Wijzig de alias van gebruiker";
$lang_str['ff_alias'] = 						"alias";
$lang_str['th_alias'] = 						"alias";
$lang_str['realy_you_want_delete_this_alias'] = "Alias verwijderen, weet u het zeker?";
$lang_str['user_have_not_any_aliases'] = 		"Gebruiker heeft geen alias";

/* ------------------------------------------------------------*/
/*      admin privileges                                       */
/* ------------------------------------------------------------*/

$lang_str['admin_privileges_of'] = 				"Admininistrator rechten staan uit";
$lang_str['admin_competence'] = 				"admin competence";
$lang_str['ff_is_admin'] = 						"is admin";
$lang_str['ff_change_privileges'] = 			"wijzig rechten van administrators";
$lang_str['acl_control'] = 						"ACL controle";
$lang_str['msg_privileges_updated_s'] = 		"Rechten gewijzigd";
$lang_str['msg_privileges_updated_l'] = 		"De rechten van de gebruiker zijn gewijzigd";
$lang_str['list_of_users'] = 					"Gebruikersoverzicht";
$lang_str['th_domain'] = 						"domein";
$lang_str['l_change_privileges'] = 				"wijzig rechten";
$lang_str['ff_domain'] = 						"domein";
$lang_str['ff_show_admins_only'] = 				"alleen administrators";

/* ------------------------------------------------------------*/
/*      server monitoring                                      */
/* ------------------------------------------------------------*/

$lang_str['ser_moni_current'] = 				"huidig";
$lang_str['ser_moni_average'] = 				"gemiddeld";
$lang_str['ser_moni_waiting_cur'] = 			"onderweg huidig";
$lang_str['ser_moni_waiting_avg'] = 			"onderweg gemiddeld";
$lang_str['ser_moni_total_cur'] = 				"totaal huidig";
$lang_str['ser_moni_total_avg'] = 				"totaal gemiddeld";
$lang_str['ser_moni_local_cur'] = 				"lokaal huidig";
$lang_str['ser_moni_local_avg'] = 				"lokaal gemiddeld";
$lang_str['ser_moni_replies_cur'] = 			"replied localy huidig";
$lang_str['ser_moni_replies_avg'] = 			"replied localy gemiddeld";
$lang_str['ser_moni_registered_cur'] = 			"geregistreerd huidig";
$lang_str['ser_moni_registered_avg'] = 			"geregistreerd gemiddeld";
$lang_str['ser_moni_expired_cur'] = 			"verlopen huidig";
$lang_str['ser_moni_expired_avg'] = 			"verlopen gemiddeld";
$lang_str['ser_moni_general_values'] = 			"totaalwaarde";
$lang_str['ser_moni_diferencial_values'] = 		"verschilwaarde";
$lang_str['ser_moni_transaction_statistics'] = 	"Transactiestatistiek";
$lang_str['ser_moni_completion_status'] = 		"Compleet status";
$lang_str['ser_moni_stateless_server_statis'] = "Serverstatistiek";
$lang_str['ser_moni_usrLoc_stats'] = 			"Gebruikers (Loc) Statistiek";
$lang_str['domain'] = 							"domein";


$lang_str['fe_not_filled_name_of_attribute'] = 	"geef attribuutnaam op";
$lang_str['ff_att_name'] = 						"attribuut naam";
$lang_str['ff_att_type'] = 						"attribuut type";
$lang_str['ff_att_default_value'] = 			"standaard waarde";
$lang_str['th_att_name'] = 						"attribuut naam";
$lang_str['th_att_type'] = 						"attribuut type";
$lang_str['th_att_default_value'] = 			"standaard waarde";
$lang_str['fe_not_filled_item_label'] = 		"geeft item label op";
$lang_str['fe_not_filled_item_value'] = 		"geeft item waarde op";
$lang_str['edit_items_of_the_list'] = 			"wijzig item uit de lijst";
$lang_str['ff_item_label'] = 					"item label";
$lang_str['ff_item_value'] = 					"item waarde";
$lang_str['ff_set_as_default'] = 				"zet als standaard waarde";
$lang_str['th_item_label'] = 					"item label";
$lang_str['th_item_value'] = 					"item waarde";
$lang_str['l_back_to_editing_attributes'] = 	"terug naar wijzigen attributen";


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

$lang_str['sel_item_all_calls'] = 				"Alle berichten";
$lang_str['sel_item_outgoing_calls'] = 			"Uitgaand";
$lang_str['sel_item_incoming_cals'] = 			"Inkomend";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['fe_no_greeeting_file'] = 			"u heeft geen welkomsbericht geselecteerd";
$lang_str['fe_invalid_greeting_file'] = 		"fout in welkomsberichtenbestand";
$lang_str['fe_greeting_file_no_wav'] = 			"het type bestand moet audio/wav zijn";
$lang_str['fe_greeting_file_too_big'] = 		"het bestand is te groot";
$lang_str['msg_greeting_stored_s'] = 			"Bestand opgeslagen";
$lang_str['msg_greeting_stored_l'] = 			"Uw welkomsbericht is opgeslagen";
$lang_str['msg_greeting_deleted_s'] = 			"Betand verwijderd";
$lang_str['msg_greeting_deleted_l'] = 			"Het bestand is verwijderd";

/* ------------------------------------------------------------*/
/*      whitelist                                              */
/* ------------------------------------------------------------*/

$lang_str['err_whitelist_already_exists'] = 	"Dit item is reeds aanwezig";

?>