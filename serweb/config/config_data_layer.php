<?
/*
 * $Id: config_data_layer.php,v 1.1 2004/09/17 19:39:07 kozlik Exp $
 */

//		$config->data_container_type="sql";		//Type of data container 'sql' or 'ldap' - this value will be removed

		////////////////////////////////////////////////////////////////
		//            configure database

		/* these are the defaults with which SER installs; if you changed
		   the SER account for SQL database, you need to update here 
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

		/* Unless you used brute-force to change SER table names */
		$config->data_sql->table_subscriber="subscriber";
		$config->data_sql->table_pending="pending";
		$config->data_sql->table_grp="grp";
		$config->data_sql->table_aliases="aliases";
		$config->data_sql->table_uuidaliases="uuidaliases";
		$config->data_sql->table_location="location";
		$config->data_sql->table_missed_calls="missed_calls";
		$config->data_sql->table_accounting="acc";
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
		

		$config->data_layer_always_required_functions=array('check_passw_of_user',
															'get_privileges_of_user',
															'get_user_dom_from_uid');
 
?>