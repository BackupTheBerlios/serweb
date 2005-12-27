<?
/*
 * $Id: config_data_layer.php,v 1.22 2005/12/27 16:13:47 kozlik Exp $
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
		

		/**
		 *	Names of attributes used internaly by serweb
		 *	
		 *	DON'T CHANGE IF YOU DON'T KNOW WHAT YOU ARE DOING !!
		 */

		$config->attr_names = array(
								'fname'				=> 'sw_fname',			//first name of user
								'lname'				=> 'sw_lname',			//last name of user
								'phone'				=> 'sw_phone',			//phone of user
								'email'				=> 'sw_email',			//email address of user
								'show_status'		=> 'sw_show_status',	//show to others if user is online
								'lang'				=> 'lang',				//language
								'timezone'			=> 'sw_timezone',		//timezone

								'is_admin'			=> 'sw_is_admin',		//have admin privilege
								'is_hostmaster'		=> 'sw_is_hostmaster',	//have hostmaster privilege
								'acl_control'		=> 'sw_acl_control',	//have to be declared as multivalue, contain list of ACL entries which admin may change

								'confirmation'		=> 'sw_confirmation',	//confirmation of registration
								'pending_ts'		=> 'sw_pending_ts',		//registration timestamp - for deleting pending accounts
								'deleted_ts'		=> 'sw_deleted_ts',		//deleted timestamp

								'digest_realm'		=> 'digest_realm',		
								'admin'				=> 'sw_admin',			//have to be declared as multivalue, meaning of this attribute is: 'admin of domain'
								'dom_owner'			=> 'sw_owner',			//contain id of customer owning domain

								'sd_fname'			=> 'sw_fname',			//speed dial first name
								'sd_lname'			=> 'sw_lname',			//speed dial last name

								'domain_default_flags'		=> 'sw_domain_default_flags',		//default flags for domains
								'credential_default_flags'	=> 'sw_credential_default_flags',	//default flags for credentials
								'uri_default_flags'			=> 'sw_uri_default_flags'			//default flags for URIs
		                      );



		$config->flags = array(
							"DB_LOAD_SER"		=> 1,	// if set, attribute is mean for SER
							"DB_DISABLED"		=> 2,   // if set, attribute is disabled
							"DB_CANON"			=> 4,	// canonical domain name (domain table)
							"DB_IS_TO"			=> 8,   // URI may be used in r-uri
							"DB_IS_FROM"		=> 16,  // URI may be used in from
							"DB_FOR_SERWEB"		=> 32,  // if set, attribute is mean for SERWEB
							"DB_PENDING"		=> 64,  // account is pending for confirmation
							"DB_DELETED"		=> 128,	// row is marked as deleted
							"DB_CALLER_DELETED"	=> 256,	// row is marked as deleted
							"DB_CALLEE_DELETED"	=> 512	// row is marked as deleted
		                  );



		////////////////////////////////////////////////////////////////
		//            Definitions of SQL tables
		//
		//	DON'T CHANGE IF YOU DON'T KNOW WHAT YOU ARE DOING !!
		//
		
		/* Unless you used brute-force to change SER table names */
		$config->data_sql->table_subscriber="subscriber";
		$config->data_sql->table_grp="grp";
		$config->data_sql->table_aliases="aliases";
		$config->data_sql->table_uuidaliases="uuidaliases";
		$config->data_sql->table_location="location";
		$config->data_sql->table_missed_calls="missed_calls";
		$config->data_sql->table_cdr="cdr";
		$config->data_sql->table_phonebook="phonebook";
		$config->data_sql->table_netgeo_cache="netgeo_cache";
		$config->data_sql->table_ser_mon="server_monitoring";
		$config->data_sql->table_ser_mon_agg="server_monitoring_agg";
		$config->data_sql->table_voice_silo="voice_silo";
		$config->data_sql->table_user_preferences="usr_preferences";
		$config->data_sql->table_user_preferences_types="usr_preferences_types";
		$config->data_sql->table_providers="providers";
		$config->data_sql->table_calls_forwarding="calls_forwarding";
		$config->data_sql->table_whitelist="whitelist";


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
		 *	Definition of table domain
		 */															
		$config->data_sql->domain = new stdClass();															
 		$config->data_sql->domain->cols = new stdClass();
		
		$config->data_sql->domain->table_name = 		"domain";
 		$config->data_sql->domain->cols->did = 			"did";
 		$config->data_sql->domain->cols->name = 		"domain";
 		$config->data_sql->domain->cols->flags = 		"flags";

 		$config->data_sql->domain->flag_values = 		&$config->flags;


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
		 *	Definition of table attr_types
		 */
		 
		$config->data_sql->attr_types = new stdClass();
 		$config->data_sql->attr_types->cols = new stdClass();
		
		$config->data_sql->attr_types->table_name = 		"attr_types";
		
 		$config->data_sql->attr_types->cols->name = 		"name";
 		$config->data_sql->attr_types->cols->rich_type = 	"rich_type";
 		$config->data_sql->attr_types->cols->raw_type = 	"raw_type";
 		$config->data_sql->attr_types->cols->type_spec = 	"type_spec";
 		$config->data_sql->attr_types->cols->desc = 		"description";
 		$config->data_sql->attr_types->cols->default_flags = 	"default_flags";
 		$config->data_sql->attr_types->cols->flags = 		"flags";
 		$config->data_sql->attr_types->cols->priority = 	"priority";
 		$config->data_sql->attr_types->cols->order = 		"ordering";

 		$config->data_sql->attr_types->flag_values = 		array(
		                                                      "DB_MULTIVALUE" => 1, 
		                                                      "DB_FILL_ON_REG" =>   1 << 1 
		                                                    );
		
 		$config->data_sql->attr_types->priority_values = 	array(
		                                                       "USER" =>   1 << 8,
		                                                       "DOMAIN" => 1 << 16,
		                                                       "GLOBAL" => 1 << 30 
		                                                    );

		/*
		 *	Definition of table acc
		 */

		$config->data_sql->acc = new stdClass();
 		$config->data_sql->acc->cols = new stdClass();
		
		$config->data_sql->acc->table_name = 				"acc";
 		$config->data_sql->acc->cols->request_timestamp = 	"request_timestamp";
 		$config->data_sql->acc->cols->response_timestamp =	"response_timestamp";
 		$config->data_sql->acc->flag_values = 				&$config->flags;
		
		/*
		 *	Definition of table missed_calls
		 */

		$config->data_sql->missed_calls = new stdClass();
		
		$config->data_sql->missed_calls->table_name = 	"missed_calls";
 		$config->data_sql->missed_calls->flag_values = 	&$config->flags;


		/*
		 *	Definition of table speed dial
		 */															
		$config->data_sql->speed_dial = new stdClass();															
 		$config->data_sql->speed_dial->cols = new stdClass();
		
		$config->data_sql->speed_dial->table_name = 		"speed_dial";
 		$config->data_sql->speed_dial->cols->id = 			"id";
 		$config->data_sql->speed_dial->cols->uid = 			"uid";
 		$config->data_sql->speed_dial->cols->dial_username= "dial_username";
 		$config->data_sql->speed_dial->cols->dial_did = 	"dial_did";
 		$config->data_sql->speed_dial->cols->new_uri = 		"new_uri";

		/*
		 *	Definition of table sd_attrs
		 */															
		$config->data_sql->sd_attrs = new stdClass();															
 		$config->data_sql->sd_attrs->cols = new stdClass();
		
		$config->data_sql->sd_attrs->table_name = 		"sd_attrs";
 		$config->data_sql->sd_attrs->cols->id = 		"id";
 		$config->data_sql->sd_attrs->cols->name = 		"name";
 		$config->data_sql->sd_attrs->cols->value = 		"value";
 		$config->data_sql->sd_attrs->cols->type = 		"type";
 		$config->data_sql->sd_attrs->cols->flags = 		"flags";

 		$config->data_sql->sd_attrs->flag_values = 		&$config->flags;


		/*
		 *	Definition of table uri
		 */															
		$config->data_sql->uri = new stdClass();															
 		$config->data_sql->uri->cols = new stdClass();
		
		$config->data_sql->uri->table_name = 		"uri";
 		$config->data_sql->uri->cols->uid = 		"uid";
 		$config->data_sql->uri->cols->did = 		"did";
 		$config->data_sql->uri->cols->username = 	"username";
 		$config->data_sql->uri->cols->flags = 		"flags";

 		$config->data_sql->uri->flag_values = 		&$config->flags;


		/*
		 *	Definition of table location
		 */															
		$config->data_sql->location = new stdClass();															
 		$config->data_sql->location->cols = new stdClass();
		
		$config->data_sql->location->table_name = 		"location";
 		$config->data_sql->location->cols->uid = 		"uid";
 		$config->data_sql->location->cols->contact = 	"contact";
 		$config->data_sql->location->cols->flags = 		"flags";

 		$config->data_sql->location->flag_values = 		&$config->flags;


		/*
		 *	Definition of table silo
		 */															
		$config->data_sql->msg_silo = new stdClass();															
 		$config->data_sql->msg_silo->cols = new stdClass();
		
		$config->data_sql->msg_silo->table_name = 		"silo";
 		$config->data_sql->msg_silo->cols->mid = 		"mid";
 		$config->data_sql->msg_silo->cols->uid = 		"uid";
 		$config->data_sql->msg_silo->cols->from = 		"from_hdr";
 		$config->data_sql->msg_silo->cols->to = 		"to_hdr";
 		$config->data_sql->msg_silo->cols->ruri = 		"ruri";
 		$config->data_sql->msg_silo->cols->inc_time = 	"inc_time";
 		$config->data_sql->msg_silo->cols->exp_time = 	"exp_time";
 		$config->data_sql->msg_silo->cols->ctype = 		"ctype";
 		$config->data_sql->msg_silo->cols->body = 		"body";


		/*
		 *  names of columns in table customer
		 */															
		$config->data_sql->customers = new stdClass();															
 		$config->data_sql->customers->cols = new stdClass();
		
		$config->data_sql->customers->table_name = 		"customers";
 		$config->data_sql->customers->cols->cid = 		"cid";
 		$config->data_sql->customers->cols->name = 		"name";
 		$config->data_sql->customers->cols->address = 	"address";
 		$config->data_sql->customers->cols->phone = 	"phone";
 		$config->data_sql->customers->cols->email = 	"email";





		$config->data_layer_always_required_functions=array('set_db_charset',
															'set_db_collation');


		/* next line specify how are users keyed. May contains values: 'uuid' (for keying by uuid) 
		   or 'username' (for keyiing by username and domain). The right value depends on your
		   database schema.
		   
		   Notice: indexing by username is DEPRECATED and WILL NOT WORK
		*/
		$config->users_indexed_by= "uuid";


?>
