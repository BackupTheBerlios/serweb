<?
/*
 * $Id: french-utf-8.php,v 1.6 2008/03/28 07:11:52 kozlik Exp $
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

$lang_set['charset'] =			"utf-8";
$lang_set['date_time_format'] =	"d.m.Y H:i";
$lang_set['date_format'] =		"d.m.Y";
$lang_set['time_format'] =		"H:i";


/* ------------------------------------------------------------*/
/*      common messages                                        */
/* ------------------------------------------------------------*/

$lang_str['user_management'] = 					"Gestion utilisateur";
$lang_str['admin_interface'] = 					"Interface d'administration";
$lang_str['user'] =								"utilisateur";
$lang_str['from'] =								"de";
$lang_str['no_records'] =						"Aucune fiche";
$lang_str['l_logout'] =							"se déconnecter";
$lang_str['l_edit'] =							"modifier";
$lang_str['l_extended'] = 						"extended";	//to translate 
$lang_str['l_change'] =							"changer";
$lang_str['l_delete'] =							"effacer";
$lang_str['l_undelete'] = 						"undelete";	//to translate 
$lang_str['l_purge'] = 	    					"purge";	//to translate 
$lang_str['l_back_to_main'] = 					"retourner à la page principale";
$lang_str['l_back'] =							"retourner";
$lang_str['l_disable'] = 						"désactiver";
$lang_str['l_enable'] = 						"activer";
$lang_str['l_disable_all'] = 					"tout désactiver";
$lang_str['l_enable_all'] = 					"tout activer";
$lang_str['status_unknown'] = 					"inconnu";
$lang_str['status_nonlocal'] = 					"externe"; // or: "non local" ?
$lang_str['status_nonexists'] = 				"inexistant";
$lang_str['status_online'] = 					"disponible";
$lang_str['status_offline'] = 					"indisponible";
$lang_str['search_filter'] = 					"filtre";
$lang_str['showed_users'] = 					"Utilisateurs affichés";
$lang_str['displaying_records'] = 				"Montrer les fiches";
$lang_str['no_users_found'] = 					"Aucun utilisateur trouvé";
$lang_str['no_records_found'] = 				"Aucune fiche trouvée";
$lang_str['none'] = 							"aucun";	// or: "aucune", "rien" ?
$lang_str['warning'] = 							"Attention !";	// or: "Avertissement !" ?
$lang_str['domain'] = 							"domaine";
$lang_str['yes'] = 								"OUI";
$lang_str['no'] = 								"NON";
$lang_str['not_exists'] = 						"n'existe pas";
$lang_str['filter_wildcard_note'] =             "You could use '*' and '?' wildcards in the filter fields";	//to translate 

/* ------------------------------------------------------------*/
/*      error messages                                         */
/* ------------------------------------------------------------*/

$lang_str['fe_not_valid_email'] =	 			"adresse courriel non valide";
$lang_str['fe_is_not_valid_email'] =	 		"Ce n'est pas un adresse courriel valide";
$lang_str['fe_not_valid_sip'] = 				"adresse SIP non valide";
$lang_str['fe_not_valid_phonenumber'] = 		"numéro de téléphone non valide";
$lang_str['fe_not_filled_sip'] = 				"vous devez remplir l'adresse SIP";
$lang_str['fe_passwords_not_match'] =			"les mots de passe ne correspondent pas";
$lang_str['fe_not_filled_username'] = 			"Vous devez indiquer le nom d'utilisateur"; // or: "Vous devez remplir le nom d'utilisateur" ?
$lang_str['fe_not_allowed_uri'] = 				"adresse SIP non permise";
$lang_str['fe_max_entries_reached'] = 			"Nombre maximum d'entrées atteint";
$lang_str['fe_not_valid_username'] = 			"nom d'utilisateur non valide";
$lang_str['fe_not_valid_domainname'] = 			"nom de domaine non valide";

/* ------------------------------------------------------------*/
/*      buttons                                                */
/* ------------------------------------------------------------*/

$lang_str['b_add'] =							"Ajouter";
$lang_str['b_apply'] =		 					"Apply";	//to translate 
$lang_str['b_back'] =		 					"Retourner";
$lang_str['b_delete_calls'] =		 			"Effacer les appels";
$lang_str['b_dial_your_voicemail'] =		 	"Composer votre messagerie vocale";
$lang_str['b_download_greeting'] =		 		"Télécharger votre salutation";
$lang_str['b_edit_items_of_the_list'] =		 	"Modifier les éléments de la liste";
$lang_str['b_find'] =							"Rechercher";
$lang_str['b_forgot_pass_submit'] = 			"Obtenir le mot de passe";
$lang_str['b_login'] =							"Se connecter";
$lang_str['b_next'] =		 					"Suivant";
$lang_str['b_register'] =						"S'inscrire";
$lang_str['b_send'] =							"Envoyer";
$lang_str['b_submit'] =							"Enregistrer";
$lang_str['b_cancel'] =		 					"Annuler";
$lang_str['b_select'] =		 					"Sélectionner";
$lang_str['b_test_firewall_NAT'] =		 		"Tester le pare-feu / NAT";	// or: "Tester le coupe-feu / NAT" ?
$lang_str['b_upload_greeting'] =		 		"Salutation de télédéchargement";
$lang_str['b_extended_settings'] =		 		"Réglages avancés";
$lang_str['b_search'] =		 					"Rechercher";
$lang_str['b_clear_filter'] =		 			"Clear filter";	//to translate 


/* ------------------------------------------------------------*/
/*      tabs                                                   */
/* ------------------------------------------------------------*/

$lang_str['tab_my_account'] =		 			"mon compte";
$lang_str['tab_phonebook'] =		 			"carnet d'adresses"; // or: "répertoire téléphonique", "liste des contacts" ?
$lang_str['tab_missed_calls'] =	 				"appels manqués";
$lang_str['tab_accounting'] =	 				"détail des communications"; // or: "détail de vos communications", "comptabilité" ?
$lang_str['tab_send_im'] =						"envoyer en MI"; // or: "envoyer en messagerie instantanée" ?
$lang_str['tab_message_store'] =	 			"boîte à messages"; // or: "boîte vocale" ?
$lang_str['tab_voicemail'] =	 				"messagerie vocale"; // or: "audio-messagerie", "courrier vocal" ?
$lang_str['tab_user_preferences'] =	 			"préférences de l'utilisateur";
$lang_str['tab_speed_dial'] =	 				"Appel abrégé"; // or: "Numérotation rapide", "Composition rapide", "Raccourcis", "Numéros prédéfinis" ?

$lang_str['tab_users'] =						"utilisateurs";
$lang_str['tab_admin_privileges'] =	 			"privilèges administratifs"; // or: "privilèges admin." ?
$lang_str['tab_domains'] =	 					"domaines";
$lang_str['tab_customers'] =	 				"clients";
$lang_str['tab_global_attributes'] =	 		"attributs globaux";
$lang_str['tab_attr_types'] =	 				"types d'attributs";

/* ------------------------------------------------------------*/
/*      form fields                                            */
/* ------------------------------------------------------------*/

$lang_str['ff_first_name'] = 					"prénom";
$lang_str['ff_last_name'] = 					"nom";
$lang_str['ff_sip_address'] = 					"adresse SIP";
$lang_str['ff_your_timezone'] = 				"votre fuseau horaire";
$lang_str['ff_username'] =						"nom d'utilisateur";
$lang_str['ff_email'] =							"courriel"; // or: "mél", "email" ?
$lang_str['ff_show_online_only'] = 				"afficher seulement les utilisateurs disponible";
$lang_str['ff_language'] = 						"langue";
$lang_str['ff_reg_confirmation'] = 				"exiger la confirmation de l'inscription";	// or: "exige la confirmation à l'inscription" ?
$lang_str['ff_uid'] = 							"UID";
$lang_str['ff_for_ser'] = 						"pour SER";
$lang_str['ff_for_serweb'] = 					"pour SerWeb"; 
$lang_str['ff_contact_email'] = 				"courriel de contact";

/* ------------------------------------------------------------*/
/*      table heading                                          */
/* ------------------------------------------------------------*/

$lang_str['th_name'] =							"nom";
$lang_str['th_sip_address'] = 					"adresse SIP";
$lang_str['th_aliases'] =						"alias";
$lang_str['th_status'] =						"état"; // or: "status" ?
$lang_str['th_timezone'] =						"fuseau horaire";
$lang_str['th_calling_subscriber'] = 			"abonné appelant"; // ?
$lang_str['th_time'] =							"heure"; // or: "date & heure", "date", "temps", "quand" ?
$lang_str['th_username'] =						"nom d'utilisateur";
$lang_str['th_email'] =							"courriel";
$lang_str['th_uid'] = 							"UID";

/* ------------------------------------------------------------*/
/*      login messages                                         */
/* ------------------------------------------------------------*/

$lang_str['bad_username'] = 					"Mauvais nom d'utilisateur ou mauvais mot de passe";
$lang_str['account_disabled'] = 				"Votre compte était désactivé"; 
$lang_str['domain_not_found'] = 				"Votre domaine non trouvé";	// or: "Votre domaine introuvable" ?
$lang_str['msg_logout_s'] = 					"Déconnecté";
$lang_str['msg_logout_l'] = 					"Vous vous etes déconnecté. Pour vous connecter à nouveau, entrer votre nom d'utilisateur et votre mot de passe ci-dessous"; // or: "Vous etes déconnecté. Pour vous connecter, taper votre nom d'utilisateur et votre mot de passe ci-dessous", "Vous avez déconnecté. Pour connecter encore, donner votre nom d'utilisateur et votre mot de passe ci-dessous", "Identifiez-vous" ?
$lang_str['userlogin'] =						"Connexion utilisateur";
$lang_str['adminlogin'] =						"Connexion administrateur";
$lang_str['enter_username_and_passw'] = 		"Veuillez entrer votre nom d'utilisateur et votre mot de passe";
$lang_str['ff_password'] =						"mot de passe";
$lang_str['l_forgot_passw'] = 					"Mot de passe oublié ?";
$lang_str['l_register'] =						"S'inscrire !";
$lang_str['l_have_my_domain'] = 				"Have-my-domain!";	//to translate 
$lang_str['remember_uname'] = 					"Se souvenir de mon nom d'utilisateur sur cet ordinateur";
$lang_str['session_expired'] = 					"Session expirée";	// or: "La session a expiré" ?
$lang_str['session_expired_relogin'] = 			"Votre session a expiré, veuillez vous reconnecter.";

/* ------------------------------------------------------------*/
/*      my account                                             */
/* ------------------------------------------------------------*/

$lang_str['msg_changes_saved_s'] =				"Changements enregistrés";
$lang_str['msg_changes_saved_l'] =				"Vos changements ont été enregistrés";
$lang_str['msg_loc_contact_deleted_s'] = 		"Contact effacé";
$lang_str['msg_loc_contact_deleted_l'] = 		"Votre contact a été effacé";
$lang_str['msg_loc_contact_added_s'] = 			"Contact ajouté";
$lang_str['msg_loc_contact_added_l'] = 			"Votre contact a été ajouté";
$lang_str['ff_your_email'] =					"votre courriel";
$lang_str['ff_fwd_to_voicemail'] =				"renvoyer à la messagerie vocale";
$lang_str['ff_allow_lookup_for_me'] = 			"permettre à d'autres de rechercher mon adresse SIP";
$lang_str['ff_status_visibility'] = 			"permettre à d'autres de voir si je suis disponible";
$lang_str['ff_your_password'] =					"votre mot de passe";
$lang_str['ff_retype_password'] =				"votre mot de passe encore"; // or: "vérifier le mot de passe", "retaper le mot de passe" ?
$lang_str['your_aliases'] =						"vos alias";
$lang_str['your_acl'] =							"Liste de contrôle d'accès ACL";
$lang_str['th_contact'] =						"contact";
$lang_str['th_expires'] =						"expire";
$lang_str['th_priority'] =						"priorité";
$lang_str['th_location'] =						"emplacement"; // or: "endroit", "localisation" ?
$lang_str['add_new_contact'] =					"ajouter un nouveau contact";
$lang_str['ff_expires'] =						"expire";
$lang_str['contact_expire_hour'] =				"une heure";
$lang_str['contact_expire_day'] =				"une jour";
$lang_str['contact_will_not_expire'] = 			"permanent";
$lang_str['acl_err_local_forward'] = 			"renvoi local interdit"; // ?
$lang_str['acl_err_gateway_forward'] = 			"contacts de passerelle interdit"; // ?

/* ------------------------------------------------------------*/
/*      phonebook                                              */
/* ------------------------------------------------------------*/

$lang_str['msg_pb_contact_deleted_s'] = 		"Contact effacé"; // "Contact" = at least a sip address
$lang_str['msg_pb_contact_deleted_l'] = 		"Le contact a été effacé de votre carnet d'adresses";
$lang_str['msg_pb_contact_updated_s'] = 		"Contact mis à jour"; // or: "Changements enregistrés" ?
$lang_str['msg_pb_contact_updated_l'] = 		"Vos changements ont été enregistrés"; // or: "Votre contact a été mis à jour"
$lang_str['msg_pb_contact_added_s'] = 			"Contact ajouté";
$lang_str['msg_pb_contact_added_l'] = 			"Le contact a été ajouté à votre carnet d'adresses";
$lang_str['phonebook_records'] =				"Fiches du carnet d'adresses"; // "Fiche" = a sip address, a name...
$lang_str['l_find_user'] =						"rechercher un utilisateur";

/* ------------------------------------------------------------*/
/*      find user                                              */
/* ------------------------------------------------------------*/

$lang_str['find_user'] =						"Rechercher un utilisateur";
$lang_str['l_add_to_phonebook'] =				"ajouter au carnet d'adresses";
$lang_str['l_back_to_phonebook'] =				"retourner au carnet d'adresses";
$lang_str['found_users'] =						"Utilisateurs";

/* ------------------------------------------------------------*/
/*      missed calls                                           */
/* ------------------------------------------------------------*/

$lang_str['th_reply_status'] = 					"réponse"; // or: ?
$lang_str['missed_calls'] = 					"Appels manqués";
$lang_str['no_missed_calls'] = 					"Aucun appel manqué"; // or: "pas d'appels manqués" ?

/* ------------------------------------------------------------*/
/*      accounting                                             */
/* ------------------------------------------------------------*/

$lang_str['th_destination'] = 					"destination";
$lang_str['th_length_of_call'] = 				"durée";
$lang_str['th_hangup'] = 						"raccrocher"; // or: ?
$lang_str['calls_count'] = 						"Appels"; // or: "Communications" ?
$lang_str['no_calls'] = 						"Aucun appel"; // or: "Pas d'appels"
$lang_str['msg_calls_deleted_s'] = 				"Appels effacés";
$lang_str['msg_calls_deleted_l'] = 				"Les appels ont été effacés avec succès";


/* ------------------------------------------------------------*/
/*      send IM                                                */
/* ------------------------------------------------------------*/

$lang_str['fe_no_im'] = 						"vous n'avez pas écrit de message";
$lang_str['fe_im_too_long'] = 					"le message instantané est trop long";
$lang_str['msg_im_send_s'] = 					"Message envoyé";
$lang_str['msg_im_send_l'] = 					"Le message a été envoyé avec succès à l'adresse";
$lang_str['max_length_of_im'] = 				"La longueur maximum du message instantané est";
$lang_str['sending_message'] = 					"envoi du message";
$lang_str['please_wait'] = 						"veuillez attendre !";
$lang_str['ff_sip_address_of_recipient'] = 		"adresse SIP du destinataire";
$lang_str['ff_text_of_message'] = 				"texte du message";
$lang_str['im_remaining'] = 					"Il reste"; // or: "Restant" ?
$lang_str['im_characters'] = 					"caractères";


/* ------------------------------------------------------------*/
/*      message store                                          */
/* ------------------------------------------------------------*/

$lang_str['instant_messages_store'] = 			"Boîte à messages instantanés";
$lang_str['voicemail_messages_store'] = 		"Boîte à messages vocaux";
$lang_str['no_stored_instant_messages'] = 		"Aucun message instantané stocké";
$lang_str['no_stored_voicemail_messages'] = 	"Aucun message vocal stocké";
$lang_str['th_subject'] = 						"Sujet"; // or: "Objet" ?
$lang_str['l_reply'] = 							"répondre"; // or: "réponse" ?
$lang_str['err_can_not_open_message'] = 		"Le message ne peut pas etre ouvert";
$lang_str['err_voice_msg_not_found'] = 			"Message non trouvé ou vous n'avez pas l'acces pour lire le message"; // or: "Message non trouvé ou vous n'avez pas l'acces pour la lecture de message" ?
$lang_str['msg_im_deleted_s'] = 				"Message effacé";
$lang_str['msg_im_deleted_l'] = 				"Le message a été effacé avec succès";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['customize_greetings'] = 				"Personnaliser les salutations";
$lang_str['err_can_not_open_greeting'] = 		"La salutation ne peut etre ouverte"; // or: "Ouverture de la salutation impossible" ?

/* ------------------------------------------------------------*/
/*      attributes                                             */
/* ------------------------------------------------------------*/

$lang_str['fe_invalid_value_of_attribute'] = 	"valeur d'attribut non valide"; // or: "valeur d'attribut inadmissible" ?
$lang_str['fe_is_not_number'] = 				"Ce n'est pas un numéro valide";
$lang_str['fe_is_not_sip_adr'] = 				"Ce n'est pas une adresse SIP valide";
$lang_str['no_attributes_defined'] = 			"Aucun attribut défini par l'administrateur"; // or: "Pas d'attributs défini par l'administrateur" ?

$lang_str['ff_send_daily_missed_calls'] =		"m'envoyer chaque jour mes appels manqués à mon courriel"; // or: "envoyer tout les jours mes appels manqués par courriel", "...quotidienement..." ?

$lang_str['ff_uri_def_f'] =						"drapeaux de défaut pour URI";	// ?
$lang_str['ff_credential_def_f'] =				"drapeaux de défaut pour qualifications";	// ?
$lang_str['ff_domain_def_f'] =					"drapeaux de défaut pour domaine";	// ?

$lang_str['attr_fwd_busy_target'] =				"destination for on-busy forwarding";	//to translate 
$lang_str['attr_fwd_noanswer_target'] =			"destination for on-no-answer forwarding";	//to translate 
$lang_str['attr_fwd_always_target'] =			"unconditional call forwarding target";	//to translate 


/* ------------------------------------------------------------*/
/*      speed dial                                             */
/* ------------------------------------------------------------*/

$lang_str['th_speed_dial'] = 					"Numéro abrégé"; // or: "Appel abrégé", "Composition rapide", "Numérotation...", "...en vitesse", "Numéros en mémoire", "...programmés", "Programmation de numéro", "Raccourcis" ?
$lang_str['th_new_uri'] = 						"Nouveau URI";




/* ------------------------------------------------------------*/
/*      registration                                           */
/* ------------------------------------------------------------*/

$lang_str['fe_not_accepted_terms'] = 			"Vous n'acceptez pas les modalités et les conditions";
$lang_str['choose_timezone'] = 					"--- veuillez choisir votre fuseau horaire ---";
$lang_str['choose_timezone_of_user'] = 			"--- veuillez choisir votre fuseau horaire d'utilisateur ---";
$lang_str['fe_not_choosed_timezone'] = 			"sélectionner votre fuseau horaire, s.v.p.";
$lang_str['fe_uname_not_follow_conventions'] = 	"le nom d'utilisateur ne suit pas des conventions suggérées";
$lang_str['fe_not_filled_password'] = 			"vous devez remplir le mot de passe";
$lang_str['fe_not_filled_your_fname'] = 		"vous devez indiquer votre prénom";
$lang_str['fe_not_filled_your_lname'] = 		"vous devez indiquer votre nom";
$lang_str['fe_uname_already_choosen_1'] = 		"Désolé, le nom d'utilisateur";
$lang_str['fe_uname_already_choosen_2'] = 		"a déja été choisi. Essayez encore";
$lang_str['err_sending_mail'] = 				"Désolé, il y a eu une erreur en envoyant le courrier. Veuillez essayez encore plus tard";
$lang_str['registration_introduction_1'] = 		"Pour s'inscrire, veuillez compléter le formulaire ci-dessous et cliquer sur le bouton de soumission en bas de la page. Un courriel vous sera envoyé confirmant votre enregistrement. Veuillez contacter"; 
$lang_str['registration_introduction_2'] = 		"si vous avez la moindre question au sujet de l'inscription et de nos services d'essai gratuit de SIP.";	// gratuit ou libre ?
$lang_str['reg_email_desc'] = 					"Adresse à laquelle une demande de confirmation d'abonnement sera envoyée. (Si une adresse non valide est donnée, aucune confirmation ne sera envoyée et aucun compte SIP ne sera créé.)";
$lang_str['reg_email_uname_desc'] = 			"Your SIP address will be same as your email address. Subscription confirmation request will be sent to this address. (If an invalid address is given, no confirmation will be sent and no SIP account will be created.) Your email address have to be from domain ".$config->domain.".";	//to translate 
$lang_str['ff_phone'] = 						"téléphone";
$lang_str['reg_phone_desc'] = 					"C'est votre numéro de téléphone RTC ou vous pouvez être joint.";
$lang_str['ff_pick_username'] = 				"sélectionner votre nom d'utilisateur";
$lang_str['reg_username_desc'] = 				"Votre adresse SIP sera nomdutilisateur@".$config->domain.". Indiquer seulement la partie nom d'utilisateur de l'adresse. Ce peut etre une adresse numérique commençant par « 8 » (par ex., « 8910 ») ou une adresse alphanumérique minuscule commençant par une lettre (par ex., jean.untel01). N'oubliez pas votre nom d'utilisateur — vous en aurez besoin pour configurer votre téléphone !";
$lang_str['ff_pick_password'] = 				"sélectionnez le mot de passe";
$lang_str['reg_password_desc'] = 				"N'oubliez pas votre mot de passe — vous en aurez besoin pour configurer votre téléphone !";
$lang_str['ff_confirm_password'] = 				"mot de passe de confirmation";
$lang_str['ff_terms_and_conditions'] = 			"modalités et conditions";
$lang_str['ff_i_accept'] = 						"J'accepte";
$lang_str['ff_timezone'] = 						"fuseau horaire";
$lang_str['ff_uname_assign_mode'] =             "Username assignment mode";	//to translate 
$lang_str['l_back_to_loginform'] = 				"Retourner au formulaire de connexion";
$lang_str['msg_user_registered_s'] = 			"Utilisateur inscrit";
$lang_str['msg_user_registered_l'] = 			"Le nouvel utilisateur a été inscrit avec succès";
$lang_str['register_new_user'] = 				"inscrire un nouvel utilisateur";
$lang_str["err_domain_of_email_not_match"] =    "Your email address is not from same domain as into which you are registering";

/* ------------------------------------------------------------*/
/*      registration - finished                                */
/* ------------------------------------------------------------*/

$lang_str['reg_finish_thanks'] = 				"Merci de vous etes inscrit chez ".$config->domain;
$lang_str['reg_finish_app_forwarded'] = 		"Votre demande a été envoyé pour approbation"; // or: "Votre demande a été envoyé pour confirmation" ?
$lang_str['reg_finish_confirm_msg'] = 			"Attendez vous à un message de confirmation sous peu."; // or: "Attendez vous à recevoir un message de confirmation sous peu.", "Vous devriez recevoir une confirmation sous peu." ?
$lang_str['reg_finish_sip_address'] = 			"Nous réservons l'adresse SIP suivante pour vous :";
$lang_str['reg_finish_questions_1'] = 			"Si vous avez n'importe quelle autre question, n'hésitez pas à envoyer"; // or: "Si vous avez toute autre question, n'hésitez pas à envoyer" ?
$lang_str['reg_finish_questions_2'] = 			"un courriel à";

/* ------------------------------------------------------------*/
/*      registration - confirmation                            */
/* ------------------------------------------------------------*/

$lang_str['reg_conf_congratulations'] = 		"Félicitations ! Votre compte ".$config->domain." a été installé !";
$lang_str['reg_conf_set_up'] = 					"Votre compte ".$config->domain." a été installé !";
$lang_str['reg_conf_jabber_failed'] = 			"Mais votre inscription à la passerelle Jabber ".$config->domain." a échoué.";
$lang_str['reg_conf_contact_infomail_1'] = 		"Veuillez contacter";
$lang_str['reg_conf_contact_infomail_2'] = 		"pour davantage d'aide.";
$lang_str['reg_conf_failed'] = 					"Nous regrettons mais votre essai de confirmation ".$config->domain." a échoué.";
$lang_str['reg_conf_nr_not_exists'] = 			"Soit votre numéro de confirmation est mauvais, soit votre compte a déjà été créé !";
$lang_str['err_reg_conf_not_exists_conf_num'] = "Désolé. Un tel numéro de confirmation n'existe pas"; // or: "Désolé. Il n'y a pas de tel numéro de confirmation qui existe" ?

/* ------------------------------------------------------------*/
/*      registration - forgot password                         */
/* ------------------------------------------------------------*/

$lang_str['forgot_pass_head'] =					"Mot de passe oublié ?";
$lang_str['forgot_pass_introduction'] = 		"Si vous avez oublié votre mot de passe, veuillez écrire votre nom d'utilisateur dans le formulaire ci-dessous. Un courriel contenant votre mot de passe sera alors envoyé à l'adresse courriel avec laquelle vous vous êtes inscrit !";
$lang_str['forgot_pass_sended'] =				"Le nouveau mot de passe a été créé et envoyé à l'adresse courriel avec laquelle vous vous êtes inscrit.";
$lang_str['msg_pass_conf_sended_s'] = 			"Informations de connexion envoyées";
$lang_str['msg_pass_conf_sended_l'] = 			"Les informations de connexion ont été envoyées à l'adresse courriel";
$lang_str['msg_password_sended_s'] = 			"Nouveau mot de passe envoyé";
$lang_str['msg_password_sended_l'] = 			"Le nouveau mot de passe a été envoyé à l'adresse courriel";
$lang_str['err_no_user'] = 						"Désolé, ce n'est pas un nom d'utilisateur inscrit !";

/* ------------------------------------------------------------*/
/*      admin - users management                               */
/* ------------------------------------------------------------*/

$lang_str['err_admin_can_not_delete_user_1'] = 	"Vous ne pouvez pas supprimer l'utilisateur";
$lang_str['err_admin_can_not_delete_user_2'] = 	"cet utilisateur est d'un domaine différent";
$lang_str['msg_acl_updated_s'] = 				"ACL mise à jour";
$lang_str['msg_acl_updated_l'] = 				"Liste de contrôle d'acces ACL d'utilisateur a été mise à jour";
$lang_str['msg_user_deleted_s'] = 				"Utilisateur effacé";
$lang_str['msg_user_deleted_l'] = 				"L'utilisateur a été effacé avec succès";
$lang_str['msg_user_undeleted_s'] = 			"User undeleted";	//to translate 
$lang_str['msg_user_undeleted_l'] = 			"User has been undeleted succesfuly";	//to translate 
$lang_str['msg_user_purged_s'] = 				"User purged";	//to translate 
$lang_str['msg_user_purged_l'] = 				"User has been purged succesfuly";	//to translate 
$lang_str['th_phone'] = 						"téléphone";
$lang_str['l_acl'] = 							"ACL";
$lang_str['l_aliases'] = 						"alias";
$lang_str['l_account'] = 						"compte";
$lang_str['l_accounting'] = 					"comptabilité";
$lang_str['realy_you_want_delete_this_user'] =	"Voulez-vous vraiment effacer cet utilisateur ?";
$lang_str['realy_you_want_purge_this_user'] =	"Do you realy want purge this user?";	//to translate 
$lang_str['l_credentials'] = 					"qualifications";
$lang_str['l_uris'] = 					        "SIP URIs";	//to translate 
$lang_str['user_has_no_credentials'] = 			"L'utilisateur n'a pas les qualifications";	// or: "L'utilisateur n'a aucune qualification" ?
$lang_str['user_has_no_sip_uris'] = 			"User has no SIP URIs";	//to translate 
$lang_str['err_cannot_delete_own_account'] = 	"You can't delete your own account";	//to translate 
$lang_str['err_cannot_disable_own_account'] = 	"You can't disable your own account";	//to translate 
$lang_str['ff_show_deleted_users'] =            "show deleted users";	//to translate 
$lang_str['deleted_user'] = 					"DELETED";	//to translate 

/* ------------------------------------------------------------*/
/*      admin - ACL, aliases                                   */
/* ------------------------------------------------------------*/

$lang_str['access_control_list_of_user'] = 		"Liste de contrôle d'acces ACL d'utilisateur";
$lang_str['have_not_privileges_to_acl'] = 		"Vous n'avez aucun privilege pour contrôler l'ACL";
$lang_str['err_alias_already_exists_1'] = 		"L'alias :";
$lang_str['err_alias_already_exists_2'] = 		"existe déja";
$lang_str['msg_alias_deleted_s'] = 				"Alias effacé";
$lang_str['msg_alias_deleted_l'] = 				"L'alias de l'utilisateur a été effacé";
$lang_str['msg_alias_updated_s'] = 				"Alias mis à jour";
$lang_str['msg_alias_updated_l'] = 				"Vos changements ont été conservés";
$lang_str['msg_alias_added_s'] = 				"Alias ajouté";
$lang_str['msg_alias_added_l'] = 				"L'alias a été ajouté à l'utilisateur";
$lang_str['change_aliases_of_user'] = 			"Changer les alias de l'utilisateur";
$lang_str['ff_alias'] = 						"alias";
$lang_str['th_alias'] = 						"alias";
$lang_str['realy_you_want_delete_this_alias'] = "Voulez-vous vraiment effacer cet alias ?";
$lang_str['user_have_not_any_aliases'] = 		"Utilisateur n'ayant aucun alias";
$lang_str['ff_is_canon'] = 						"est canonique";
$lang_str['ff_is_enabled'] = 					"est activé";
$lang_str['ff_uri_is_to'] = 					"peut être employé comme URI « vers »";
$lang_str['ff_uri_is_from'] = 					"peut être employé comme URI « de »";
$lang_str['th_is_canon'] = 						"canonique";
$lang_str['th_uri_is_to'] = 					"vers";
$lang_str['th_uri_is_from'] = 					"de";
$lang_str['l_ack'] = 							"reconnaître";
$lang_str['l_deny'] = 							"refuser";
$lang_str['uris_with_same_uname_did'] = 		"URI existants avec le même nom d'utilisateur et domaine";
$lang_str['ack_values'] = 						"Reconnaître les valeurs";
$lang_str['uri_already_exists'] = 				"L'URI avec le nom d'utilisateur et le domaine choisis existe déjà. Veuillez reconnaître les valeurs.";
$lang_str['is_to_warning'] = 					"AVERTISSEMENT : le drapeau « EST À » est placé pour un autre URI. Si vous continuez, ce drapeau sera supprimé dans l'URI";
$lang_str['err_canon_uri_exists'] = 			"Il n'est pas possible de placer d'URI canonique parce qu'il y a un autre URI canonique que vous ne pouvez pas affecter";	// or: "Ne peut pas placer URI canonique parce qu'il y a un autre URI canonique que vous ne pouvez pas affecter" ?
$lang_str['uid_with_alias'] = 					"Liste des UID avec alias";

/* ------------------------------------------------------------*/
/*      admin privileges                                       */
/* ------------------------------------------------------------*/

$lang_str['admin_privileges_of'] = 				"Privilèges administratifs de";
$lang_str['admin_competence'] = 				"compétence administrative";
$lang_str['ff_is_admin'] = 						"est administrateur";
$lang_str['ff_change_privileges'] = 			"change les privilèges des administrateurs";
$lang_str['ff_is_hostmaster'] = 				"c'est le serveur-maître";	// ?
$lang_str['acl_control'] = 						"contrôle des ACL";
$lang_str['msg_privileges_updated_s'] = 		"Privilèges mis à jour";
$lang_str['msg_privileges_updated_l'] = 		"Les privilèges de l'utilisateur ont été mis à jour";
$lang_str['list_of_users'] = 					"Liste des utilisateurs";
$lang_str['th_domain'] = 						"domaine";
$lang_str['l_change_privileges'] = 				"changer les privilèges";
$lang_str['ff_domain'] = 						"domaine";
$lang_str['ff_realm'] = 						"royaume";
$lang_str['th_realm'] = 						"royaume";
$lang_str['ff_show_admins_only'] = 				"afficher seulement les adminitrateurs";
$lang_str['err_cant_ch_priv_of_hostmaster'] = 	"Cet utilisateur est serveur-maître. Vous ne pouvez pas changer des privilèges de serveur-maître parce que vous n'êtes pas serveur-maître !";


/* ------------------------------------------------------------*/
/*      attribute types                                        */
/* ------------------------------------------------------------*/

$lang_str['fe_not_filled_name_of_attribute'] = 	"vous devez remplir le nom d'attribut";
$lang_str['fe_empty_not_allowed'] = 			"ne peut pas être vide";
$lang_str['ff_order'] = 						"ordre";	// or: "ranger", "classer" ?
$lang_str['ff_att_name'] = 						"nom de l'attribut";
$lang_str['ff_att_type'] = 						"type de l'attribut";
$lang_str['ff_att_access'] = 					"accès";	// or: "accéder" ?
$lang_str['ff_label'] = 						"étiquette";
$lang_str['ff_att_group'] = 					"group";	//to translate 
$lang_str['ff_att_uri'] = 						"uri";	//to translate 
$lang_str['ff_att_user'] = 						"utilisateur";
$lang_str['ff_att_domain'] = 					"domaine";
$lang_str['ff_att_global'] = 					"global";
$lang_str['ff_multivalue'] = 					"valeur multiple";	// or: "multivaleur" ?
$lang_str['ff_att_reg'] = 						"requis à l'enregistrement";
$lang_str['ff_att_req'] = 						"requis (non vide)";
$lang_str['ff_fr_timer'] = 						"temps d'attente de réponse"; // "response waiting time" ...or: "temporisation de réponse final" ?
$lang_str['ff_fr_inv_timer'] = 					"temps d'attente de réponse d'invitation"; // "invitation response waiting time" ...or: "temporisation de réponse d'invitation final" ?
$lang_str['ff_uid_format'] = 					"format des UID nouvellement créés";
$lang_str['ff_did_format'] = 					"format des DID nouvellement créés";

$lang_str['at_access_0'] = 						"accès complet";
$lang_str['at_access_1'] = 						"lecture seulement pour les utilisateurs";
$lang_str['at_access_3'] = 						"pour les admin. seulement (lecture/écriture)";
$lang_str['at_access_21'] = 					"read only";	//to translate 


$lang_str['th_att_name'] = 						"nom de l'attribut";
$lang_str['th_att_type'] = 						"type de l'attribut";
$lang_str['th_order'] = 						"ordre";
$lang_str['th_label'] = 						"étiquette";
$lang_str['th_att_group'] = 					"group";	//to translate 
$lang_str['fe_order_is_not_number'] = 			"« ordre » n'est pas pas un numéro valide";

$lang_str['fe_not_filled_item_label'] = 		"vous devez remplir l'étiquette de l'élément";
$lang_str['fe_not_filled_item_value'] = 		"vous devez remplir la valeur de l'élément";
$lang_str['ff_item_label'] = 					"étiquette de l'élément";
$lang_str['ff_item_value'] = 					"valeur de l'élément";
$lang_str['th_item_label'] = 					"étiquette de l'élément";
$lang_str['th_item_value'] = 					"valeur de l'élément";
$lang_str['l_back_to_editing_attributes'] = 	"retourner à l'édition des attributs";
$lang_str['realy_want_you_delete_this_attr'] = 	"Voulez-vous vraiment effacer cet attribut ?";
$lang_str['realy_want_you_delete_this_item'] = 	"Voulez-vous vraiment effacer cet élément ?";


$lang_str['attr_type_warning'] = 				"Sur cette page, vous pouvez définir de nouveaux attributs et changer leurs types, leurs drapeaux, etc. Les attributs prédéfinis sont la plupart du temps employés à l'intérieur de SerWeb ou de SER. Ne les changez pas, si vous ne savez pas ce que vous faites !!!";
$lang_str['at_hint_order'] = 					"Les attributs sont arrangés dans cet ordre dans SerWeb";
$lang_str['at_hint_label'] = 					"Étiquette d'attribut affichée dans SerWeb. Si elle débute par « @ », la chaine de caractères est traduite dans la langue de l'utilisateur avec les fichiers du dossier « lang ». Il est de votre responsabilité que toutes les expressions utilisées soient présentes dans les fichiers, pour toutes les langues.";
$lang_str['at_hint_for_ser'] = 					"L'attribut est chargé par SER. Seul les attributs récemment créés sont affectés par le changement de ceci.";
$lang_str['at_hint_for_serweb'] = 				"L'attribut est chargé par SerWeb. Seul les attributs récemment créés sont affectés par le changement de ceci.";
$lang_str['at_hint_user'] = 					"L'attribut est affiché sur la page de préférences de l'utilisateur";
$lang_str['at_hint_domain'] = 					"L'attribut est affiché sur page de préférences du domaine";
$lang_str['at_hint_global'] = 					"L'attribut est affiché sur page de préférences globales";
$lang_str['at_hint_multivalue'] = 				"L'attribut peut avoir des valeurs multiples";
$lang_str['at_hint_registration'] = 			"L'attribut est affiché sur le formulaire d'inscription d'utilisateur";
$lang_str['at_hint_required'] = 				"L'attribut doit avoir n'importe quelle valeur non vide. Non utilisé pour tous les types. Utilisé pour des types : int, email_adr, sip_adr, etc.";


$lang_str['ff_att_default_value'] = 			"valeur par défaut";
$lang_str['th_att_default_value'] = 			"valeur par défaut";
$lang_str['ff_set_as_default'] = 				"mettre par défaut"; // or: "mettre à défaut" ?
$lang_str['edit_items_of_the_list'] = 			"modifier les éléments de la liste";

$lang_str['o_lang_not_selected'] = 				"non selectionné";

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


$lang_str['change_credentials_of_user'] =				"Changer les qualifications de l'utilisateur";

$lang_str['th_password'] =								"mot de passe";
$lang_str['th_for_ser'] =								"pour SER";
$lang_str['th_for_serweb'] =							"pour SerWeb";

$lang_str['err_credential_changed_domain'] =			"Le domaine de l'utilisateur a été changé. Vous devez également remplir le nouveau mot de passe";
$lang_str['warning_credential_changed_domain'] =		"Serweb est configuré pour ne pas stocker des mots de passe en clair. Ce qui signifie que, si vous changez le domaine de l'utilisateur, vous devez également remplir le champ du mot de passe. Autrement, le mot de passe haché ne sera plus valable.";

$lang_str['realy_want_you_delete_this_credential'] = 	"Voulez-vous vraiment effacer cet qualification ?";


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

$lang_str['sel_item_all_calls'] =				"Tous les appels";
$lang_str['sel_item_outgoing_calls'] = 			"Appels sortants seulement";
$lang_str['sel_item_incoming_cals'] = 			"Appels entrants seulement";

/* ------------------------------------------------------------*/
/*      voicemail                                              */
/* ------------------------------------------------------------*/

$lang_str['fe_no_greeeting_file'] = 			"vous n'avez pas choisi de fichier de salutation";
$lang_str['fe_invalid_greeting_file'] = 		"fichier de salutation non valide";
$lang_str['fe_greeting_file_no_wav'] = 			"type de fichier de salutation doit etre audio/wav";
$lang_str['fe_greeting_file_too_big'] = 		"fichier de salutation trop volumineux";
$lang_str['msg_greeting_stored_s'] = 			"Salutation stockée";
$lang_str['msg_greeting_stored_l'] = 			"Votre salutation a été stockée avec succès";
$lang_str['msg_greeting_deleted_s'] = 			"Salutation enlevée";
$lang_str['msg_greeting_deleted_l'] = 			"Votre salutation a été enlevée avec succès";

/* ------------------------------------------------------------*/
/*      whitelist                                              */
/* ------------------------------------------------------------*/

$lang_str['err_whitelist_already_exists'] = 	"L'entrée dans la liste blanche existe déja"; // or: "entrée dans la liste blanche déja existante" ?

/* ------------------------------------------------------------*/
/*      multidomain                                            */
/* ------------------------------------------------------------*/

$lang_str['fe_not_customer_name'] = 			"Vous devez remplir nom du client";
$lang_str['ff_customer_name'] = 				"nom du client";
$lang_str['no_customers'] = 					"Aucun client";
$lang_str['customer'] = 						"Client";

$lang_str['msg_customer_updated_s'] = 			"Client mis à jour";
$lang_str['msg_customer_updated_l'] = 			"Le nom de client a été mis à jour";
$lang_str['msg_customer_deleted_s'] = 			"Client effacé";
$lang_str['msg_customer_deleted_l'] = 			"Le Client a été effacé";
$lang_str['msg_customer_added_s'] = 			"Client créé";
$lang_str['msg_customer_added_l'] = 			"Le nouveau client a été créé";
$lang_str['err_customer_own_domains'] = 		"Le client possède quelques domaines, il n'est pas possible de l'effacer";

$lang_str['d_id'] = 							"ID du domaine";
$lang_str['d_name'] = 							"Nom du domaine";
$lang_str['list_of_domains'] = 					"Liste des domaines";
$lang_str['showed_domains'] = 					"Affichage des domaines";
$lang_str['no_domains_found'] = 				"Aucun domaine trouvé";
$lang_str['new_dom_name'] = 					"Ajouter un nouveau nom de domaine";
$lang_str['owner'] = 							"Propriétaire";

$lang_str['realy_delete_domain'] = 				"Voulez-vous vraiment effacer ce domaine ?";
$lang_str['realy_purge_domain'] =               "Do you realy want purge this domain?";	//to translate 
$lang_str['l_create_new_domain'] = 				"créer un nouveau domaine";
$lang_str['l_reload_ser'] = 					"recharger SER etle serveur Web";
$lang_str['no_domain_name_is_set'] = 			"Écrivez au moins un nom de domaine";
$lang_str['prohibited_domain_name'] = 			"Désolé, ce nom de domaine est interdit";
$lang_str['can_not_del_last_dom_name'] = 		"Il n'est pas possible de supprimer le seul nom de domaine";

$lang_str['msg_domain_reload_s'] = 				"Config. rechargée";
$lang_str['msg_domain_reload_l'] = 				"La configuration de SER et du serveur Web a été rechargée";
$lang_str['msg_domain_deleted_s'] = 			"Domaine effacé";
$lang_str['msg_domain_deleted_l'] = 			"Ce domaine n'est plus servi et tous les fiches associés incluant les données d'abonné seront bientôt supprimés. Assurez vous que les enregistrements DNS ne se rapportent plus à ce serveur";
$lang_str['msg_domain_undeleted_s'] = 			"Domain undeleted";	//to translate 
$lang_str['msg_domain_undeleted_l'] = 			"Domain has been undeleted succesfuly";	//to translate 
$lang_str['msg_domain_purged_s'] = 				"Domain purged";	//to translate 
$lang_str['msg_domain_purged_l'] = 				"Domain has been purged succesfuly";	//to translate 

$lang_str['assigned_domains'] = 				"Domaines assignés";
$lang_str['unassigned_domains'] = 				"Domaines non assignés";	//or: "Domaines désassignés" ?
$lang_str['l_assign_domain'] = 					"assigner le domaine";
$lang_str['l_unassign_domain'] = 				"désassigner le domaine";	// or: "non assigner le domaine" ?
$lang_str['l_assign'] =                                  "assigner";
$lang_str['l_unassign'] =                                "désassigner";
$lang_str['l_assigned_domains'] = 				"Domaines";
$lang_str['l_change_layout'] = 					"Disposition";
$lang_str['l_domain_attributes'] = 				"Attributs";
$lang_str['l_unassign_admin'] = 				"désassigner l'admin.";
$lang_str['l_set_canon'] = 						"placer en canonique";

$lang_str['admins_of_domain'] = 				"Admin. de ce domaine";
$lang_str['no_admins'] = 						"Aucuns admin.";

$lang_str['ff_address'] = 						"adresse";

$lang_str['lf_terms_and_conditions'] =			"modalités et conditions";
$lang_str['lf_mail_register_by_admin'] = 		"Courrier envoyé à l'utilisateur quand est créé par l'admin.";	// ??
$lang_str['lf_mail_register'] = 				"inscription de confirmation du courrier";	// ??
$lang_str['lf_mail_fp_conf'] = 					"confirmation par courrier du mot de passe de remise à zéro quand l'ancien a été oublié";	// ??
$lang_str['lf_mail_fp_pass'] = 					"expédier le nouveau mot de passe quand l'ancien a été oublié";	// ??
$lang_str['lf_config'] = 						"configuration du domaine";

$lang_str['l_toggle_wysiwyg'] = 				"basculer en tel-tel (WYSIWYG)";
$lang_str['l_upload_images'] = 					"télédécharger les images";	// or: "téléverser les images" ?
$lang_str['l_back_to_default'] = 				"restaurer le contenu par défaut";

$lang_str['wysiwyg_warning'] = 					"Veuillez être prudent en utilisant l'éditeur tel-tel (WYSIWYG). Prolog.html doit commencer par l'élément &lt;body&gt; et epilog.html doit finir par l'élément &lt;/body&gt;. L'éditeur tel-tel (WYSIWYG) peut les défaire ! Veuillez noter que la liste de compatibilité de l'éditeur tel-tel (WYSIWYG) utilisé est : « Mozilla, MSIE et FireFox (Safari expérimental) ». Si votre navigateur n'est pas sur la liste, l'éditeur tel-tel (WYSIWYG) peut ne pas fonctionner.";

$lang_str['choose_one'] = 						"en choisir un";

$lang_str['layout_files'] = 					"Fichiers disposition";	// or: "Fichiers de disposition" ?
$lang_str['text_files'] = 						"Fichiers texte";

$lang_str['fe_domain_not_selected']	= 			"Le domaine pour l'utilisateur n'est pas sélectionné";

$lang_str['th_old_versions'] = 					"Anciennes versions de ce fichier";
$lang_str['initial_ver'] = 						"initiale";
$lang_str['ff_show_deleted_domains'] =          "show deleted domains";	//to translate 
$lang_str['deleted_domain'] = 					"DELETED";	//to translate 


$lang_str['err_dns_lookup'] =                   "Error during DNS lookup. Can not check the DNS setting";	//to translate 
$lang_str['err_no_srv_record'] =                "There is no SRV record for hostname <hostname>";	//to translate 
$lang_str['err_wrong_srv_record'] =             "SRV record(s) found, but it has wrong target host or port. Following SRV records have been found: ";	//to translate 
$lang_str['err_domain_already_hosted'] = 		"This domain is already hosted on this server";	//to translate 
$lang_str['err_cannot_delete_own_domain'] = 	"You can't delete domain of your own account";	//to translate 
$lang_str['err_cannot_disable_own_domain'] = 	"You can't disable domain of your own account";	//to translate 



/* ------------------------------------------------------------*/
/*      wizard - create new domain                             */
/* ------------------------------------------------------------*/

$lang_str['register_new_admin'] = 				"Inscrire un nouvel admin. pour le domaine";
$lang_str['assign_existing_admin'] = 			"Assigner un admin. existant au domaine";
$lang_str['assign_admin_to_domain'] = 			"Assigner un admin. au domaine";
$lang_str['create_new_domain'] = 				"Créer un nouveau domaine";
$lang_str['l_create_new_customer'] = 			"créer un nouveau client";
$lang_str['create_new_customer'] = 				"Créer un nouveau client";
$lang_str['l_close_window'] = 					"fermer la fenêtre";
$lang_str['step'] = 							"étape";
$lang_str['l_select'] = 						"sélectionner";
$lang_str['domain_setup_success'] = 			"Le nouveau domaine a été installé avec succès !";
$lang_str['l_skip_asignment_of_admin'] = 		"sauter l'attribution d'admin.";

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


?>
