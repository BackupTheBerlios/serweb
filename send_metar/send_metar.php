<?
/*
 * $Id: send_metar.php,v 1.2 2003/03/17 20:03:52 kozlik Exp $
 */

//disable time limit
set_time_limit(0);

if (!$serweb_config) {
	echo "Not specified serweb_config parameter\n";
	echo "usage: send_metar.php?serweb_config=path/to/serweb/config/file/config.php\n";
	exit (1);
}

$serweb_dir=dirname($serweb_config);

require ($serweb_config);
require ("phpweather/phpweather.php");
require ("phpweather/output/pw_text_en.php");
require ($serweb_dir."/functions.php");

$db = connect_to_db();
if (!$db){ echo "can´t connect to sql server"; exit (1);}

$reg=new Creg();

//create phpweather objects
$weather = new phpweather();

$language="en";
$text_type = 'pw_text_'.$language;


//get users which have subscribed this service
$q="select username from ".$config->table_event." where uri='".$config->metar_event_uri."'";
$res=mySQL_query($q);
if (!$res) {echo "error in SQL query, line: ".__LINE__."\n"; exit (1);}

while ($row=MySQL_Fetch_Object($res)){

	//for each user get contacts
	$q="select contact from ".$config->table_location." where username='".$row->username."'";
	$res2=mySQL_query($q);
	if (!$res2) {echo "error in SQL query, line: ".__LINE__."\n"; continue;}

	$send_na=true;
	unset($user_icao_cache);
	$user_icao_cache=array();

	//for each contact get meter data and send it
	while ($row2=MySQL_Fetch_Object($res2)){

		$domain=$reg->get_domainname($row2->contact);

		//get icao for domain
		$q="select icao from ".$config->table_netgeo_cache." where domainname='$domain'";
		$res3=MySQL_query($q);
		if (!$res3) {echo "error in SQL query, line: ".__LINE__."\n"; continue;}
		$row3=MySQL_Fetch_Object($res3);

		if(!$row3) continue;
		$icao=$row3->icao; // if domain isn't in cache, continue with next contact

		if (!$icao) continue; // if icao for this domain isn't in cache, continue with next contact

		if (in_array($icao, $user_icao_cache)) continue; //metar data already sended from this icao
		$user_icao_cache[]=$icao;

		$weather->set_icao($icao);
		if (!$weather->get_metar()) continue;	//there is no avaiable metar data

		$text = new $text_type($weather);
		$message=$text->print_pretty();

		//delete html tags from phpweather otuput
		$message=ereg_Replace("<[^>]*>","",$message);

		//replace special chars in phpweather otuput
		$message=str_Replace("&nbsp;"," ",$message);
		$message=str_Replace("&deg;","°",$message);

		$send_na=false; //successfully get data for user, not to need send n/a message

		/* construct FIFO command */
		$fifo_cmd=":t_uac_from:".$config->reply_fifo_filename."\n".
		    "MESSAGE\n".
			$config->metar_from_sip_uri."\n".
			"sip:".$row->username."@".$config->default_domain."\n".
		    "p-version: ".$config->psignature."\n".
		    "Contact: ".$config->web_contact."\n".
		    "Content-Type: text/plain; charset=UTF-8\n\n".
		    str_Replace("\n.\n","\n. \n",$message)."\n.\n\n";

		write2fifo($fifo_cmd, $errors, $status);
		if ($errors) {
			foreach ($errors as $err) echo $err."\n";
			unset ($errors);
			continue;
		}
		/* we accept any status code beginning with 2 as ok */
		if (substr($status,0,1)!="2") {echo $status."\n"; continue; }

	}

	if ($send_na){ //no data is accesible for this user, send n/a message
		$fifo_cmd=":t_uac_from:".$config->reply_fifo_filename."\n".
		    "MESSAGE\n".
			$config->metar_from_sip_uri."\n".
			"sip:".$row->username."@".$config->default_domain."\n".
		    "p-version: ".$config->psignature."\n".
		    "Contact: ".$config->web_contact."\n".
		    "Content-Type: text/plain; charset=UTF-8\n\n".
		    $config->metar_na_message."\n.\n\n";

		write2fifo($fifo_cmd, $errors, $status);
		if ($errors) {
			foreach ($errors as $err) echo $err."\n";
			unset ($errors);
			continue;
		}
		/* we accept any status code beginning with 2 as ok */
		if (substr($status,0,1)!="2") {echo $status."\n"; continue; }
	}

}


?>
