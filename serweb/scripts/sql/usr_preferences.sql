#
# $Id: usr_preferences.sql,v 1.2 2005/06/02 11:28:12 kozlik Exp $
#
# Dumping data for table 'usr_preferences_types'
#

INSERT INTO usr_preferences_types (att_name, att_rich_type, att_raw_type, att_type_spec, default_value) 
VALUES("fw_voicemail", "boolean", "1", NULL, "0");

INSERT INTO usr_preferences_types (att_name, att_rich_type, att_raw_type, att_type_spec, default_value) 
VALUES("sw_user_status_visible", "boolean", "1", NULL, "1");

INSERT INTO usr_preferences_types (att_name, att_rich_type, att_raw_type, att_type_spec, default_value) 
VALUES("send_daily_missed_calls", "boolean", "1", NULL, "0");
