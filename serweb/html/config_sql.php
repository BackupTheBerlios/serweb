<?
/*
 * $Id: config_sql.php,v 1.2 2004/04/04 19:50:54 kozlik Exp $
 */

		////////////////////////////////////////////////////////////////
		//            configure database

		/* these are the defaults with which SER installs; if you changed
		   the SER account for SQL database, you need to update here 
		*/

		$config->db_type="mysql";			//type of db host, enter "mysql" for MySQL or "pgsql" for PostgreSQL
		$config->db_host="localhost";		//database host
		$config->db_port="";				//database port - leave empty for default
		$config->db_name="ser";				//database name
		$config->db_user="ser";				//database conection user
		$config->db_pass="heslo";			//database conection password


		/* Unless you used brute-force to change SER table names */
		$config->table_subscriber="subscriber";
		$config->table_pending="pending";
		$config->table_grp="grp";
		$config->table_aliases="aliases";
		$config->table_location="location";
		$config->table_missed_calls="missed_calls";
		$config->table_accounting="acc";
		$config->table_phonebook="phonebook";
		$config->table_event="event";
		$config->table_netgeo_cache="netgeo_cache";
		$config->table_ser_mon="server_monitoring";
		$config->table_ser_mon_agg="server_monitoring_agg";
		$config->table_message_silo="silo";
		$config->table_voice_silo="voice_silo";
		$config->table_user_preferences="usr_preferences";
		$config->table_user_preferences_types="usr_preferences_types";
		$config->table_providers="providers";
		$config->table_admin_privileges="admin_privileges";
		$config->table_speed_dial="speed_dial";
		$config->table_calls_forwarding="calls_forwarding";
 
?>