<?
/*
 * $Id: config_data_layer.php,v 1.59 2012/08/29 16:06:42 kozlik Exp $
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
		$config->use_rpc = true;

		/**
		 * These are options for connect to XML-RPC interface of SER
		 */

		/**
		 * The hostname for xml-rpc requests 
		 */
		$config->ser_rpc['host']		= "localhost";

		/**
		 * The port for xml-rpc requests. 5060 is used by default
		 */
#CFGOPTION_RPC_PORT
		//$config->ser_rpc['port']		= 5060;
#ENDCFGOPTION
		/**
		 * Username for xml-rpc authentication (if required)
		 */
		//$config->ser_rpc['user']		= "xmlrpc";

		/**
		 * Password for xml-rpc authentication (if required) 
		 */
		//$config->ser_rpc['pass']		= "heslo";

		/**
		 * Use encrypted connections for xml-rpc requests 
		 */
		//$config->ser_rpc['use_ssl']		= true;

		/**
		 * Verify host - check if the common name in SSL peer 
		 * certificate matches the hostname provided 
		 * Is true by default
		 */
		//$config->ser_rpc['ssl_vh']		= true;

		/**
		 * The SSL version to use. By default PHP will try 
		 * to determine this itself.
		 */
		//$config->ser_rpc['ssl_ver']		= 1;

		/**
		 * The name of a file holding one or more PEM formated certificates 
		 * to verify the peer with.
		 */
		//$config->ser_rpc['ssl_ca']		= "/etc/serweb/ca.crt";

		/**
		 * The name of a file containing a PEM formatted client certificate. 
		 */
		//$config->ser_rpc['ssl_cert']	= "/etc/serweb/serweb.crt";

		/**
		 * The secret password needed to use the client certificate
		 */
		//$config->ser_rpc['ssl_cert_pass']	= "abrakadabra";

		/**
		 * The name of a file containing a PEM formatted private SSL key. 
		 */
		//$config->ser_rpc['ssl_key']		= "/etc/serweb/serweb.key";

		/**
		 * The secret password needed to use the private SSL key 
		 */
		//$config->ser_rpc['ssl_key_pass']	= "abrakadabra";
		


		/**
		 *	Following array contain list of all sip proxies. Uncoment this if
		 *	there are more sip proxies in your environment. This list is used 
		 *	for xml-rpc comunication.
		 *	
		 *	Each array may contains the same keys as $config->ser_rpc. Only the
		 *	'host' key is required. If some keys are not specified in some 
		 *	array, the default values from $config->ser_rpc are used.
		 */
#CFGOPTION_PROXIES
/*
		$config->sip_proxies[] = array('host'=>'proxy1.mydomain.org');
		$config->sip_proxies[] = array('host'=>'proxy2.mydomain.org');
		$config->sip_proxies[] = array('host'=>'proxy3.mydomain.org');
		$config->sip_proxies[] = array('host'=>'proxy4.mydomain.org');
		$config->sip_proxies[] = array('host'=>'proxy5.mydomain.org');
*/
#ENDCFGOPTION

		/**
		 *	Obtain setting of database from SER - *** EXPERIMENTAL ***
		 *	To enable this option must be $config->use_rpc = true
		 */

		$config->get_db_uri_from_ser = false;

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
#CFGOPTION_DB_HOST
		$config->data_sql->host[$i]['host']="localhost";	//database host
#ENDCFGOPTION

#CFGOPTION_DB_PORT
		$config->data_sql->host[$i]['port']="";				//database port - leave empty for default
#ENDCFGOPTION

#CFGOPTION_DB_NAME
		$config->data_sql->host[$i]['name']="ser";			//database name
#ENDCFGOPTION

#CFGOPTION_DB_USER
		$config->data_sql->host[$i]['user']="ser";			//database conection user
#ENDCFGOPTION

#CFGOPTION_DB_PASS
		$config->data_sql->host[$i]['pass']="heslo";		//database conection password
#ENDCFGOPTION

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
		 *	
		 *	If $config->keep_acc_interval is 0, accounting records are not 
		 *	deleted.
		 */
		$config->keep_acc_interval = 0;
		
		/**
		 *	Number of kept versions of one file
		 *	This variable tells how many versions of one file (from directory 
		 *	with domain specific config) is stored. If is set to zero files
		 *	are not backuped on update of them.
		 */
		$config->backup_versions_nr = 10;
		
		/**
		 *	Set to true if SER caching domain table
		 *
		 *	modparam("domain", "db_mode", 1) is set in ser.cfg
		 *	
		 *	Otherwise set this option to false
		 */
		$config->ser_domain_cache = true;

        /**
         *  When data in domain table are changed, all sip proxies should be notified 
         *  about it to reload the data. There are two methods how to do it:
         *   
         *   - serweb could notify ser itself, by it's management interface.
         *     It have the disadvantage that serweb have to know about all sip 
         *     proxies in the setup. When a proxy is not accessible during the notify
         *     it is not notified and still useing the old data.
         *     
         *     List of sip proxies shold be set by config variable:
         *     $config->sip_proxies
         *     
         *   - all sip proxies periodically checking value of global AVP 
         *     'domain_data_version' and if the value is changed, they reload the 
         *     domain data
         *
         *   Set following option to true to enable notifing of sip proxies by serweb
         */
        $config->domain_reload_ser_notify = false;

        /**
         *  When data in global attrs are changed, all sip proxies should be notified 
         *  about it to reload the data. There are two methods how to do it:
         *   
         *   - serweb could notify ser itself, by it's management interface.
         *     It have the disadvantage that serweb have to know about all sip 
         *     proxies in the setup. When a proxy is not accessible during the notify
         *     it is not notified and still useing the old data.
         *     
         *     List of sip proxies shold be set by config variable:
         *     $config->sip_proxies
         *     
         *   - all sip proxies periodically checking value of global AVP 
         *     'gattr_timestamp' and if the value is changed, they reload the 
         *     global attrs
         *
         *   Set following option to true to enable notifing of sip proxies by serweb
         */
        $config->g_attrs_reload_ser_notify = false;

		/**
		 *	Set to false if SER useing did column of credentials table
		 *
		 *	modparam("auth_db", "use_did", 0) is set in ser.cfg
		 *	
		 *	Otherwise set this option to true
		 *
		 *  Please note that it IS NOT RECOMENDED to change this value when 
		 *  your credentials table is already populated with data.
		 */
		$config->auth['use_did'] = true;

		
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
         *  Names of attributes used internaly by serweb
         *  
         *  DON'T CHANGE IF YOU DON'T KNOW WHAT YOU ARE DOING !!
         */

        $config->attr_names = array(
                                'fname'             => 'first_name',        //first name of user
                                'lname'             => 'last_name',         //last name of user
                                'phone'             => 'phone',             //phone of user
                                'email'             => 'email',             //email address of user
                                'show_status'       => 'sw_show_status',    //show to others if user is online
                                'lang'              => 'lang',              //language
                                'timezone'          => 'timezone',          //timezone
                                'allow_find'        => 'sw_allow_find',     //allow other to look up for this user
                                'send_mc'           => 'sw_send_missed',    //send missed calls
                                'acl'               => 'acl',               //contain access control list of user - have to be declared as multivalue

                                'is_admin'          => 'sw_is_admin',       //have admin privilege
                                'is_hostmaster'     => 'sw_is_hostmaster',  //have hostmaster privilege
                                'acl_control'       => 'sw_acl_control',    //have to be declared as multivalue, contain list of ACL entries which admin may change
                                'highest_alias_number' => 'sw_highest_alias_number',    //highest assigned alias number
                                'max_uri_user'      => 'sw_max_uri_user',   //maximum number of uris per user

                                'confirmation'      => 'sw_confirmation',   //confirmation of registration
                                'uname_asign_mode'  => 'sw_uname_assign_mode', //mode of username assignment on registration
                                'pending_ts'        => 'sw_pending_ts',     //registration timestamp - for deleting pending accounts
                                'deleted_ts'        => 'sw_deleted_ts',     //deleted timestamp
                                'datetime_created'  => 'datetime_created',  //time of user creation
                                'require_conf'      => 'sw_require_confirm',//require confirmation

                                'digest_realm'      => 'digest_realm',      
                                'contact_email'     => 'contact_email',     //email address used in mail header from when serweb sending email
                                'admin'             => 'sw_admin',          //have to be declared as multivalue, meaning of this attribute is: 'admin of domain'
                                'dom_owner'         => 'sw_owner',          //contain id of customer owning domain

                                'uid_format'        => 'uid_format',        //format of newly created UIDs
                                'did_format'        => 'did_format',        //format of newly created DIDs

                                'sd_fname'          => 'first_name',        //speed dial first name
                                'sd_lname'          => 'last_name',         //speed dial last name

                                'domain_default_flags'      => 'sw_domain_default_flags',       //default flags for domains
                                'credential_default_flags'  => 'sw_credential_default_flags',   //default flags for credentials
                                'uri_default_flags'         => 'sw_uri_default_flags',          //default flags for URIs

                                'domain_data_version'       => 'domain_data_version',           //version of data in domain table
                                'gattr_timestamp'           => 'gattr_timestamp'                //version of data in global AVPs
                              );



		$config->flags = array(
							"DB_LOAD_SER"		=> 1,    	// if set, attribute is mean for SER
							"DB_DISABLED"		=> 1 << 1,  // if set, attribute is disabled
							"DB_CANON"			=> 1 << 2,	// canonical domain name (domain table)
							"DB_IS_TO"			=> 1 << 3,  // URI may be used in r-uri
							"DB_IS_FROM"		=> 1 << 4,  // URI may be used in from
							"DB_FOR_SERWEB"		=> 1 << 5,  // if set, attribute is mean for SERWEB
							"DB_PENDING"		=> 1 << 6,  // not used
							"DB_DELETED"		=> 1 << 7,	// row is marked as deleted
							"DB_CALLER_DELETED"	=> 1 << 8,	// row is marked as deleted
							"DB_CALLEE_DELETED"	=> 1 << 9,	// row is marked as deleted
							"DB_MULTIVALUE"     => 1 << 10, // attr_types
							"DB_FILL_ON_REG"    => 1 << 11, // attr_types 
							"DB_REQUIRED"       => 1 << 12, // attr_types
							"DB_DIR" 		    => 1 << 13  // domain_settings
		                  );



		////////////////////////////////////////////////////////////////
		//            Definitions of SQL tables
		//
		//	DON'T CHANGE IF YOU DON'T KNOW WHAT YOU ARE DOING !!
		//
		
		/* Unless you used brute-force to change SER table names */
		$config->data_sql->table_aliases="aliases";
		$config->data_sql->table_location="location";
		$config->data_sql->table_missed_calls="missed_calls";
		$config->data_sql->table_cdr="cdr";
		$config->data_sql->table_phonebook="phonebook";
		$config->data_sql->table_netgeo_cache="netgeo_cache";
		$config->data_sql->table_voice_silo="voice_silo";
		$config->data_sql->table_whitelist="whitelist";


		/*
		 *	Definition of table credentials
		 */
		 
		$config->data_sql->table_credentials="credentials";

		$config->data_sql->credentials = new stdClass();
 		$config->data_sql->credentials->cols = new stdClass();
		
		$config->data_sql->credentials->table_name = 		"credentials";
		
 		$config->data_sql->credentials->cols->uname = 		"auth_username";
 		$config->data_sql->credentials->cols->did = 		"did";
 		$config->data_sql->credentials->cols->realm = 		"realm";
 		$config->data_sql->credentials->cols->password = 	"password";
 		$config->data_sql->credentials->cols->flags = 		"flags";
 		$config->data_sql->credentials->cols->ha1 = 		"ha1";
 		$config->data_sql->credentials->cols->ha1b = 		"ha1b";
 		$config->data_sql->credentials->cols->uid = 		"uid";

 		$config->data_sql->credentials->flag_values = &$config->flags;
		$config->data_sql->credentials->version = 			7;



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
		$config->data_sql->domain->version = 			2;


		/*
		 *	Definition of table uri_attrs
		 */
		 
		$config->data_sql->uri_attrs = new stdClass();
 		$config->data_sql->uri_attrs->cols = new stdClass();
		
		$config->data_sql->uri_attrs->table_name = 		"uri_attrs";
		
 		$config->data_sql->uri_attrs->cols->scheme = 	"scheme";
 		$config->data_sql->uri_attrs->cols->username = 	"username";
 		$config->data_sql->uri_attrs->cols->did = 		"did";
 		$config->data_sql->uri_attrs->cols->name = 		"name";
 		$config->data_sql->uri_attrs->cols->value = 	"value";
 		$config->data_sql->uri_attrs->cols->type = 		"type";
 		$config->data_sql->uri_attrs->cols->flags = 	"flags";

 		$config->data_sql->uri_attrs->flag_values = &$config->flags;
		$config->data_sql->uri_attrs->version = 		2;


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
		$config->data_sql->user_attrs->version = 			3;



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
		$config->data_sql->domain_attrs->version = 			1;



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
		$config->data_sql->global_attrs->version = 			1;


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
 		$config->data_sql->attr_types->cols->access = 		"access";
 		$config->data_sql->attr_types->cols->order = 		"ordering";
 		$config->data_sql->attr_types->cols->group = 		"grp";

 		$config->data_sql->attr_types->flag_values = 		&$config->flags;
		$config->data_sql->attr_types->version = 			4;

		
 		$config->data_sql->attr_types->priority_values = 	array(
		                                                       "URI" =>    1 << 4,
		                                                       "USER" =>   1 << 8,
		                                                       "DOMAIN" => 1 << 16,
		                                                       "GLOBAL" => 1 << 30 
		                                                    );

 		$config->data_sql->attr_types->groups = 	array(
               "general" => array("order" => 1,  "label" => "@attr_grp_general"),
               "privacy" => array("order" => 5,  "label" => "@attr_grp_privacy"),
               "other" =>   array("order" => 80, "label" => "@attr_grp_other") 
                                                    );

		/*
		 *	Definition of table acc
		 */

		$config->data_sql->acc = new stdClass();
 		$config->data_sql->acc->cols = new stdClass();
		
		$config->data_sql->acc->table_name = 				"acc";
 		$config->data_sql->acc->cols->request_timestamp = 	"request_timestamp";
 		$config->data_sql->acc->cols->response_timestamp =	"response_timestamp";
 		$config->data_sql->acc->flag_values = 				
                array(
                    "FLAG_ACC"            => 1 << 1,  // this request will be recorded by ACC
                    "FLAG_FAILUREROUTE"   => 1 << 2,  // we are operating from the failure route
                    "FLAG_NAT"            => 1 << 3,  // the UAC is behind a NAT
                    "FLAG_PEER_REPLICATE" => 1 << 4,  // the request came from a replication peer node
                    "FLAG_TOTAG"          => 1 << 5,
                    "FLAG_PSTN_ALLOWED"   => 1 << 6,  // the user is allowed to use the PSTN
                    "FLAG_DONT_RM_CRED"   => 1 << 7,  // do not remove the credentials
                    "FLAG_AUTH_OK"        => 1 << 8,
                    "DB_CALLER_DELETED"   => 1 << 9,  // row is marked as deleted
                    "DB_CALLEE_DELETED"   => 1 << 10  // row is marked as deleted
                );


		$config->data_sql->acc->version = 					4;
		
		/*
		 *	Definition of table missed_calls
		 */

		$config->data_sql->missed_calls = new stdClass();
		
		$config->data_sql->missed_calls->table_name = 	"missed_calls";
 		$config->data_sql->missed_calls->flag_values = 	&$config->data_sql->acc->flag_values;

		$config->data_sql->missed_calls->version = 		4;


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

		$config->data_sql->speed_dial->version = 			2;

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
		$config->data_sql->sd_attrs->version = 			1;


		/*
		 *	Definition of table uri
		 */															
		$config->data_sql->uri = new stdClass();															
 		$config->data_sql->uri->cols = new stdClass();
		
		$config->data_sql->uri->table_name = 		"uri";
 		$config->data_sql->uri->cols->scheme = 		"scheme";
 		$config->data_sql->uri->cols->uid = 		"uid";
 		$config->data_sql->uri->cols->did = 		"did";
 		$config->data_sql->uri->cols->username = 	"username";
 		$config->data_sql->uri->cols->flags = 		"flags";

 		$config->data_sql->uri->flag_values = 		&$config->flags;
		$config->data_sql->uri->version = 			3;


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
		$config->data_sql->location->version = 			9;


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

		$config->data_sql->msg_silo->version = 			4;


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

		$config->data_sql->customers->version = 		1;


		/*
		 *  names of columns in table domain_settings
		 */															
		$config->data_sql->domain_settings = new stdClass();															
 		$config->data_sql->domain_settings->cols = new stdClass();
		
		$config->data_sql->domain_settings->table_name = 		"domain_settings";
 		$config->data_sql->domain_settings->cols->did = 		"did";
 		$config->data_sql->domain_settings->cols->filename = 	"filename";
 		$config->data_sql->domain_settings->cols->version = 	"version";
 		$config->data_sql->domain_settings->cols->timestamp = 	"timestamp";
 		$config->data_sql->domain_settings->cols->content = 	"content";
 		$config->data_sql->domain_settings->cols->flags = 		"flags";

 		$config->data_sql->domain_settings->flag_values = 	&$config->flags;
		$config->data_sql->domain_settings->version = 			1;



		$config->data_layer_always_required_functions=array('set_db_charset',
															'set_db_collation');


		/* next line specify how are users keyed. May contains values: 'uuid' (for keying by uuid) 
		   or 'username' (for keyiing by username and domain). The right value depends on your
		   database schema.
		   
		   Notice: indexing by username is DEPRECATED and WILL NOT WORK
		*/
		$config->users_indexed_by= "uuid";


?>
