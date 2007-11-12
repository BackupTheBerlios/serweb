<?
/*
 * $Id: dutch-utf-8.php,v 1.48 2007/11/12 12:45:05 kozlik Exp $
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
$lang_str['l_extended'] = 						"extended";	//to translate 
$lang_str['l_change'] = 						"wijzigen";
$lang_str['l_delete'] = 						"verwijderen";
$lang_str['l_back_to_main'] = 					"terug";
$lang_str['l_back'] = 							"terug";
$lang_str['l_disable'] = 						"uit";
$lang_str['l_enable'] = 						"aan";
$lang_str['l_disable_all'] = 					"alles uit";
$lang_str['l_enable_all'] = 					"alles aan";
$lang_str['status_unknown'] = 					"onbekend";
$lang_str['status_nonlocal'] = 					"niet lokaal";
$lang_str['status_nonexists'] = 				"non-existent";
$lang_str['status_online'] = 					"on line";
$lang_str['status_offline'] = 					"off line";
$lang_str['search_filter'] = 					"filter";
$lang_str['showed_users'] = 					"Overzicht gebruikers";
$lang_str['displaying_records'] = 				"Overzicht records";
$lang_str['no_users_found'] = 					"Geen gebruikers gevonden";
$lang_str['no_records_found'] = 				"Geen records aanwezig";
$lang_str['none'] = 							"geen";
$lang_str['warning'] = 							"Waarschuwing!";
$lang_str['domain'] = 							"domein";
$lang_str['yes'] = 								"Ja";
$lang_str['no'] = 								"Nee";
$lang_str['not_exists'] = 						"niet aanwezig";
$lang_str['filter_wildcard_note'] =             "You could use '*' and '?' wildcards in the filter fields";	//to translate 

/* ------------------------------------------------------------*/
/*      error messages                                         */
/* ------------------------------------------------------------*/

$lang_str['fe_not_valid_email'] =	 			"ongeldig email adres";
$lang_str['fe_is_not_valid_email'] =	 		"ongeldig email adres";
$lang_str['fe_not_valid_sip'] = 				"ongeldig sip adres";
$lang_str['fe_not_valid_phonenumber'] = 		"ongeldig telefoonnummer";
$lang_str['fe_not_filled_sip'] = 				"u moet een SIP adres invullen";
$lang_str['fe_passwords_not_match'] =			"wachtwoord niet gelijk";
$lang_str['fe_not_filled_username'] = 			"U moet een gebruikersnaam invullen";
$lang_str['fe_not_allowed_uri'] = 				"ongeldig SIP adres";
$lang_str['fe_max_entries_reached'] = 			"Maximum aantal pogingen";
$lang_str['fe_not_valid_username'] = 			"gebruikersnaam ongeldig";
$lang_str['fe_not_valid_domainname'] = 			"domeinnaam ongeldig";

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
$lang_str['b_cancel'] =		 					"Stop";
$lang_str['b_select'] =		 					"Selecteer";
$lang_str['b_test_firewall_NAT'] =		 		"Test firewall/NAT";
$lang_str['b_upload_greeting'] =		 		"Upload welkomsbericht";
$lang_str['b_extended_settings'] =		 		"Extra settings";
$lang_str['b_search'] =		 					"Zoeken";


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

$lang_str['tab_users'] =	 					"gebruikers";
$lang_str['tab_admin_privileges'] =	 			"admin rechten";
$lang_str['tab_domains'] =	 					"domein";
$lang_str['tab_customers'] =	 				"klanten";
$lang_str['tab_global_attributes'] =	 		"globale parameters";
$lang_str['tab_attr_types'] =	 				"soort parameters";

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
$lang_str['ff_language'] = 						"taal";
$lang_str['ff_reg_confirmation'] = 				"bevestiging voor juiste registratie";
$lang_str['ff_uid'] = 							"uid";
$lang_str['ff_for_ser'] = 						"voor SER";
$lang_str['ff_for_serweb'] = 					"voor SerWeb";
$lang_str['ff_contact_email'] = 				"contact email";

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
$lang_str['th_uid'] = 							"uid";

/* ------------------------------------------------------------*/
/*      login messages                                         */
/* ------------------------------------------------------------*/

$lang_str['bad_username'] = 					"Ongeldige gebruikersnaam of wachtwoord";
$lang_str['account_disabled'] = 				"Uw account is uitgeschakeld";
$lang_str['domain_not_found'] = 				"Uw doemian is niet gevonden";
$lang_str['msg_logout_s'] = 					"U bent uitgelogd";
$lang_str['msg_logout_l'] = 					"U bent uitgelogd, om opnieuw in te loggen, toets hieronder uw gebruikersnaam en wachtwood in";
$lang_str['userlogin'] = 						"Gebruikers login";
$lang_str['adminlogin'] = 						"Administrator login";
$lang_str['enter_username_and_passw'] = 		"Login met gebruikersnaam en wachtwoord";
$lang_str['ff_password'] = 						"Wachtwoord";
$lang_str['l_forgot_passw'] = 					"Wachtwoord vergeten?";
$lang_str['l_register'] = 						"Inschrijven!";
$lang_str['l_have_my_domain'] = 				"Have-my-domain!";	//to translate 
$lang_str['remember_uname'] = 					"Login gegevens onthouden op de computer?";
$lang_str['session_expired'] = 					"Sessie verlopen";
$lang_str['session_expired_relogin'] = 			"Uw sessie is verlopen, opnieuw inloggen.";

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
$lang_str['acl_err_local_forward'] = 			"doorsturen verboden";
$lang_str['acl_err_gateway_forward'] = 			"gateway contacten verboden";

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
/*      attributes                                             */
/* ------------------------------------------------------------*/

$lang_str['fe_invalid_value_of_attribute'] = 	"ongeldige waarde voor dit attribuut";
$lang_str['fe_is_not_number'] = 				"ongeldig nummer";
$lang_str['fe_is_not_sip_adr'] = 				"ongeldig sip adres";
$lang_str['no_attributes_defined'] = 			"Geen rechten gegeven door de beheerder";

$lang_str['ff_send_daily_missed_calls'] =		"verstuur dagelijks mijn gemiste berichten naar mijn emailadres";

$lang_str['ff_uri_def_f'] =						"standaard waarden voor uri";
$lang_str['ff_credential_def_f'] =				"standaard gegevns";
$lang_str['ff_domain_def_f'] =					"standaard waarden voor domein";

$lang_str['attr_fwd_busy_target'] =				"destination for on-busy forwarding";	//to translate 
$lang_str['attr_fwd_noanswer_target'] =			"destination for on-no-answer forwarding";	//to translate 
$lang_str['attr_fwd_always_target'] =			"unconditional call forwarding target";	//to translate 


/* ------------------------------------------------------------*/
/*      speed dial                                             */
/* ------------------------------------------------------------*/

$lang_str['th_speed_dial'] = 					"Verkorte code";
$lang_str['th_new_uri'] = 						"Nieuwe uri";




/* ------------------------------------------------------------*/
/*      registration                                           */
/* ------------------------------------------------------------*/

$lang_str['fe_not_accepted_terms'] = 			"Uw moet de algemene voorwaarden accepteren";
$lang_str['choose_timezone'] = 					"--- selecteer uw tijdzone ---";
$lang_str['choose_timezone_of_user'] = 			"--- selecteer uw tijdzone voor deze gebruiker ---";
$lang_str['fe_not_choosed_timezone'] = 			"selecteer uw tijdzone SVP";
$lang_str['fe_uname_not_follow_conventions'] = 	"gebruikersnaam voldoet niet aan de regels";
$lang_str['fe_not_filled_password'] = 			"u moet een wachtwoord invullen";
$lang_str['fe_not_filled_your_fname'] = 		"u moet uw voornaam opgeven";
$lang_str['fe_not_filled_your_lname'] = 		"u moet uw achternaam opgeven";
$lang_str['fe_uname_already_choosen_1'] = 		"Sorry, deze gebruikersnaam ";
$lang_str['fe_uname_already_choosen_2'] = 		"is reeds in gebruik. Probeer opnieuw met een andere naam";
$lang_str['err_sending_mail'] = 				"Sorry, er is een fout opgetreden tijdens het versturen van email. Probeer het later nogmaals";
$lang_str['registration_introduction_1'] = 		"Voor registratie, vul het formulier in. U ontvangt een email om uw registratie te bevestigen.";
$lang_str['registration_introduction_2'] = 		"heeft u vragen over uw gratis trial SIP services.";
$lang_str['reg_email_desc'] = 					"Het email adres waar wij de bevestiging van uw registratie naar toe sturen. Indien het email adres ongeldig is kan er GEEN registratie voor onze SIP dienst plaatsvinden.)";
$lang_str['reg_email_uname_desc'] = 			"Your SIP address will be same as your email address. Subscription confirmation request will be sent to this address. (If an invalid address is given, no confirmation will be sent and no SIP account will be created.) Your email address have to be from domain ".$config->domain.".";	//to translate 
$lang_str['ff_phone'] = 						"telefoonnummer";
$lang_str['reg_phone_desc'] = 					"Dit is uw telefoonnummer waarop u bereikbaar bent.";
$lang_str['ff_pick_username'] = 				"kies uw gebruikersnaam";
$lang_str['reg_username_desc'] = 				"Uw SIP adres wordt gebruikersnaam@".$config->domain.". dit geeft aan dat u via uw gebruikersnaam bereikbaar bent. U mag zelf een keuze maken, bijvoorbeeld door een nummer te kiezen beginnend met '8' (e.g., '8910') of een naam en nummer combinatie zoals (klaas.bruinsma13). Bij het configureren van uw telefoon heeft u uw gebruikersnaam nodig!";
$lang_str['ff_pick_password'] = 				"kies uw wachtwoord";
$lang_str['reg_password_desc'] = 				"Vergeet uw wachtwoord niet, dit heeft u nodig om uw telefoon te configureren!";
$lang_str['ff_confirm_password'] = 				"bevestig uw wachtwoord";
$lang_str['ff_terms_and_conditions'] = 			"leveringsvoorwaarden";
$lang_str['ff_i_accept'] = 						"accepteren";
$lang_str['ff_timezone'] = 						"tijdzone";
$lang_str['ff_uname_assign_mode'] =             "Username assignment mode";	//to translate 
$lang_str['l_back_to_loginform'] = 				"terug naar login";
$lang_str['msg_user_registered_s'] = 			"Gebruiker geregistreerd";
$lang_str['msg_user_registered_l'] = 			"Nieuwe gebruiker is geregistreerd";
$lang_str['register_new_user'] = 				"registreer nieuwe gebruiker";
$lang_str["err_domain_of_email_not_match"] =    "Your email address is not from same domain as into which you are registering";

/* ------------------------------------------------------------*/
/*      registration - finished                                */
/* ------------------------------------------------------------*/

$lang_str['reg_finish_thanks'] = 				"Onze hartelijke dank voor uw registratie bij ".$config->domain;
$lang_str['reg_finish_app_forwarded'] = 		"Your application was forwarded for approval.";
$lang_str['reg_finish_confirm_msg'] = 			"U ontvangt spoedig een bevestigingsbericht van ons.";
$lang_str['reg_finish_sip_address'] = 			"Wij hebben het onderstaande SIP adres voor u gereserveerd:";
$lang_str['reg_finish_questions_1'] = 			"Heeft u aanvullende vragen stuur";	//to translate 
$lang_str['reg_finish_questions_2'] = 			"een email aan";	//to translate 

/* ------------------------------------------------------------*/
/*      registration - confirmation                            */
/* ------------------------------------------------------------*/

$lang_str['reg_conf_congratulations'] = 		"Gefeliciteerd! Uw ".$config->domain." account is aangemaakt!";
$lang_str['reg_conf_set_up'] = 					"Uw ".$config->domain." account is aangemaakt!";
$lang_str['reg_conf_jabber_failed'] = 			"Helaas uw ".$config->domain." Jabber Gateway registratie is mislukt.";
$lang_str['reg_conf_contact_infomail_1'] = 		"Neem contact op";
$lang_str['reg_conf_contact_infomail_2'] = 		"voor ondersteuning.";
$lang_str['reg_conf_failed'] = 					"Helaas uw ".$config->domain." bevestiging is mislukt.";
$lang_str['reg_conf_nr_not_exists'] = 			"Uw bevestiging is onjuiste of deze account is reeds aanwezig!";
$lang_str['err_reg_conf_not_exists_conf_num'] = "Sorry. Dit nummer komt niet voor op onze server!";

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
$lang_str['realy_you_want_delete_this_user'] =	"Deze gebruiker verwijderen?";
$lang_str['l_credentials'] = 					"gegevens";
$lang_str['l_uris'] = 					        "SIP URIs";	//to translate 
$lang_str['user_has_no_credentials'] = 			"Gebruiker heeft geen gegevens";
$lang_str['user_has_no_sip_uris'] = 			"User has no SIP URIs";	//to translate 

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
$lang_str['ff_is_canon'] = 						"is canonical";	//to translate 
$lang_str['ff_is_enabled'] = 					"staat aan";
$lang_str['ff_uri_is_to'] = 					"gebruik 'aan' uri";
$lang_str['ff_uri_is_from'] = 					"gebruik 'van' uri";
$lang_str['th_is_canon'] = 						"canonical";	//to translate 
$lang_str['th_uri_is_to'] = 					"aan";
$lang_str['th_uri_is_from'] = 					"van";
$lang_str['l_ack'] = 							"bevestig";
$lang_str['l_deny'] = 							"deny";	//to translate 
$lang_str['uris_with_same_uname_did'] = 		"bestaande URIs met dezelfde gebruikersnaam en domein";
$lang_str['ack_values'] = 						"Bevestig waarden";
$lang_str['uri_already_exists'] = 				"URI met deze gebruikersnaam en domein zijn al aanwezig. Bevestig deze waarden.";
$lang_str['is_to_warning'] = 					"LET OP: waarde 'AAN' is in gebruik voor een andere URI. Doorgaan, verwijderd de waarde in URI";
$lang_str['err_canon_uri_exists'] = 			"Kan deze waarde voor URI niet wijzigen";
$lang_str['uid_with_alias'] = 					"Overzicht UID met alias";

/* ------------------------------------------------------------*/
/*      admin privileges                                       */
/* ------------------------------------------------------------*/

$lang_str['admin_privileges_of'] = 				"Admininistrator rechten staan uit";
$lang_str['admin_competence'] = 				"admin rechten";
$lang_str['ff_is_admin'] = 						"is admin";
$lang_str['ff_change_privileges'] = 			"wijzig rechten van administrators";
$lang_str['ff_is_hostmaster'] = 				"is hostmaster";
$lang_str['acl_control'] = 						"ACL controle";
$lang_str['msg_privileges_updated_s'] = 		"Rechten gewijzigd";
$lang_str['msg_privileges_updated_l'] = 		"De rechten van de gebruiker zijn gewijzigd";
$lang_str['list_of_users'] = 					"Gebruikersoverzicht";
$lang_str['th_domain'] = 						"domein";
$lang_str['l_change_privileges'] = 				"rechten";
$lang_str['ff_domain'] = 						"domein";
$lang_str['ff_realm'] = 						"realm";
$lang_str['th_realm'] = 						"realm";
$lang_str['ff_show_admins_only'] = 				"alleen administrators";
$lang_str['err_cant_ch_priv_of_hostmaster'] = 	"Deze gebruiker is hostmaster. U mag de rechten van de hostmaster niet wijzigen omdat u geen hostmaster rechten heeft!";


/* ------------------------------------------------------------*/
/*      attribute types                                        */
/* ------------------------------------------------------------*/

$lang_str['fe_not_filled_name_of_attribute'] = 	"geef attribuutnaam op";
$lang_str['fe_empty_not_allowed'] = 			"mag niet leeg zijn";
$lang_str['ff_order'] = 						"volgorde";
$lang_str['ff_att_name'] = 						"attribuut naam";
$lang_str['ff_att_type'] = 						"attribuut type";
$lang_str['ff_att_access'] = 					"toegang";
$lang_str['ff_label'] = 						"label";
$lang_str['ff_att_group'] = 					"group";	//to translate 
$lang_str['ff_att_uri'] = 						"uri";	//to translate 
$lang_str['ff_att_user'] = 						"gebruiker";
$lang_str['ff_att_domain'] = 					"domein";
$lang_str['ff_att_global'] = 					"global";
$lang_str['ff_multivalue'] = 					"meerdere waarden";
$lang_str['ff_att_reg'] = 						"verplicht bij registratie";
$lang_str['ff_att_req'] = 						"verplicht (niet leeg)";
$lang_str['ff_fr_timer'] = 						"timer";
$lang_str['ff_fr_inv_timer'] = 					"uitnodiging timer";
$lang_str['ff_uid_format'] = 					"formaat voor nieuwe UIDs";
$lang_str['ff_did_format'] = 					"formaat voor nieuwe DIDs";

$lang_str['at_access_0'] = 						"volledige toegang";
$lang_str['at_access_1'] = 						"alleen lezen voor gebruikers";
$lang_str['at_access_3'] = 						"alleen voor administrators (R/W)";


$lang_str['th_att_name'] = 						"attribuut naam";
$lang_str['th_att_type'] = 						"attribuut type";
$lang_str['th_order'] = 						"volgorde";
$lang_str['th_label'] = 						"label";
$lang_str['th_att_group'] = 					"group";	//to translate 
$lang_str['fe_order_is_not_number'] = 			"'volgorde' ongeldig nummer";

$lang_str['fe_not_filled_item_label'] = 		"geeft item label op";
$lang_str['fe_not_filled_item_value'] = 		"geeft item waarde op";
$lang_str['ff_item_label'] = 					"item label";
$lang_str['ff_item_value'] = 					"item waarde";
$lang_str['th_item_label'] = 					"item label";
$lang_str['th_item_value'] = 					"item waarde";
$lang_str['l_back_to_editing_attributes'] = 	"terug naar wijzigen attributen";
$lang_str['realy_want_you_delete_this_attr'] = 	"verwijder dit attribuut?";
$lang_str['realy_want_you_delete_this_item'] = 	"Verwijder dit onderdeel?";


$lang_str['attr_type_warning'] = 				"Op deze pagina kunt u attributen, types en parameters wijzigen. Deze worden intern gebruikt door SerWeb en SER. NIET WIJZIGEN als u niet weet hoe dit werkt!!!";
$lang_str['at_hint_order'] = 					"Attributen staan op volgorde van SerWeb";
$lang_str['at_hint_label'] = 					"Label en attributen staan in volgorde van SerWeb. Als deze start met een '@', de waarde wordt vertaald in de taal in de directory 'lang'. Het is uw verantwoording dat deze waarden aanwezig zijn voor uw eigen taal.";
$lang_str['at_hint_for_ser'] = 					"Attribute wordt geladen door SER. Alleen nieuwe attributen hebben hier enig effect.";
$lang_str['at_hint_for_serweb'] = 				"Attribute wordt geladen door SerWeb. Alleen nieuwe attributen hebben hier enig effect.";
$lang_str['at_hint_user'] = 					"Attribute is zichtbaar op gebruikers pagina";
$lang_str['at_hint_domain'] = 					"Attribute is zichtbaar op domein parameter pagina";
$lang_str['at_hint_global'] = 					"Attribute is zichtbaar op global parameter pagina";
$lang_str['at_hint_multivalue'] = 				"Attribute mag meerdere waarden hebben";
$lang_str['at_hint_registration'] = 			"Attribute is zichtbaar op gebruikersregistratie pagina";
$lang_str['at_hint_required'] = 				"Attribute mag geen lege velden bevatten. Geld niet voor types: int, email_adr, sip_adr, etc.";


$lang_str['ff_att_default_value'] = 			"standaard waarde";
$lang_str['th_att_default_value'] = 			"standaard waarde";
$lang_str['ff_set_as_default'] = 				"zet als standaard waarde";
$lang_str['edit_items_of_the_list'] = 			"wijzig item uit de lijst";

$lang_str['o_lang_not_selected'] = 				"niet geselecteerd";

$lang_str['at_int_title'] = 					"Change extended settings of int attribute";	//to translate 
$lang_str['ff_at_int_min'] = 					"min value";	//to translate 
$lang_str['ff_at_int_max'] = 					"max value";	//to translate 
$lang_str['ff_at_int_err'] = 					"error message";	//to translate 

$lang_str['ff_at_int_min_hint'] = 				"Minimum allowed value. Leave this field empty to disable check.";	//to translate 
$lang_str['ff_at_int_max_hint'] = 				"Maximum allowed value. Leave this field empty to disable check.";	//to translate 
$lang_str['ff_at_int_err_hint'] = 				"Customize error message displayed when value is not in specified range. Leave this field empty for default error message. If message starts with '@', the string is translated into user language with files in directory 'lang'. It is your responsibility that all used phrases are present in files for all languages.";	//to translate 

$lang_str['at_import_title'] = 					"Import attribute types";	//to translate 
$lang_str['ff_xml_file'] = 					    "XML file";	//to translate 
$lang_str['ff_at_import_purge'] = 				"Purge all attribute types before importing new ones";	//to translate 
$lang_str['ff_at_import_exists'] = 				"What to do when an attribute type already exists?";	//to translate 
$lang_str['ff_at_import_skip'] = 				"Skip it";	//to translate 
$lang_str['ff_at_import_update'] = 				"Replace existing attribute type with a new one";	//to translate 

$lang_str['fe_file_too_big'] =                  "File is too big";	//to translate 
$lang_str['fe_at_no_xml_file'] =                "Missing XML file";	//to translate 
$lang_str['fe_at_invalid_sml_file'] =           "XML file is not valid";	//to translate 
$lang_str['fe_at_xml_file_type'] =              "Given file is not xml";	//to translate 

$lang_str['err_at_int_range'] = 				"must be in interval %d and %d";	//to translate 
$lang_str['err_at_int_range_min'] = 			"must be great then %d";	//to translate 
$lang_str['err_at_int_range_max'] = 			"must be less then %d";	//to translate 

$lang_str['attr_grp_general'] = 				"general";	//to translate 
$lang_str['attr_grp_privacy'] = 				"privacy";	//to translate 
$lang_str['attr_grp_other'] = 					"other";	//to translate 
$lang_str['err_at_grp_empty'] = 				"Attribute group can't be empty";	//to translate 
$lang_str['attr_grp_create_new'] = 				"create new group";	//to translate 


$lang_str['l_attr_grp_toggle'] = 				"toggle displaying of attribute groups";	//to translate 
$lang_str['l_export_sql'] = 				    "export to SQL script";	//to translate 
$lang_str['l_export_xml'] = 				    "export to XML file";	//to translate 
$lang_str['l_import_xml'] = 				    "import from XML file";	//to translate 

$lang_str['msg_at_imported_s'] =                "Attribute types imported";	//to translate 
$lang_str['msg_at_imported_l'] =                "Attribute types has been successfully imported";	//to translate 

/* ------------------------------------------------------------*/
/*      credentials                                            */
/* ------------------------------------------------------------*/


$lang_str['change_credentials_of_user'] = 		"Wijzig gebruiker parameters";

$lang_str['th_password'] = 						"wachtwoord";
$lang_str['th_for_ser'] = 						"voor SER";
$lang_str['th_for_serweb'] = 					"voor SerWeb";

$lang_str['err_credential_changed_domain'] = 	"Domein van gebruiker is gewijzigd. Vul een nieuw wachtwoord in";
$lang_str['warning_credential_changed_domain'] =		"Serweb is streng in wachtwoorden. Dit betekend, als u het domein van een gebruiker wil wijzigen u ook het wachtwoord moet wijzigen. Anders is het wachtwoord niet geldig.";

$lang_str['realy_want_you_delete_this_credential'] = 	"Verwijder deze gegevens?";


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

/* ------------------------------------------------------------*/
/*      multidomain                                            */
/* ------------------------------------------------------------*/

$lang_str['fe_not_customer_name'] = 			"U moet de naam van de klant invoeren";
$lang_str['ff_customer_name'] = 				"naam van de klant";
$lang_str['no_customers'] = 					"Geen klant";
$lang_str['customer'] = 						"Klant";

$lang_str['msg_customer_updated_s'] = 			"Klant gewijzigd";
$lang_str['msg_customer_updated_l'] = 			"Klant naam gewijzigd";
$lang_str['msg_customer_deleted_s'] = 			"Klant verwijderd";
$lang_str['msg_customer_deleted_l'] = 			"Klant is verwijderd";
$lang_str['msg_customer_added_s'] = 			"Klant aangemaakt";
$lang_str['msg_customer_added_l'] = 			"Nieuwe klant is aangemaakt";
$lang_str['err_customer_own_domains'] = 		"Klant heeft een domein, verwijderen kan niet";

$lang_str['d_id'] = 							"Domein ID";
$lang_str['d_name'] = 							"Domein naam";
$lang_str['list_of_domains'] = 					"Overzicht van domeinen";
$lang_str['showed_domains'] = 					"Lijst domeinen";
$lang_str['no_domains_found'] = 				"Geen domeinen aanwezig";
$lang_str['new_dom_name'] = 					"Nieuwe domeinnaam toevoegen";
$lang_str['owner'] = 							"Eigenaar";

$lang_str['realy_delete_domain'] = 				"Verwijder dit domein?";
$lang_str['l_create_new_domain'] = 				"domein toevoegen";
$lang_str['l_reload_ser'] = 					"opnieuw laden, SER en webserver";
$lang_str['no_domain_name_is_set'] = 			"minimaal 1 domeinnaam toevoegen";
$lang_str['prohibited_domain_name'] = 			"Sorry, deze domeinnaam is niet toegestaan";
$lang_str['can_not_del_last_dom_name'] = 		"Alleen domeinnaam verwijderen mag niet";

$lang_str['msg_domain_reload_s'] = 				"Configuratie is opnieuw geladen";
$lang_str['msg_domain_reload_l'] = 				"Configuratie van SER en webserwer is opnieuw geladen";

$lang_str['msg_domain_deleted_s'] = 			"Domein verwijderd";
$lang_str['msg_domain_deleted_l'] = 			"Dit domein wordt niet langer ondersteund en de aanwezige records worden verwijderd. Zorg er voor dat uw DNS records niet langer naar deze server verwijzen.";

$lang_str['assigned_domains'] = 				"Toegewezen domeinen";
$lang_str['unassigned_domains'] = 				"Niet toegewezen domeinen";
$lang_str['l_assign_domain'] = 					"toegewezen domein";
$lang_str['l_unassign_domain'] = 				"niet toegewezen domein";
$lang_str['l_assign'] =                                  "toegewezen";
$lang_str['l_unassign'] =                                "niet toegewezen";
$lang_str['l_assigned_domains'] = 				"Domeinen";
$lang_str['l_change_layout'] = 					"Layout";
$lang_str['l_domain_attributes'] = 				"Attributen";
$lang_str['l_unassign_admin'] = 				"niet toegewezen admin";
$lang_str['l_set_canon'] = 						"set canonical";	//to translate 

$lang_str['admins_of_domain'] = 				"Admin van dit domein";
$lang_str['no_admins'] = 						"Geen admin";

$lang_str['ff_address'] = 						"adres";

$lang_str['lf_terms_and_conditions'] =			"voorwaarden";
$lang_str['lf_mail_register_by_admin'] = 		"mail naar gebruiker gemaakt door admin";
$lang_str['lf_mail_register'] = 				"mail bevestig registratie";
$lang_str['lf_mail_fp_conf'] = 					"mail bevestiging voor nieuw wachtwoord";
$lang_str['lf_mail_fp_pass'] = 					"mail bevestiging voor nieuw wachtwoord (wachwoord vergeten)";
$lang_str['lf_config'] = 						"domein configuratie";

$lang_str['l_toggle_wysiwyg'] = 				"gebruik WYSIWYG";
$lang_str['l_upload_images'] = 					"upload afbeelding";
$lang_str['l_back_to_default'] = 				"zet standaard inhoud terug";

$lang_str['wysiwyg_warning'] = 					"Voorzichtig met WYSIWYG editor. Prolog.html moet beginnen met &lt;body&gt; element en epilog.html moet eindigen op &lt;/body&gt; element. WYSIWYG editor verwijderd deze waarden!";

$lang_str['choose_one'] = 						"selecteer";

$lang_str['layout_files'] = 					"Layout bestanden";
$lang_str['text_files'] = 						"Tekst bestanden";

$lang_str['fe_domain_not_selected']	= 			"Domein voor gebruiker is niet geselecteerd";

$lang_str['th_old_versions'] = 					"Oude versies van dit bestand";
$lang_str['initial_ver'] = 						"begin";


$lang_str['err_dns_lookup'] =                   "Error during DNS lookup. Can not check the DNS setting";	//to translate 
$lang_str['err_no_srv_record'] =                "There is no SRV record for hostname <hostname>";	//to translate 
$lang_str['err_wrong_srv_record'] =             "SRV record(s) found, but it has wrong target host or port. Following SRV records have been found: ";	//to translate 
$lang_str['err_domain_already_hosted'] = 		"This domain is already hosted on this server";	//to translate 



/* ------------------------------------------------------------*/
/*      wizard - create new domain                             */
/* ------------------------------------------------------------*/

$lang_str['register_new_admin'] = 				"Registeer nieuwe admin voor domein";
$lang_str['assign_existing_admin'] = 			"Toewijzen bestaande admin aan domein";
$lang_str['assign_admin_to_domain'] = 			"Toewijzen admin aan domein";
$lang_str['create_new_domain'] = 				"Nieuw domein toevoegen";
$lang_str['l_create_new_customer'] = 			"Nieuwe klant toevoegen"; 
$lang_str['create_new_customer'] = 				"Nieuwe klant toevoegen"; 
$lang_str['l_close_window'] = 					"sluit window";
$lang_str['step'] = 							"stap";
$lang_str['l_select'] = 						"selecteer";
$lang_str['domain_setup_success'] = 			"Nieuwe domein setup gewijzigd!";
$lang_str['l_skip_asignment_of_admin'] = 		"toewijzen admin overslaan";

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

$lang_str['err_cant_run_host_command'] =        "Error when executing 'host' command. Can not check the DNS setting";	//to translate 
$lang_str['err_no_output_of_host_command'] =    "Error when executing 'host' command. There is no output. Can not check the DNS setting";	//to translate 
$lang_str['err_unrecognized_output_of_host'] =  "DNS is not set in correct way. Here is output of 'host' comamnd: ";	//to translate 
?>
