<?
/*
 * $Id: german-utf-8.php,v 1.4 2013/06/24 14:57:40 kozlik Exp $
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

$lang_str['user_management'] = 					"Benutzerverwaltung";
$lang_str['admin_interface'] = 					"Administrator Oberfläche";
$lang_str['user'] = 							"Benutzer";
$lang_str['from'] = 							"von";
$lang_str['no_records'] = 						"Keine Einträge";
$lang_str['l_logout'] = 						"Logout";
$lang_str['l_dele_account'] = 					"Konto löschen";
$lang_str['l_cancel'] = 						"abbrechen";
$lang_str['l_edit'] = 							"bearbeiten";
$lang_str['l_insert'] = 						"hinzufügen";
$lang_str['l_extended'] = 						"erweitert";
$lang_str['l_rename'] = 						"umbenennen";
$lang_str['l_change'] = 						"wechseln";
$lang_str['l_delete'] = 						"löschen";
$lang_str['l_undelete'] = 						"wiederherstellen";
$lang_str['l_purge'] = 	    					"endgültig entfernen";
$lang_str['l_back_to_main'] = 					"zurück zur Hauptseite";
$lang_str['l_back'] = 							"zurück";
$lang_str['l_disable'] = 						"deaktivieren";
$lang_str['l_enable'] = 						"aktivieren";
$lang_str['l_disable_all'] = 					"alle deaktivieren";
$lang_str['l_enable_all'] = 					"alle aktivieren";
$lang_str['l_generate'] = 					    "erzeugen";
$lang_str['status_unknown'] = 					"unbekannt";
$lang_str['status_nonlocal'] = 					"nicht lokal";
$lang_str['status_nonexists'] = 				"existiert nicht";
$lang_str['status_online'] = 					"online";
$lang_str['status_offline'] = 					"offline";
$lang_str['search_filter'] = 					"Filter";
$lang_str['showed_users'] = 					"Benutzer anzeigen";
$lang_str['displaying_records'] = 				"Einträge anzeigen";
$lang_str['no_users_found'] = 					"Keine Benutzer gefunden";
$lang_str['no_records_found'] = 				"Keine Einträge gefunden";
$lang_str['none'] = 							"keine";
$lang_str['warning'] = 							"Warnung!";
$lang_str['domain'] = 							"Domain";
$lang_str['yes'] = 								"JA";
$lang_str['no'] = 								"NEIN";
$lang_str['not_exists'] = 						"existiert nicht";
$lang_str['filter_wildcard_note'] =             "Sie können auch '*' und '?' in den Filterfeldern benutzen";

/* ------------------------------------------------------------*/
/*      error messages                                         */
/* ------------------------------------------------------------*/

$lang_str['fe_not_valid_email'] =	 			"Keine gültige E-Mail Adresse";
$lang_str['fe_is_not_valid_email'] =	 		"ist keine gültige E-Mail Adresse";
$lang_str['fe_not_valid_sip'] = 				"Keine gültige SIP Adresse";
$lang_str['fe_not_valid_phonenumber'] = 		"Keine gültige Telefonnummer";
$lang_str['fe_not_filled_sip'] = 				"Sie müssen eine SIP Adresse eintragen";
$lang_str['fe_passwords_not_match'] =			"Die Passwörter stimmen nicht";
$lang_str['fe_not_filled_username'] = 			"Sie müssen einen Benutzernamen eintragen";
$lang_str['fe_not_allowed_uri'] = 				"Die SIP Adresse ist unzulässig";
$lang_str['fe_max_entries_reached'] = 			"Die Maximale Anzahl der Einträge ist erreicht";
$lang_str['fe_not_valid_username'] = 			"Kein gültiger Benutzername";
$lang_str['fe_not_valid_domainname'] = 			"Kein gültiger Domainname";

/* ------------------------------------------------------------*/
/*      buttons                                                */
/* ------------------------------------------------------------*/

$lang_str['b_add'] =		 					"Hinzufügen";
$lang_str['b_apply'] =		 					"Anwenden";
$lang_str['b_back'] =		 					"Zurück";
$lang_str['b_delete_calls'] =		 			"Delete calls";
$lang_str['b_dial_your_voicemail'] =		 	"Voicemail anrufen";
$lang_str['b_download_greeting'] =		 		"Ihre Ansage herunterladen";
$lang_str['b_edit_items_of_the_list'] =		 	"Listeneinträge editieren";
$lang_str['b_find'] = 							"Suchen";
$lang_str['b_forgot_pass_submit'] = 			"Passwort abrufen";
$lang_str['b_login'] =		 					"Login";
$lang_str['b_next'] =		 					"Weiter";
$lang_str['b_register'] = 						"Anmelden";
$lang_str['b_send'] =		 					"Senden";
$lang_str['b_submit'] =		 					"Speichern";
$lang_str['b_cancel'] =		 					"Abbrechen";
$lang_str['b_select'] =		 					"Auswählen";
$lang_str['b_test_firewall_NAT'] =		 		"Firewall/NAT testen";
$lang_str['b_upload_greeting'] =		 		"Ihre Ansage hochladen";
$lang_str['b_extended_settings'] =		 		"Erweiterte Einstellungen";
$lang_str['b_search'] =		 					"Suchen";
$lang_str['b_clear_filter'] =		 			"Filter löschen";


/* ------------------------------------------------------------*/
/*      tabs                                                   */
/* ------------------------------------------------------------*/

$lang_str['tab_my_account'] =		 			"Mein Konto";
$lang_str['tab_phonebook'] =		 			"Telefonbuch";
$lang_str['tab_missed_calls'] =	 				"Verpasste Anrufe";
$lang_str['tab_accounting'] =	 				"Buchhaltung";
$lang_str['tab_send_im'] =	 					"IM senden";
$lang_str['tab_message_store'] =	 			"Nachrichten Speicher";
$lang_str['tab_voicemail'] =	 				"Voicemail";
$lang_str['tab_user_preferences'] =	 			"Benutzereinstellungen";
$lang_str['tab_speed_dial'] =	 				"Kurzwahlen";

$lang_str['tab_users'] =	 					"Benutzer";
$lang_str['tab_admin_privileges'] =	 			"Administratorrechte";
$lang_str['tab_domains'] =	 					"Domains";
$lang_str['tab_customers'] =	 				"Kunden";
$lang_str['tab_global_attributes'] =	 		"Globale Eigenschaften";
$lang_str['tab_attr_types'] =	 				"Eigenschaftstypen";

/* ------------------------------------------------------------*/
/*      form fields                                            */
/* ------------------------------------------------------------*/

$lang_str['ff_first_name'] = 					"Vorname";
$lang_str['ff_last_name'] = 					"Nachname";
$lang_str['ff_sip_address'] = 					"SIP Adresse";
$lang_str['ff_your_timezone'] = 				"Ihre Zeitzone";
$lang_str['ff_username'] = 						"Benutzername";
$lang_str['ff_email'] = 						"E-Mail";
$lang_str['ff_show_online_only'] = 				"Nur 'Online' Benutzer anzeigen";
$lang_str['ff_language'] = 						"Sprache";
$lang_str['ff_reg_confirmation'] = 				"Anmeldebestätigung obligatorisch";
$lang_str['ff_uid'] = 							"UID";
$lang_str['ff_for_ser'] = 						"für SER";
$lang_str['ff_for_serweb'] = 					"für SerWeb";
$lang_str['ff_contact_email'] = 				"Kontakt E-Mail";

/* ------------------------------------------------------------*/
/*      table heading                                          */
/* ------------------------------------------------------------*/

$lang_str['th_name'] = 							"Name";
$lang_str['th_sip_address'] = 					"SIP Adresse";
$lang_str['th_aliases'] = 						"Aliasse";
$lang_str['th_status'] = 						"Status";
$lang_str['th_timezone'] = 						"Zeitzone";
$lang_str['th_calling_subscriber'] = 			"anrufender Teilnehmer";
$lang_str['th_time'] = 							"Zeit";
$lang_str['th_username'] = 						"Benutzername";
$lang_str['th_email'] = 						"E-Mail";
$lang_str['th_uid'] = 							"UID";

/* ------------------------------------------------------------*/
/*      login messages                                         */
/* ------------------------------------------------------------*/

$lang_str['bad_username'] = 					"Benutzername oder Passwort ist falsch";
$lang_str['account_disabled'] = 				"Ihr Konto wurde deaktiviert";
$lang_str['domain_not_found'] = 				"Ihre Domain wurde nicht gefunden";
$lang_str['msg_logout_s'] = 					"Abgemeldet";
$lang_str['msg_logout_l'] = 					"Sie sind abgemeldet. Zur erneuten Anmeldung, geben Sie unten Ihren Benutzernamen und Passwort ein";
$lang_str['userlogin'] = 						"Anmeldung";
$lang_str['adminlogin'] = 						"Administratoranmeldung";
$lang_str['enter_username_and_passw'] = 		"Bitte geben Sie Ihren Benutzernamen und Ihr Passwort ein";
$lang_str['ff_password'] = 						"Passwort";
$lang_str['l_forgot_passw'] = 					"Passwort vergessen?";
$lang_str['l_register'] = 						"Registrieren!";
$lang_str['l_have_my_domain'] = 				"Meine-Eigene-Domain!";
$lang_str['remember_uname'] = 					"Meinen Benutzernamen auf diesem Computer merken";
$lang_str['session_expired'] = 					"Sitzung abgelaufen";
$lang_str['session_expired_relogin'] = 			"Ihre Sitzung ist abgelaufen, bitte melden Sie sich erneut an.";

/* ------------------------------------------------------------*/
/*      account delete                                         */
/* ------------------------------------------------------------*/

$lang_str['msg_self_account_delete_l'] = 		"Ihr Konto wurde gelöscht";
$lang_str['l_yes_delete_it'] = 					"ja, löschen";
$lang_str['are_you_sure_to_delete_account'] = 	"Sind Sie sicher, dass Sie Ihr Konto löschen wollen?";
$lang_str['delete_account_description'] = 		"Wenn Sie bestätigen, wird Ihr Konto gelöscht. Ihre Daten werden für die nächsten <keep_days> Tage in der Datenbank bleiben und danach entfernt werden. In dieser Zeit können Sie den Administrator Ihrer Domain bitten, Ihre Kontolöschung rückgängig zu machen, sollten Sie Ihre Meinung ändern.";


/* ------------------------------------------------------------*/
/*      my account                                             */
/* ------------------------------------------------------------*/

$lang_str['msg_changes_saved_s'] = 				"Änderungen wurden gespeichert";
$lang_str['msg_changes_saved_l'] = 				"Ihre Änderungen wurden gespeichert";
$lang_str['msg_loc_contact_deleted_s'] = 		"Kontakt gelöscht";
$lang_str['msg_loc_contact_deleted_l'] = 		"Ihr Kontakt wurde gelöscht";
$lang_str['msg_loc_contact_added_s'] = 			"Kontakt hinzugefügt";
$lang_str['msg_loc_contact_added_l'] = 			"Ihr Kontakt wurde hinzugefügt";
$lang_str['ff_your_email'] = 					"Ihre E-Mail";
$lang_str['ff_fwd_to_voicemail'] = 				"Weiterleitung auf Voicemail";
$lang_str['ff_allow_lookup_for_me'] = 			"anderen erlauben, meine SIP Adresse abzurufen";
$lang_str['ff_status_visibility'] = 			"anderen erlauben, meinen Online-Status zu sehen";
$lang_str['ff_your_password'] = 				"Ihr Passwort";
$lang_str['ff_retype_password'] = 				"Passwort erneut eingeben";
$lang_str['your_aliases'] = 					"Ihre Aliasse";
$lang_str['your_acl'] = 						"Zugriffskontrolliste";
$lang_str['th_contact'] = 						"Kontakt";
$lang_str['th_expires'] = 						"erlischt";
$lang_str['th_priority'] = 						"Priorität";
$lang_str['th_location'] = 						"Ort";
$lang_str['add_new_contact'] = 					"Neuen Kontakt hinzufügen";
$lang_str['ff_expires'] = 						"erlischt";
$lang_str['contact_expire_hour'] = 				"eine Stunde";
$lang_str['contact_expire_day'] = 				"ein Tag";
$lang_str['contact_will_not_expire'] = 			"dauerhaft";
$lang_str['acl_err_local_forward'] = 			"lokale Weiterleitung unzulässig";
$lang_str['acl_err_gateway_forward'] = 			"Gateway Kontakte unzulässig";
$lang_str['l_edit_uri'] = 						"Aliasse editieren";

/* ------------------------------------------------------------*/
/*      phonebook                                              */
/* ------------------------------------------------------------*/

$lang_str['msg_pb_contact_deleted_s'] = 		"Kontakt gelöscht";
$lang_str['msg_pb_contact_deleted_l'] = 		"Der Kontakt wurde aus Ihrem Telefonbuch gelöscht";
$lang_str['msg_pb_contact_updated_s'] = 		"Kontakt aktualisiert";
$lang_str['msg_pb_contact_updated_l'] = 		"Ihre Änderungen wurden gespeichert";
$lang_str['msg_pb_contact_added_s'] = 			"Kontakt hinzugefügt";
$lang_str['msg_pb_contact_added_l'] = 			"Der Kontakt wurde zu Ihrem Telefonbuch hinzugefügt";
$lang_str['phonebook_records'] = 				"Telefonbucheinträge";
$lang_str['l_find_user'] = 						"Benutzer suchen";

/* ------------------------------------------------------------*/
/*      find user                                              */
/* ------------------------------------------------------------*/

$lang_str['find_user'] = 						"Benutzer suchen";
$lang_str['l_add_to_phonebook'] = 				"Zum Telefonbuch hinzufügen";
$lang_str['l_back_to_phonebook'] = 				"Zurück zum Telefonbuch";
$lang_str['found_users'] = 						"Benutzer";

/* ------------------------------------------------------------*/
/*      missed calls                                           */
/* ------------------------------------------------------------*/

$lang_str['th_reply_status'] = 					"Antwort Status";
$lang_str['missed_calls'] = 					"Verpasste Anrufe";
$lang_str['no_missed_calls'] = 					"Keine verpassten Anrufe";

/* ------------------------------------------------------------*/
/*      accounting                                             */
/* ------------------------------------------------------------*/

$lang_str['th_destination'] = 					"Ziel";
$lang_str['th_length_of_call'] = 				"Anruflänge";
$lang_str['th_hangup'] = 						"auflegen";
$lang_str['calls_count'] = 						"Anrufe";
$lang_str['no_calls'] = 						"Keine Anrufe";
$lang_str['msg_calls_deleted_s'] = 				"gelöschte Anrufe";
$lang_str['msg_calls_deleted_l'] = 				"Anrufe wurden erfolgreich gelöscht";


/* ------------------------------------------------------------*/
/*      send IM                                                */
/* ------------------------------------------------------------*/

$lang_str['fe_no_im'] = 						"Sie haben keine Nachricht verfasst";
$lang_str['fe_im_too_long'] = 					"die Nachricht ist zu lang";
$lang_str['msg_im_send_s'] = 					"Nachricht gesendet";
$lang_str['msg_im_send_l'] = 					"Die Nachricht wurde erfolgreich an die Adresse gesendet";
$lang_str['max_length_of_im'] = 				"Die Maximallänge der Nachricht ist";
$lang_str['sending_message'] = 					"Nachricht wird versendet";
$lang_str['please_wait'] = 						"bitte warten!";
$lang_str['ff_sip_address_of_recipient'] = 		"SIP Adresse des Empfängers";
$lang_str['ff_text_of_message'] = 				"Text der Nachricht";
$lang_str['im_remaining'] = 					"verfügbar";
$lang_str['im_characters'] = 					"Zeichen";


/* ------------------------------------------------------------*/
/*      message store                                          */
/* ------------------------------------------------------------*/

$lang_str['instant_messages_store'] = 			"Nachrichten Speicher";
$lang_str['voicemail_messages_store'] = 		"Voicemail Nachrichten Speicher";
$lang_str['no_stored_instant_messages'] = 		"keine Nachrichten gespeichert";
$lang_str['no_stored_voicemail_messages'] = 	"keine Voicemail Nachrichten gespeichert";
$lang_str['th_subject'] = 						"Betreff";
$lang_str['l_reply'] = 							"antworten";
$lang_str['err_can_not_open_message'] = 		"Nachricht kann nicht geöffnet werden";
$lang_str['err_voice_msg_not_found'] = 			"Nachricht nicht gefunden oder Sie haben keinen Zugriff auf die Nachricht";
$lang_str['msg_im_deleted_s'] = 				"Nachricht gelöscht";
$lang_str['msg_im_deleted_l'] = 				"Nachricht wurde erfolgreich gelöscht";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['customize_greetings'] = 				"Ansage ändern";
$lang_str['err_can_not_open_greeting'] = 		"Kann die Ansage nicht öffnen";

/* ------------------------------------------------------------*/
/*      attributes                                             */
/* ------------------------------------------------------------*/

$lang_str['fe_invalid_value_of_attribute'] = 	"ungültiger Wert";
$lang_str['fe_is_not_number'] = 				"ist keine gültige Nummer";
$lang_str['fe_is_not_sip_adr'] = 				"ist keine gültige SIP Adresse";
$lang_str['no_attributes_defined'] = 			"Der Administrator hat keine Eigenschaften definiert";

$lang_str['ff_send_daily_missed_calls'] =		"Verpasste Anrufe täglich per E-Mail senden";

$lang_str['ff_uri_def_f'] =						"Standardeinstellungen für URI";
$lang_str['ff_credential_def_f'] =				"Standardeinstellungen für Anmeldedaten";
$lang_str['ff_domain_def_f'] =					"Standardeinstellungen für Domain";

$lang_str['ff_max_uri_user'] =					"Maximalanzahl der URIs pro Benutzer";

$lang_str['attr_fwd_busy_target'] =				"Weiterleitungsziel falls besetzt";
$lang_str['attr_fwd_noanswer_target'] =			"Weiterleitungsziel falls keine Antwort";
$lang_str['attr_fwd_always_target'] =			"unbedingtes Weiterleitungsziel";


/* ------------------------------------------------------------*/
/*      speed dial                                             */
/* ------------------------------------------------------------*/

$lang_str['th_speed_dial'] = 					"Kurzwahl";
$lang_str['th_new_uri'] = 						"Neue URI";




/* ------------------------------------------------------------*/
/*      registration                                           */
/* ------------------------------------------------------------*/

$lang_str['fe_not_accepted_terms'] = 			"Sie haben den allgemeine Geschäftsbedingungen nicht zugestimmt";
$lang_str['choose_timezone'] = 					"--- wählen Sie Ihre Zeitzone ---";
$lang_str['choose_timezone_of_user'] = 			"--- wählen Sie die Zeitzone des Benutzers ---";
$lang_str['fe_not_choosed_timezone'] = 			"wählen Sie bitte Ihre Zeitzone";
$lang_str['fe_uname_not_follow_conventions'] = 	"der Benutzername entspricht nicht den empfohlenen Konventionen";
$lang_str['fe_not_filled_password'] = 			"Sie müssen ein Passwort eingeben";
$lang_str['fe_not_filled_your_fname'] = 		"Sie müssen Ihren Vornamen angeben";
$lang_str['fe_not_filled_your_lname'] = 		"Sie müssen Ihren Nachnamen angeben";
$lang_str['fe_uname_already_choosen_1'] = 		"Der Benutzername ist";
$lang_str['fe_uname_already_choosen_2'] = 		"leider schon vergeben.";
$lang_str['err_sending_mail'] = 				"Beim Versenden der Mail ist leider ein Fehler aufgetreten. Bitte versuchen Sie es später erneut";
$lang_str['registration_introduction_1'] = 		"Für die Anmeldung füllen Sie bitte das folgende Formular auf und klicken Sie zur Übermittelung auf den Knopf am unteren Ende der Seite. Zur Bestätigung Ihrer Anmeldung erhalten Sie in Kürze eine E-Mail. Bitte kontaktieren Sie uns,";
$lang_str['registration_introduction_2'] = 		"wenn Sie bezüglich der Anmeldung oder des kostenlosen Ausprobierens dieses SIP Services Fragen haben.";
$lang_str['reg_email_desc'] = 					"Die Adresse an die die Anmeldebestätigung gesendet wird. (Falls keine gültige E-Mail Adresse angegeben wird, kein SIP Konto erzeugt und keine Anmeldebestätigung gesendet.)";
$lang_str['reg_email_uname_desc'] = 			"Ihre SIP Adresse wird mit Ihrer E-Mail Adresse identisch sein. Die Anmeldebestätigungsanforderung wird an dies Adresse gesendet. (Falls keine gültige E-Mail Adresse angegeben wird, kein SIP Konto erzeugt und keine Anmeldebestätigung gesendet.) Ihre E-Mail Adresse muss zu folgender Domain gehören: ".$config->domain.".";
$lang_str['ff_phone'] = 						"Telefon";
$lang_str['reg_phone_desc'] = 					"Dies ist die PSTN Telefonnummer, unter der Sie erreichbar sind.";
$lang_str['ff_pick_username'] = 				"wählen Sie Ihren Benutzernamen";
$lang_str['reg_username_desc'] = 				"Ihre SIP Adresse wird lauten: username@".$config->domain.". Geben Sie nur den Benutzernamenteil Ihrer Adresse an. Dieser kann aus einer alphanumerischen Zeichenkette von Ziffern und Kleinbuchstaben bestehen und muss entweder mit einer '8' oder einem beliebigen Kleinbuchstaben beginnen (z.B., '8910' oder 'john.doe01'). Vergessen Sie Ihren Benutzernamen nicht, Sie benötigen Ihn später zur Konfiguration Ihres Telefons!";
$lang_str['ff_pick_password'] = 				"Passwort wählen";
$lang_str['reg_password_desc'] = 				"Vergessen Sie Ihr Passwort nicht, Sie benötigen es später zur Konfiguration Ihres Telefons!";
$lang_str['ff_confirm_password'] = 				"Passwort bestätigen";
$lang_str['ff_terms_and_conditions'] = 			"allgemeine Geschäftsbedingungen";
$lang_str['ff_i_accept'] = 						"Ich stimme zu";
$lang_str['ff_timezone'] = 						"Zeitzone";
$lang_str['ff_uname_assign_mode'] =             "Benutzernamenzuweisungsmodus";
$lang_str['l_back_to_loginform'] = 				"Zurück zum Loginformular";
$lang_str['msg_user_registered_s'] = 			"Benutzer angemeldet";
$lang_str['msg_user_registered_l'] = 			"Ein neuer Benutzer wurde erfolgreich angemeldet";
$lang_str['register_new_user'] = 				"neuen Benutzer anmelden";
$lang_str["err_domain_of_email_not_match"] =    "Your email address is not from same domain as into which you are registering";

/* ------------------------------------------------------------*/
/*      registration - finished                                */
/* ------------------------------------------------------------*/

$lang_str['reg_finish_thanks'] = 				"Vielen Dank, für Ihre Anmeldung bei ".$config->domain;
$lang_str['reg_finish_app_forwarded'] = 		"Ihre Anmeldung wird zur Prüfung vorgelegt.";
$lang_str['reg_finish_confirm_msg'] = 			"Sie werden in Kürze eine Benachrichtigung erhalten.";
$lang_str['reg_finish_sip_address'] = 			"Wir reservieren Ihnen die folgende SIP Adresse:";
$lang_str['reg_finish_questions_1'] = 			"Sollten Sie weitere Fragen haben, senden Sie bitte eine";
$lang_str['reg_finish_questions_2'] = 			"E-Mail an:";

/* ------------------------------------------------------------*/
/*      registration - confirmation                            */
/* ------------------------------------------------------------*/

$lang_str['reg_conf_congratulations'] = 		"Herzlichen Glückwunsch, Ihr ".$config->domain." Konto ist eingerichtet!";
$lang_str['reg_conf_set_up'] = 					"Ihr ".$config->domain." Konto ist eingerichtet!";
$lang_str['reg_conf_jabber_failed'] = 			"Aber Ihre ".$config->domain." Jabber Gateway Anmeldung ist fehlgeschlagen.";
$lang_str['reg_conf_contact_infomail_1'] = 		"Bitte kontaktieren Sie";
$lang_str['reg_conf_contact_infomail_2'] = 		"für entsprechende Hilfestellung.";
$lang_str['reg_conf_failed'] = 					"Bedauerlicherweise schlug der ".$config->domain." Bestätigungsversuch fehl.";
$lang_str['reg_conf_nr_not_exists'] = 			"Entweder ist Ihre Bestätigungsnummer falsch, oder Ihr Konto wurde bereits erstellt!";
$lang_str['err_reg_conf_not_exists_conf_num'] = "Diese Bestätigungsnummer existiert leider nicht";

/* ------------------------------------------------------------*/
/*      registration - forgot password                         */
/* ------------------------------------------------------------*/

$lang_str['forgot_pass_head'] = 				"Passwort vergessen?";
$lang_str['forgot_pass_introduction'] = 		"Sollten Sie Ihr Passwort vergessen haben, tragen Sie bitte ihren Benutzernamen in das Formular unten ein. Eine E-Mail mit Ihrem Passwort wird an die E-Mail Adresse, die Sie bei der Anmeldung hinterlegt haben, gesendet !";
$lang_str['forgot_pass_sended'] = 				"Ein neues Passwort wurde erzeugt und an die E-Mail Adresse, die Sie bei der Anmeldung hinterlegt haben, gesendet.";
$lang_str['msg_pass_conf_sended_s'] = 			"Logininformationen wurden gesendet";
$lang_str['msg_pass_conf_sended_l'] = 			"Die Logininformationen wurden an Ihre E-Mail Adresse gesendet";
$lang_str['msg_password_sended_s'] = 			"Neues Passwort gesendet";
$lang_str['msg_password_sended_l'] = 			"Ein neues Passwort wurde an Ihre E-Mail Adresse gesendet";
$lang_str['err_no_user'] = 						"Dies ist leider kein bekannter Benutzername!";

/* ------------------------------------------------------------*/
/*      admin - users management                               */
/* ------------------------------------------------------------*/

$lang_str['err_admin_can_not_delete_user_1'] = 	"Sie können diesen Benutzer nicht löschen";
$lang_str['err_admin_can_not_delete_user_2'] = 	"dieser Benutzer gehört zu einer anderen Domain";
$lang_str['msg_acl_updated_s'] = 				"ACL aktualisiert";
$lang_str['msg_acl_updated_l'] = 				"Die Zugriffskontrolliste des Benutzers wurde aktualisiert";
$lang_str['msg_user_deleted_s'] = 				"Benutzer gelöscht";
$lang_str['msg_user_deleted_l'] = 				"Der Benutzer wurde erfolgreich gelöscht";
$lang_str['msg_user_undeleted_s'] = 			"Benutzer wiederhergestellt";
$lang_str['msg_user_undeleted_l'] = 			"Der Benutzer wurde erfolgreich wiederhergestellt";
$lang_str['msg_user_purged_s'] = 				"Benutzer endgültig entfernt";
$lang_str['msg_user_purged_l'] = 				"Der Benutzer wurde mit Erfolg endgültig entfernt";
$lang_str['th_phone'] = 						"Telefone";
$lang_str['l_acl'] = 							"ACL";
$lang_str['l_aliases'] = 						"Aliasse";
$lang_str['l_account'] = 						"Konto";
$lang_str['l_accounting'] = 					"Abrechnung";
$lang_str['realy_you_want_delete_this_user'] =	"Möchten Sie diesen Benutzer wirklich löschen?";
$lang_str['realy_you_want_purge_this_user'] =	"Möchten Sie diesen Benutzer wirklich endgültig entfernen?";
$lang_str['l_credentials'] = 					"Anmeldedaten";
$lang_str['l_uris'] = 					        "SIP URIs";
$lang_str['user_has_no_credentials'] = 			"Benutzer hat keine Anmeldedaten";
$lang_str['user_has_no_sip_uris'] = 			"Benutzer hat keine SIP URIs";
$lang_str['err_cannot_delete_own_account'] = 	"Sie können Ihr eigenes Konto nicht löschen";
$lang_str['err_cannot_disable_own_account'] = 	"Sie können Ihr eigenes Konto nicht deaktivieren";
$lang_str['ff_show_deleted_users'] =            "gelöschte Benutzer anzeigen";
$lang_str['deleted_user'] = 					"GELÖSCHT";

/* ------------------------------------------------------------*/
/*      admin - ACL, aliases                                   */
/* ------------------------------------------------------------*/

$lang_str['access_control_list_of_user'] = 		"Zugriffskontrolliste des Benutzers";
$lang_str['have_not_privileges_to_acl'] = 		"Sie haben keine Berechtigung zur ACL Kontrolle";
$lang_str['err_alias_already_exists_1'] = 		"Der Alias:";
$lang_str['err_alias_already_exists_2'] = 		"existent bereits";
$lang_str['msg_alias_deleted_s'] = 				"Alias gelöscht";
$lang_str['msg_alias_deleted_l'] = 				"Der Alias des Benutzers wurde gelöscht";
$lang_str['msg_alias_updated_s'] = 				"Alias aktualisiert";
$lang_str['msg_alias_updated_l'] = 				"Ihre Änderungen wurden gespeichert";
$lang_str['msg_alias_added_s'] = 				"Alias hinzugefügt";
$lang_str['msg_alias_added_l'] = 				"Der Alias wurde dem Benutzers hinzugefügt";
$lang_str['change_aliases_of_user'] = 			"Aliasse des Benutzers ändern";
$lang_str['ff_alias'] = 						"Alias";
$lang_str['th_alias'] = 						"Alias";
$lang_str['ff_uri'] = 						    "URI";
$lang_str['th_uri'] = 						    "URI";
$lang_str['realy_you_want_delete_this_alias'] = "Möchten Sie diesen Alias wirklich löschen?";
$lang_str['user_have_not_any_aliases'] = 		"Der Benutzer hat keine Aliasse";
$lang_str['ff_is_canon'] = 						"ist kanonisch";
$lang_str['ff_is_enabled'] = 					"ist aktiviert";
$lang_str['ff_uri_is_to'] = 					"kann als 'zu' URI benutzt werden";
$lang_str['ff_uri_is_from'] = 					"kann als 'von' URI benutzt werden";
$lang_str['th_is_canon'] = 						"kanonisch";
$lang_str['th_uri_is_to'] = 					"zu";
$lang_str['th_uri_is_from'] = 					"von";
$lang_str['l_ack'] = 							"bestätigen";
$lang_str['l_deny'] = 							"abbrechen";
$lang_str['uris_with_same_uname_did'] = 		"existierende URIs mit selbem Benutzernamen und Domain";
$lang_str['ack_values'] = 						"Werte bestätigen";
$lang_str['uri_already_exists'] = 				"Eine URI mit dem angegebenen Benutzernamen und der Domain existiert bereits. Bitte bestätigen Sie Ihre Angaben.";
$lang_str['is_to_warning'] = 					"WARNUNG: Die URI <uri> wird bereits von einem anderen Benutzer verwendet und das 'IS TO' Flag ist dafür eingestellt. Wenn Sie fortfahren, wird das 'IS TO' Flag für diese UIR gelöscht";
$lang_str['err_canon_uri_exists'] = 			"Ein kanonisches Flag kann für dies UIR nicht gesetzt werden, da jeder Benutzer jeweils nur eine URI haben kann, bei der dieses Flag aktiviert ist. Das Flag ist aber schon für eine andere URI aktiviert, für die Sie keine Änderungsrechte besitzen.";
$lang_str['uid_with_alias'] = 					"Liste UID mit Alias";
$lang_str['uri_available'] = 					"Dieser Alias wird noch nicht benutzt.";
$lang_str['uri_not_available'] = 				"Dieser Alias wird bereits benutzt.";
$lang_str['l_uri_suggest'] = 					"Neuer Vorschlag";
$lang_str['no_suggestions'] = 					"Leider keine Vorschläge!";
$lang_str['err_ri_dup'] =                       "Eine äquivalente URI existiert bereits.";
$lang_str['err_uri_limit_reached'] =            "Die maximale Anzahl der URIs ist erreicht";
$lang_str['err_uri_modify_not_permited'] =      "Sie sind zum Ändern dieser URI nicht berechtigt";
$lang_str['user_uris'] =                        "Benutzer URIs";
$lang_str['l_back_to_my_account'] = 			"zurück zu meinem Konto";
$lang_str['msg_uri_deleted_s'] = 				"URI gelöscht";
$lang_str['msg_uri_deleted_l'] = 				"Die URI wurde gelöscht";
$lang_str['msg_uri_updated_s'] = 				"URI aktualisiert";
$lang_str['msg_uri_updated_l'] = 				"Die URI wurde aktualisiert";
$lang_str['msg_uri_created_s'] =   				"URI hinzugefügt";
$lang_str['msg_uri_created_l'] =   				"Die URI wurde hinzugefügt";

/* ------------------------------------------------------------*/
/*      admin privileges                                       */
/* ------------------------------------------------------------*/

$lang_str['admin_privileges_of'] = 				"Administratorenrechte von";
$lang_str['admin_competence'] = 				"Administrator Zuständigkeit";
$lang_str['ff_is_admin'] = 						"ist Administrator";
$lang_str['ff_change_privileges'] = 			"ändert die Administratorenrechte";
$lang_str['ff_is_hostmaster'] = 				"ist Hostmaster";
$lang_str['acl_control'] = 						"ACL Kontrolle";
$lang_str['msg_privileges_updated_s'] = 		"Berechtigungen aktualisiert";
$lang_str['msg_privileges_updated_l'] = 		"Die Berechtigungen des Benutzers wurden aktualisiert";
$lang_str['list_of_users'] = 					"Benutzerliste";
$lang_str['th_domain'] = 						"Domain";
$lang_str['l_change_privileges'] = 				"Berechtigungen";
$lang_str['ff_domain'] = 						"Domain";
$lang_str['ff_realm'] = 						"Realm";
$lang_str['th_realm'] = 						"Realm";
$lang_str['ff_show_admins_only'] = 				"nur Administratoren anzeigen";
$lang_str['err_cant_ch_priv_of_hostmaster'] = 	"Dieser Benutzer ist Hostmaster. Sie können die Berechtigungen eines Hostmasters nicht ändern, da Sie selbst kein Hostmaster sind!";


/* ------------------------------------------------------------*/
/*      attribute types                                        */
/* ------------------------------------------------------------*/

$lang_str['fe_not_filled_name_of_attribute'] = 	"Sie müssen einen Attributnamen angeben";
$lang_str['fe_empty_not_allowed'] = 			"darf nicht leer seine";
$lang_str['ff_order'] = 						"Reihenfolge";
$lang_str['ff_att_name'] = 						"Attributnamen";
$lang_str['ff_att_type'] = 						"Attributtyp";
$lang_str['ff_att_access'] = 					"Zugang";
$lang_str['ff_label'] = 						"Etikett";
$lang_str['ff_att_group'] = 					"Gruppe";
$lang_str['ff_att_uri'] = 						"URI";
$lang_str['ff_att_user'] = 						"Benutzer";
$lang_str['ff_att_domain'] = 					"Domain";
$lang_str['ff_att_global'] = 					"global";
$lang_str['ff_multivalue'] = 					"Multivalue";
$lang_str['ff_att_reg'] = 						"Für Anmeldung erforderlich";
$lang_str['ff_att_req'] = 						"erforderlich (nicht leer)";
$lang_str['ff_fr_timer'] = 						"maximale Antwortzeit";
$lang_str['ff_fr_inv_timer'] = 					"final response invite timer";
$lang_str['ff_uid_format'] = 					"Format neuerstellter UIDs";
$lang_str['ff_did_format'] = 					"Format neuerstellter DIDs";

$lang_str['title_group_rename'] = 				"Gruppe umbenennen";
$lang_str['ff_new_group'] = 					"neuer Gruppenname";

$lang_str['at_access_0'] = 						"unbeschränkter Zugang";
$lang_str['at_access_1'] = 						"Read-Only für Benutzer";
$lang_str['at_access_3'] = 						"Nicht sichtbar für Benutzer";
$lang_str['at_access_21'] = 					"Read-Only";


$lang_str['th_att_name'] = 						"Attributnamen";
$lang_str['th_att_type'] = 						"Attributtyp";
$lang_str['th_order'] = 						"Reihenfolge";
$lang_str['th_label'] = 						"Etikett";
$lang_str['th_att_group'] = 					"Gruppe";
$lang_str['fe_order_is_not_number'] = 			"'Reihenfolge' ist keine gültige Nummer";

$lang_str['fe_not_filled_item_label'] = 		"Sie müssen dem Begriff ein Etikett geben";
$lang_str['fe_not_filled_item_value'] = 		"Sie müssen dem Begriff einen Wert geben";
$lang_str['ff_item_label'] = 					"Begriffsetikett";
$lang_str['ff_item_value'] = 					"Begriffswert";
$lang_str['th_item_label'] = 					"Begriffsetikett";
$lang_str['th_item_value'] = 					"Begriffswert";
$lang_str['l_back_to_editing_attributes'] = 	"zurück zum Editieren der Attribute";
$lang_str['realy_want_you_delete_this_attr'] = 	"Möchten Sie dieses Attribut wirklich löschen?";
$lang_str['realy_want_you_delete_this_item'] = 	"Möchten Sie diesen Begriff wirklich löschen?";


$lang_str['attr_type_warning'] = 				"Auf dieser Seite können Sie neue Attribute definieren, deren Typen, Flags, usw. modifizieren. Vordefinierte Attribute werden von SerWeb bzw. SER hauptsächlich intern verwendet. Ändern Sie diese nicht, es sei denn, Sie wissen genau warum!!!";
$lang_str['at_hint_order'] = 					"Attribute werden in SerWeb in dieser Reihenfolge arrangiert";
$lang_str['at_hint_label'] = 					"Etikett des Attributes, so wie es in SerWeb angezeigt wird. Wenn dies mit '@' beginnt, wird der Begriff mit den Dateien im Verzeichnis 'lang' in die Sprache des Benutzers übersetzt. Es liegt in Ihrer Verantwortung, dass in diesen Dateien die Übersetzungen für sämtliche Phrasen vorliegen.";
$lang_str['at_hint_for_ser'] = 					"Attribut wurde von SER geladen. Diese Änderung wirkt nur auf neu erzeugte Attribute.";
$lang_str['at_hint_for_serweb'] = 				"Attribut wurde von SerWeb geladen. Diese Änderung wirkt nur auf neu erzeugte Attribute.";
$lang_str['at_hint_user'] = 					"Attribut wird auf der Seite für Benutzereinstellungen angezeigt";
$lang_str['at_hint_domain'] = 					"Attribut wird auf der Seite für Domaineinstellungen angezeigt";
$lang_str['at_hint_global'] = 					"Attribut wird auf der Seite für globale Einstellungen angezeigt";
$lang_str['at_hint_multivalue'] = 				"Attribut kann mehrere Werte haben";
$lang_str['at_hint_registration'] = 			"Attribut wird auf dem Benutzeranmeldeformular angezeigt";
$lang_str['at_hint_required'] = 				"Attribute has to have any not empty value. Wird nicht für alle Typen benutzt. Wird für die Typen: int, email_adr, sip_adr, usw. benutzt";


$lang_str['ff_att_default_value'] = 			"Standardwert";
$lang_str['th_att_default_value'] = 			"Standardwert";
$lang_str['ff_set_as_default'] = 				"als Standard übernehmen";
$lang_str['edit_items_of_the_list'] = 			"Einträge der Liste editieren";

$lang_str['o_lang_not_selected'] = 				"nicht ausgewählt";

$lang_str['at_int_title'] = 					"Erweiterte Einstellungen des int Attributes ändern";
$lang_str['ff_at_int_min'] = 					"Minimaler Wert";
$lang_str['ff_at_int_max'] = 					"Maximaler Wert";
$lang_str['ff_at_int_err'] = 					"Fehlermeldung";

$lang_str['ff_at_int_min_hint'] = 				"Kleinster zulässiger Wert. Bleibt dieses Feld leer, findet keine Überprüfung statt.";
$lang_str['ff_at_int_max_hint'] = 				"Größter zulässiger Wert. Bleibt dieses Feld leer, findet keine Überprüfung statt.";
$lang_str['ff_at_int_err_hint'] = 				"Anpassung der Fehlermeldungen für Werte ausserhalb des gültigen Bereiches. Lassen Sie dieses Feld für die Standardfehlermeldung leer. Wenn die Fehlermeldung mit '@' beginnt, wird der Begriff mit den Dateien im Verzeichnis 'lang' in die Sprache des Benutzers übersetzt. Es liegt in Ihrer Verantwortung, dass in diesen Dateien die Übersetzungen für sämtliche Phrasen vorliegen.";

$lang_str['at_import_title'] = 					"Attributtypen importieren";
$lang_str['ff_xml_file'] = 					    "XML Datei";
$lang_str['ff_at_import_purge'] = 				"Alle Attributtypen endgültig entfernen, bevor neue importiert werden";
$lang_str['ff_at_import_exists'] = 				"Falls ein Attributtyp bereits existiert ...";
$lang_str['ff_at_import_skip'] = 				"überspringen";
$lang_str['ff_at_import_update'] = 				"Das alte Attribut durch das neue ersetzen";

$lang_str['fe_file_too_big'] =                  "Die Datei ist zu groß";
$lang_str['fe_at_no_xml_file'] =                "Fehlende XML Datei";
$lang_str['fe_at_invalid_sml_file'] =           "XML Datei ist ungültig";
$lang_str['fe_at_xml_file_type'] =              "Die Datei enthält kein xml";

$lang_str['err_at_int_range'] = 				"muss im Bereich %d bis %d liegen";
$lang_str['err_at_int_range_min'] = 			"muss größer seine als %d";
$lang_str['err_at_int_range_max'] = 			"muss kleiner seine als %d";

$lang_str['attr_grp_general'] = 				"Allgemeines";
$lang_str['attr_grp_privacy'] = 				"Privatsphäre";
$lang_str['attr_grp_other'] = 					"Sonstiges";
$lang_str['err_at_grp_empty'] = 				"Attribut Gruppe darf nicht leer sein";
$lang_str['err_at_new_grp_empty'] = 	        "Gruppenname darf nicht leer sein";
$lang_str['attr_grp_create_new'] = 				"Neue Gruppe anlegen";


$lang_str['l_attr_grp_toggle'] = 				"Anzeigen der Attributgruppen umschalten";
$lang_str['l_export_sql'] = 				    "in ein SQL Script exportieren";
$lang_str['l_export_xml'] = 				    "in eine XML Datei exportieren";
$lang_str['l_import_xml'] = 				    "aus einer XML Datei importieren";

$lang_str['msg_at_imported_s'] =                "Attributtypen importiert";
$lang_str['msg_at_imported_l'] =                "Die Attributtypen wurden erfolgreich importiert";

/* ------------------------------------------------------------*/
/*      credentials                                            */
/* ------------------------------------------------------------*/


$lang_str['change_credentials_of_user'] = 		"Anmeldedaten des Benutzers ändern";

$lang_str['th_password'] = 						"Passwort";
$lang_str['th_for_ser'] = 						"für SER";
$lang_str['th_for_serweb'] = 					"für SerWeb";

$lang_str['err_credential_changed_domain'] = 	"Die Domain des Benutzers hat sich geändert. Auch das Passwort muss neu gesetzt werden.";
$lang_str['warning_credential_changed_domain'] =		"Da SerWeb Passwörter nicht im Klartext speichert, werden die gehashten Passwörter, beim Ändern der Domain eines Benutzers ungültig. Daher müssen Sie in diesem Fall auch das 'Passwort' Feld ausfüllen.";

$lang_str['realy_want_you_delete_this_credential'] = 	"Möchten Sie diese Anmeldedaten wirklich löschen?";


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

$lang_str['sel_item_all_calls'] = 				"Alle Abrufe";
$lang_str['sel_item_outgoing_calls'] = 			"Nur ausgehende Anrufe";
$lang_str['sel_item_incoming_cals'] = 			"Nur eingehende Anrufe";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['fe_no_greeeting_file'] = 			"Sie haben keine Ansagedatei ausgewählt";
$lang_str['fe_invalid_greeting_file'] = 		"die Ansagedatei ist ungültig";
$lang_str['fe_greeting_file_no_wav'] = 			"die Ansagedatei muss im audio/wav Format vorliegen";
$lang_str['fe_greeting_file_too_big'] = 		"die Ansagedatei ist zu lang";
$lang_str['msg_greeting_stored_s'] = 			"Ansage abgespeichert";
$lang_str['msg_greeting_stored_l'] = 			"Ihre Ansage wurde erfolgreich abgespeichert";
$lang_str['msg_greeting_deleted_s'] = 			"Ansage entfernt";
$lang_str['msg_greeting_deleted_l'] = 			"Ihre Ansage wurde erfolgreich entfernt";

/* ------------------------------------------------------------*/
/*      whitelist                                              */
/* ------------------------------------------------------------*/

$lang_str['err_whitelist_already_exists'] = 	"Der Whitelist Eintrag existiert bereits";

/* ------------------------------------------------------------*/
/*      multidomain                                            */
/* ------------------------------------------------------------*/

$lang_str['fe_not_customer_name'] = 			"Sie müssen den Namen des Kunden eintragen";
$lang_str['ff_customer_name'] = 				"Name des Kunden";
$lang_str['no_customers'] = 					"Kein Kunde";
$lang_str['customer'] = 						"Kunde";

$lang_str['msg_customer_updated_s'] = 			"Kunde aktualisiert";
$lang_str['msg_customer_updated_l'] = 			"Kundenname wurde aktualisiert";
$lang_str['msg_customer_deleted_s'] = 			"Kunde gelöscht";
$lang_str['msg_customer_deleted_l'] = 			"Kunde wurde gelöscht";
$lang_str['msg_customer_added_s'] = 			"Kunde angelegt";
$lang_str['msg_customer_added_l'] = 			"Ein neuer Kunde wurde angelegt";
$lang_str['err_customer_own_domains'] = 		"Der Kunde besitzt einige Domains und kann nicht gelöscht werden";

$lang_str['d_id'] = 							"Domain ID";
$lang_str['d_name'] = 							"Domain Name";
$lang_str['list_of_domains'] = 					"Liste der Domains";
$lang_str['showed_domains'] = 					"Anzeigen der Domains";
$lang_str['no_domains_found'] = 				"keine Domains gefunden";
$lang_str['new_dom_name'] = 					"Neuen Domainnamen hinzufügen";
$lang_str['owner'] = 							"Besitzer";

$lang_str['realy_delete_domain'] = 				"Möchten Sie diese Domain wirklich löschen?";
$lang_str['realy_purge_domain'] =               "Möchten Sie diese Domain wirklich endgültig entfernen?";
$lang_str['l_create_new_domain'] = 				"Neue Domain anlegen";
$lang_str['l_reload_ser'] = 					"SER und Web Server neu laden";
$lang_str['no_domain_name_is_set'] = 			"Geben Sie mindestens einen Domainnamen ein";
$lang_str['prohibited_domain_name'] = 			"Dieser Domainname ist leider verboten";
$lang_str['can_not_del_last_dom_name'] = 		"Der einzige Domainname kann nicht gelöscht werden";

$lang_str['msg_domain_reload_s'] = 				"Konfiguration neu geladen";
$lang_str['msg_domain_reload_l'] = 				"Die Konfigurationsdaten von SER und dem Web Serwer wurden neu geladen";
$lang_str['msg_domain_deleted_s'] = 			"Domain gelöscht";
$lang_str['msg_domain_deleted_l'] = 			"Diese Domain wird hier nicht mehr verwaltet, und alle zugehörigen Datensätze einschließlich Teilnehmerdaten werden bald gelöscht. Stellen Sie sicher, dass keine DNS-Einträge mehr auf diesen Server verweisen";
$lang_str['msg_domain_undeleted_s'] = 			"Domainlöschung rückgängig gemacht";
$lang_str['msg_domain_undeleted_l'] = 			"Die Domainlöschung wurde erfolgreich rückgängig gemacht";
$lang_str['msg_domain_purged_s'] = 				"Domain endgültig entfernt";
$lang_str['msg_domain_purged_l'] = 				"Domain wurde mit Erfolg endgültig entfernt";

$lang_str['assigned_domains'] = 				"Domains zuweisen";
$lang_str['unassigned_domains'] = 				"nicht zugewiesene Domains";
$lang_str['l_assign_domain'] = 					"Domains zuweisen";
$lang_str['l_unassign_domain'] = 				"Domainzuweisung aufheben";
$lang_str['l_assign'] =                                  "zuweisen";
$lang_str['l_unassign'] =                                "Zuweisung aufheben";
$lang_str['l_assigned_domains'] = 				"Domains";
$lang_str['l_change_layout'] = 					"Layout";
$lang_str['l_domain_attributes'] = 				"Attribute";
$lang_str['l_unassign_admin'] = 				"Administratorzuweisung aufheben";
$lang_str['l_set_canon'] = 						"kanonisch setzen";

$lang_str['admins_of_domain'] = 				"Administratoren dieser Domain";
$lang_str['no_admins'] = 						"Keine Administratoren";

$lang_str['ff_address'] = 						"Adresse";

$lang_str['lf_terms_and_conditions'] =			"allgemeine Geschäftsbedingungen";
$lang_str['lf_mail_register_by_admin'] = 		"Mail die an den Benutzer geschickt wird, nachdem dieser vom Administrator angelegt wurde";
$lang_str['lf_mail_register'] = 				"Anmeldebestätigungsmail";
$lang_str['lf_mail_register_conf'] = 			"E-Mail mit Anmeldeauftragsbestätigung versenden (E-Mail Validierung erforderlich)"; 
$lang_str['lf_mail_fp_conf'] = 					"Bestätigungsemail für neues Passwort, wenn das alte vergessen wurde";
$lang_str['lf_mail_fp_pass'] = 					"E-Mail mit neuem Passwort, wenn das alte vergessen wurde";
$lang_str['lf_mail_mmissed_calls'] = 			"Verpasste Anrufe per E-Mail versenden"; 
$lang_str['lf_config'] = 						"Domain Konfiguration";

$lang_str['l_toggle_wysiwyg'] = 				"WYSIWYG umschalten";
$lang_str['l_upload_images'] = 					"Bilder hochladen";
$lang_str['l_back_to_default'] = 				"Standardinhalt wieder herstellen";

$lang_str['wysiwyg_warning'] = 					"Seien Sie mit dem WYSIWYG Editor etwas vorsichtig. Die Prolog.html Datei muss mit dem &lt;body&gt; Element beginnen und die epilog.html Datei muss mit dem &lt;/body&gt; Element enden. Der WYSIWYG Editor könnte dies Elemente entfernen! Beachten Sie auch die Kompatibilitätsliste des WYSIWYG Editors: 'Mozilla, MSIE und FireFox (Safari experimental)'. Sollten Sie einen anderen Browser benutzen funktioniert der WYSIWYG Editor möglicherweise nicht.";

$lang_str['choose_one'] = 						"Auswahl treffen";

$lang_str['layout_files'] = 					"Layout Dateien";
$lang_str['text_files'] = 						"Text Dateien";

$lang_str['fe_domain_not_selected']	= 			"Für den Benutzer ist keine Domain ausgewählt";

$lang_str['th_old_versions'] = 					"Ältere Versionen dieser Datei";
$lang_str['initial_ver'] = 						"erste";
$lang_str['ff_show_deleted_domains'] =          "gelöschte Domains anzeigen";
$lang_str['deleted_domain'] = 					"GELÖSCHT";


$lang_str['err_dns_lookup'] =                   "Fehler beim DNS Lookup. DNS Einstellungen konnten nicht überprüft werden";
$lang_str['err_no_srv_record'] =                "Für den Hostnamen <hostname> gibt es keinen SRV Eintrag";
$lang_str['err_wrong_srv_record'] =             "SRV Eintrag(e) gefunden, aber Ziel-Host und Port stimmen nicht. Die folgenden SRV Einträge wurden gefunden: ";
$lang_str['err_domain_already_hosted'] = 		"Diese Domain wird auf diesem Server bereits verwaltet";
$lang_str['err_cannot_delete_own_domain'] = 	"Sie können die Domain Ihres eigenen Kontos nicht löschen";
$lang_str['err_cannot_disable_own_domain'] = 	"Sie können die Domain Ihres eigenen Kontos nicht deaktivieren";



/* ------------------------------------------------------------*/
/*      wizard - create new domain                             */
/* ------------------------------------------------------------*/

$lang_str['register_new_admin'] = 				"Neuen Administrator für die Domain einrichten";
$lang_str['assign_existing_admin'] = 			"Der Domain einen bereits existierenden Administrator zuweisen";
$lang_str['assign_admin_to_domain'] = 			"Der Domain einen Administrator zuweisen";
$lang_str['create_new_domain'] = 				"Eine neue Domain anlegen";
$lang_str['l_create_new_customer'] = 			"neuen Kunden anlegen";
$lang_str['create_new_customer'] = 				"Einen neuen Kunden anlegen";
$lang_str['l_close_window'] = 					"Fenster schliesen";
$lang_str['step'] = 							"Schritt";
$lang_str['l_select'] = 						"auswählen";
$lang_str['domain_setup_success'] = 			"Die neue Domain wurde erfolgreich angelegt!";
$lang_str['l_skip_asignment_of_admin'] = 		"Zuweisung eines Administrators überspringen";

/* ------------------------------------------------------------*/
/*      wizard - have a domain                                 */
/* ------------------------------------------------------------*/

$lang_str['have_a_domain_head'] = 				"Meine-Eigene-Domain!";
$lang_str['have_a_domain_introduction'] = 		"Auf dieser Seite könnten Sie Ihre eigene Domain zur Verwaltung auf dem ".$config->domain." Server registrieren. Wenn Sie Ihre Domain auf dem ".$config->domain." Server verwalten lassen wollen, müssen Sie die DNS Einträge für Ihre Domain zunächst entsprechend anpassen. Es muss einen SRV Eintrag für den 'SIP' Service und das 'UDP' Protocol geben, die auf den Host <srv_host> mit Port <srv_port> zeigen.";
$lang_str['have_a_domain_introduction2'] = 		"Registrieren Sie Ihre Domain in zwei Schritten:";
$lang_str['have_a_domain_step1'] = 				"DNS Einträge Ihrer Domain prüfen";
$lang_str['have_a_domain_step2'] = 				"Ein Administrator Konto für Ihre Domain anlegen";
$lang_str['have_a_domain_introduction3'] = 		"Ausfüllen des Formulars weiter unten und prüfen der DNS Einträge für Ihre Domain.";
$lang_str[''] = 							"";
$lang_str[''] = 							"";
$lang_str[''] = 							"";


?>
