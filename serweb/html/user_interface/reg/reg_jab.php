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
	$sipdomain = $config->default_domain;

	# -----
	# check if is already registered with Jabber gateway
	# -----
	$sipuri = "sip:".$sipname."@".$sipdomain;
	$dblink = mysql_connect($config->jab_db_srv, $config->jab_db_usr, $config->jab_db_pas);
	if(!$dblink) return 1;
	$res = mysql_select_db($config->jab_db_db, $dblink);
	if(!$res) return 2;
	# ----
	$query = "SELECT jab_id FROM jusers WHERE sip_id='$sipuri'";
	$result = mysql_query($query, $dblink);
	if(!$result) return 3;
	if(mysql_num_rows($result) == 0)
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
			mysql_close($dblink);
			jab_disconnect($fd);
			return 5;
		}
		# -----
		# Add user in database
		# -----
		$query = "INSERT INTO jusers (jab_id, jab_passwd, sip_id) VALUES ('$sipname', '$new_passwd', '$sipuri')";
		$result = mysql_query($query, $dblink);
		if(mysql_affected_rows() != 1)
		{
			mysql_close($dblink);
			jab_disconnect($fd);
			return 6;
		}
		jab_disconnect($fd);
	}
	return 0;
}
?>
