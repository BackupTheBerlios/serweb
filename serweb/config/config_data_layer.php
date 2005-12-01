<?
/*
 * $Id: config_data_layer.php,v 1.18 2005/12/01 12:04:59 kozlik Exp $
 */


		////////////////////////////////////////////////////////////////
		//            configure connection to SER

		/**
		 *	Use XML RPC instead of FIFO for manage SER.
		 *	This feature is still experimental.
		 *	If SER useing XXL extension, this must be set to true.
		 *
		 *	If this is set to true the database setting is irelevant. In this 
		 *  case, this is obtained from SER automaticaly. 
		 */
		$config->use_rpc = false;

		/* these are options for connect to XML-RPC interface of SER
		*/

		$config->ser_rpc['host']	= "localhost";		//SER host
		$config->ser_rpc['port']	= 5060;


		////////////////////////////////////////////////////////////////
		//            configure database

		/* these are the defaults with which SER installs; if you changed
		   the SER account for SQL database, you need to update here 
		   
		   If $config->use_rpc = true you need not set data_sql values, it is
		   obtained from SER automaticaly
		*/

		$config->data_sql=new stdClass();
		
		$config->data_sql->type="mysql";			//type of db host, enter "mysql" for MySQL or "pgsql" for PostgreSQL

		$i=0;
		$config->data_sql->host[$i]['host']="localhost";	//database host
		$config->data_sql->host[$i]['port']="";				//database port - leave empty for default
		$config->data_sql->host[$i]['name']="ser";			//database name
		$config->data_sql->host[$i]['user']="ser";			//database conection user
		$config->data_sql->host[$i]['pass']="heslo";		//database conection password

		// If you want to configure additional backup SQL servers, do so below. 
		/*
		$i++;
		$config->data_sql->host[$i]['host']="localhost";	//database host
		$config->data_sql->host[$i]['port']="";				//database port - leave empty for default
		$config->data_sql->host[$i]['name']="ser";			//database name
		$config->data_sql->host[$i]['user']="ser";			//database conection user
		$config->data_sql->host[$i]['pass']="heslo";		//database conection password
		*/
		// If you want to configure more SQL backup servers, copy and paste the above (including the "$i++;")
 		

		/**
		 *  Needs to be set when you are useing MySQL >= 4.1
		 *  see mysql manual for more info
		 *
		 * 	$config->data_sql->collation = "utf8_general_ci";
		 */

		$config->data_sql->collation = "";

		/**
		 *  Set to true when you are useing MySQL >= 4.1
		 *  This option set mysql system variables character_set_client, 
		 *  character_set_connection, and character_set_results to charset 
		 *  used in serweb
		 *
		 * 	$config->data_sql->set_charset = true;
		 */

		$config->data_sql->set_charset = false;

		
		/**
		 *	Default timeout after which is again looked up for proxy of user 
		 */
		$config->XXL_proxy_asigment_lifetime = 900;
 		
		/**
		 *	Lifetime of deleted records. (in days)
		 *	Deleted domains and subscribers will be kept in DB for given time
		 *	interval. After expiring it, records will be permanently deleted.
		 */
		$config->keep_deleted_interval = 30;
		
		/**
		 *	Lifetime of pending records. (in hours)
		 *	Pending subscribers will be kept in DB for given time
		 *	interval. After expiring it, records will be permanently deleted.
		 */
		$config->keep_pending_interval = 24;
		
		/**
		 *	Lifetime of acc records. (in days)
		 *	Accounting records will be kept in DB for given time
		 *	interval. After expiring it, records will be permanently deleted.
		 */
		$config->keep_acc_interval = 60;
		
		/**
		 *	Number of kept versions of one file
		 *	This variable tells how many versions of one file (from directory 
		 *	with domain specific config) is stored. If is set to zero files
		 *	are not backuped on update of them.
		 */
		$config->backup_versions_nr = 10;
		
		/* these are setting required by ldap, you need to change it only if you are using ldap to 
		   store some data. If you are using ldap, you need to instal PEAR package db_ldap2 by command:
		
		   pear install -f db_ldap2
		*/
		
		$config->data_ldap=new stdClass();

		$config->data_ldap->version=3;							//version of LDAP protocol, can be 2 or 3
		$config->data_ldap->base_dn="dc=mydomain,dc=org";		// The base DN of your LDAP server
		
		$i=0;
		$config->data_ldap->host[$i]['host']="localhost";		//ldap host
		$config->data_ldap->host[$i]['port']="";				//ldap port - leave empty for default
																//ldap conection user
		$config->data_ldap->host[$i]['login_dn']="cn=admin,dc=mydomain,dc=org";
		$config->data_ldap->host[$i]['login_pass']="heslo";		//ldap conection password

		// If you want to configure additional backup LDAP servers, do so below. 
		/*
		$i++;
		$config->data_ldap->host[$i]['host']="localhost";		//ldap host
		$config->data_ldap->host[$i]['port']="";				//ldap port - leave empty for default
																//ldap conection user
		$config->data_ldap->host[$i]['login_dn']="cn=admin,dc=mydomain,dc=org";
		$config->data_ldap->host[$i]['login_pass']="heslo";		//ldap conection password
		*/
		// If you want to configure more LDAP backup servers, copy and paste the above (including the "$i++;")
		

		/* next line specify how are users keyed. May contains values: 'uuid' (for keying by uuid) 
		   or 'username' (for keyiing by username and domain). The right value depends on your
		   database schema.
		*/
		$config->users_indexed_by= "username";

		/* if true, serweb will create/delete entries in table uri when alisa will be created/deleted
		   (working only in uuidized version)
		*/
		$config->use_table_uri = false;

		/* 	if true, serweb will add new subscriber also into aliases table instead of into subscriber table only
		*/
		$config->copy_new_subscribers_to_aliases_table = false;		
		
		/* Unless you used brute-force to change SER table names */
		$config->data_sql->table_subscriber="subscriber";
		$config->data_sql->table_pending="pending";
		$config->data_sql->table_grp="grp";
		$config->data_sql->table_aliases="aliases";
		$config->data_sql->table_uuidaliases="uuidaliases";
		$config->data_sql->table_location="location";
		$config->data_sql->table_missed_calls="missed_calls";
		$config->data_sql->table_accounting="acc";
		$config->data_sql->table_cdr="cdr";
		$config->data_sql->table_phonebook="phonebook";
		$config->data_sql->table_event="event";
		$config->data_sql->table_netgeo_cache="netgeo_cache";
		$config->data_sql->table_ser_mon="server_monitoring";
		$config->data_sql->table_ser_mon_agg="server_monitoring_agg";
		$config->data_sql->table_message_silo="silo";
		$config->data_sql->table_voice_silo="voice_silo";
		$config->data_sql->table_user_preferences="usr_preferences";
		$config->data_sql->table_user_preferences_types="usr_preferences_types";
		$config->data_sql->table_providers="providers";
		$config->data_sql->table_admin_privileges="admin_privileges";
		$config->data_sql->table_speed_dial="speed_dial";
		$config->data_sql->table_calls_forwarding="calls_forwarding";
		$config->data_sql->table_domain="domain";
		$config->data_sql->table_whitelist="whitelist";
		$config->data_sql->table_lcr="lcr";
		$config->data_sql->table_uri="uri";
		$config->data_sql->table_customer="customer";
		$config->data_sql->table_dom_preferences="dom_preferences";


		$config->flags = array(
							"DB_LOAD_SER"   => 1,	// if set, attribute is mean for SER
							"DB_DISABLED"   => 2,   // if set, attribute is disabled
							"DB_CANON"      => 4,	// canonical domain name (domain table)
							"DB_IS_TO"      => 8,   // URI may be used in r-uri
							"DB_IS_FROM"    => 16,  // URI may be used in from
							"DB_FOR_SERWEB" => 32,  // if set, attribute is mean for SERWEB
							"DB_PENDING"    => 64,  // account is pending for confirmation
							"DB_DELETED"    => 128	// row is marked as deleted
		                  );
		

		$config->data_layer_always_required_functions=array('check_passw_of_user',
															'get_privileges_of_user',
															'get_user_dom_from_uid',
															'set_db_charset',
															'set_db_collation',
															'get_domains_of_admin');


		/*
		 *	Definition of table credentials
		 */
		 
		$config->data_sql->table_credentials="credentials";

		$config->data_sql->credentials = new stdClass();
 		$config->data_sql->credentials->cols = new stdClass();
		
		$config->data_sql->credentials->table_name = 		"credentials";
		
 		$config->data_sql->credentials->cols->uname = 		"auth_username";
 		$config->data_sql->credentials->cols->realm = 		"realm";
 		$config->data_sql->credentials->cols->password = 	"password";
 		$config->data_sql->credentials->cols->flags = 		"flags";
 		$config->data_sql->credentials->cols->ha1 = 		"ha1";
 		$config->data_sql->credentials->cols->ha1b = 		"ha1b";
 		$config->data_sql->credentials->cols->uid = 		"uid";

 		$config->data_sql->credentials->flag_values = &$config->flags;



		/*
		 *	Definition of table user_attrs
		 */
		 
		$config->data_sql->user_attrs = new stdClass();
 		$config->data_sql->user_attrs->cols = new stdClass();
		
		$config->data_sql->user_attrs->table_name = 		"user_attrs";
		
 		$config->data_sql->user_attrs->cols->uid = 			"uid";
 		$config->data_sql->user_attrs->cols->name = 		"name";
 		$config->data_sql->user_attrs->cols->value = 		"value";
 		$config->data_sql->user_attrs->cols->type = 		"type";
 		$config->data_sql->user_attrs->cols->flags = 		"flags";

 		$config->data_sql->user_attrs->flag_values = &$config->flags;



		/*
		 *	Definition of table domain_attrs
		 */
		 
		$config->data_sql->domain_attrs = new stdClass();
 		$config->data_sql->domain_attrs->cols = new stdClass();
		
		$config->data_sql->domain_attrs->table_name = 		"domain_attrs";
		
 		$config->data_sql->domain_attrs->cols->did = 		"did";
 		$config->data_sql->domain_attrs->cols->name = 		"name";
 		$config->data_sql->domain_attrs->cols->value = 		"value";
 		$config->data_sql->domain_attrs->cols->type = 		"type";
 		$config->data_sql->domain_attrs->cols->flags = 		"flags";

 		$config->data_sql->domain_attrs->flag_values = &$config->flags;



		/*
		 *	Definition of table global_attrs
		 */
		 
		$config->data_sql->global_attrs = new stdClass();
 		$config->data_sql->global_attrs->cols = new stdClass();
		
		$config->data_sql->global_attrs->table_name = 		"global_attrs";
		
 		$config->data_sql->global_attrs->cols->name = 		"name";
 		$config->data_sql->global_attrs->cols->value = 		"value";
 		$config->data_sql->global_attrs->cols->type = 		"type";
 		$config->data_sql->global_attrs->cols->flags = 		"flags";

 		$config->data_sql->global_attrs->flag_values = &$config->flags;


		/*
		 *  names of columns in table speed dial
		 *
		 *	DON'T CHANGE IF YOU DOESN'T KNOW WHAT YOU ARE DOING 
		 */															
		$config->data_sql->speed_dial = new stdClass();															
 		$config->data_sql->speed_dial->sd_username = 	"sd_username";
 		$config->data_sql->speed_dial->sd_domain = 		"sd_domain";
 		$config->data_sql->speed_dial->new_uri = 		"new_uri";
 		$config->data_sql->speed_dial->fname = 			"fname";
 		$config->data_sql->speed_dial->lname = 			"lname";


		/*
		 *  names of columns in table customer
		 */															
		$config->data_sql->customer = new stdClass();															
 		$config->data_sql->customer->id = 				"c_id";
 		$config->data_sql->customer->name = 			"name";
 		$config->data_sql->customer->address = 			"address";
 		$config->data_sql->customer->phone = 			"phone";
 		$config->data_sql->customer->email = 			"email";

		/*
		 *  names of columns in table domain
		 */															
		$config->data_sql->domain = new stdClass();															
 		$config->data_sql->domain->id = 				"d_id";
 		$config->data_sql->domain->name = 				"domain";

		/*
		 *  names of columns in table dom_preferences
		 */															
		$config->data_sql->dom_pref = new stdClass();															
 		$config->data_sql->dom_pref->id = 				"d_id";
 		$config->data_sql->dom_pref->att_name = 		"att_name";
 		$config->data_sql->dom_pref->att_value = 		"att_value";

		/*
		 *  names of columns in table subscriber
		 */															
		$config->data_sql->subscriber = new stdClass();															
 		$config->data_sql->subscriber->uuid = 			$config->users_indexed_by=='uuid' ? "uuid" : "phplib_id";
 		$config->data_sql->subscriber->username = 		"username";
 		$config->data_sql->subscriber->domain = 		"domain";

		/*
		 *  names of columns in table usr_preferences
		 */															
		$config->data_sql->usr_pref = new stdClass();															
 		$config->data_sql->usr_pref->uuid = 			"uuid";
 		$config->data_sql->usr_pref->username = 		"username";
 		$config->data_sql->usr_pref->domain = 			"domain";
 		$config->data_sql->usr_pref->att_name = 		"attribute";
 		$config->data_sql->usr_pref->att_value = 		"value";

?>
