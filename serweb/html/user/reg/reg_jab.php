<?php
# *** ---------------------------------------- ***
# IM Gateway subscription
# contact daniel for anything related to it
# *** ---------------------------------------- ***
#
include ("libjab.php");
function reg_jab($sipname)
{
	global $config;

	$jcid=$config->jcid;
	$sipdomain = $config->domain;

	# -----
	# check if is already registered with Jabber gateway
	# -----
	$sipuri = "sip:".$sipname."@".$sipdomain;

	$dsn = 	$config->jab_db_type."://".
			$config->jab_db_usr.":".
			$config->jab_db_pas."@".
			$config->jab_db_srv.
				(empty($config->jab_db_port)?
					"":
					":".$config->jab_db_port)."/".
			$config->jab_db_db;

	$db = DB::connect($dsn);
	if (DB::isError($db)) {	log_errors($db, $dummy); return 1; }

	# ----
	$query = "SELECT jab_id FROM jusers WHERE sip_id='$sipuri'";
	$result = $db->query($query);
	if (DB::isError($result)) {log_errors($result, $dummy); return 3;}

	if($result->numRows() == 0)
	{ // no Jabber account - create one
		$fd = jab_connect($config->jserver, $config->jport);
		if(!$fd)
			return 4;
		$buf_recv = fread($fd, 2048);
		while(!$buf_recv)
		{
			usleep(100);
			$buf_recv = fread($fd, 2048);
		}
		$jcid = $jcid + 1;
		jab_get_reg($fd, $jcid, $config->jserver);
		$buf_recv = fread($fd, 2048);
		while(!$buf_recv)
		{
			usleep(100);
			$buf_recv = fread($fd, 2048);
		}
		$jcid = $jcid + 1;
		$new_passwd = "qw1".$sipname."#";
		jab_set_reg($fd, $jcid, $config->jserver, $sipname, $new_passwd);
		$buf_recv = fread($fd, 2048);
		while(!$buf_recv)
		{
			usleep(100);
			$buf_recv = fread($fd, 2048);
		}
		if(stristr($buf_recv, " id='$jcid'") && stristr($buf_recv, " type='error'"))
		{
			$db->disconnect();
			jab_disconnect($fd);
			return 5;
		}
		# -----
		# Add user in database
		# -----
		$query = "INSERT INTO jusers (jab_id, jab_passwd, sip_id) VALUES ('$sipname', '$new_passwd', '$sipuri')";
		$result = $db->query($query);

		if($db->affectedRows() != 1)
		{
			$db->disconnect();
			jab_disconnect($fd);
			return 6;
		}
		jab_disconnect($fd);
	}
	return 0;
}
?>
