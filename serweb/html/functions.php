<?
/*
 * $Id: functions.php,v 1.20 2003/05/26 23:00:34 jiri Exp $
 */


$reg_validate_email="^[^@]+@[^.]+\.[^,;@ ]+$";

$reg_validate_username="^((8[0-9]*)|([a-zA-Z][a-zA-Z0-9.]*))$";

class Creg{

	var $alphanum;
	var $mark;
	var $unreserved;
	var $escaped;
	var $user_unreserved;
	var $user;

	var $port;
	var $hex4;
	var $hexseq;
	var $hexpart;
	var $ipv4address;
	var $ipv6address;
	var $ipv6reference;

	var $toplabel;
	var $domainlabel;
	var $hostname;
	var $host;

	var $token;
	var $param_unreserved;
	var $paramchar;
	var $pname;
	var $pvalue;
	var $uri_parameter;
	var $uri_parameters;

	var $address;
	var $sip_address;

	function Creg(){
		$this->alphanum="[a-zA-Z0-9]";
		$this->mark="[-_.!~*'()]";
		$this->unreserved="(".$this->alphanum."|".$this->mark.")";
		$this->escaped="(%[0-9a-fA-F][0-9a-fA-F])";
		$this->user_unreserved="[&=+$,;?/]";
		$this->user="(".$this->unreserved."|".$this->escaped."|".$this->user_unreserved.")+";

		$this->port="[0-9]+";
		$this->hex4="([0-9a-fA-F]{1,4})";
		$this->hexseq="(".$this->hex4."(:".$this->hex4.")*)";
		$this->hexpart="(".$this->hexseq."|(".$this->hexseq."::".$this->hexseq."?)|(::".$this->hexseq."?))";
		$this->ipv4address="([0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3})";
		$this->ipv6address="(".$this->hexpart."(:".$this->ipv4address.")?)";
		$this->ipv6reference="(\\[".$this->ipv6address."])";

		$this->toplabel="([a-zA-Z]|([a-zA-Z](~|".$this->alphanum.")*".$this->alphanum."))";
		$this->domainlabel="(".$this->alphanum."|(".$this->alphanum."(~|".$this->alphanum.")*".$this->alphanum."))";
		$this->hostname="((".$this->domainlabel."\\.)*".$this->toplabel."(\\.)?)";
		$this->host="(".$this->hostname."|".$this->ipv4address."|".$this->ipv6reference.")";

		$this->token="(([-.!%*_+`'~]|".$this->alphanum.")+)";
		$this->param_unreserved="[\\][/:&+$]";
		$this->paramchar="(".$this->param_unreserved."|".$this->unreserved."|".$this->escaped.")";
		$this->pname="((".$this->paramchar.")+)";
		$this->pvalue="((".$this->paramchar.")+)";
		$this->uri_parameter="(".$this->pname."(=".$this->pvalue.")?)";
		$this->uri_parameters="((;".$this->uri_parameter.")*)";

		$this->address="(".$this->user."@)?".$this->host."(:".$this->port.")?".$this->uri_parameters;
		$this->sip_address="[sS][iI][pP]:".$this->address;
	}

	function get_domainname($sip){
		return ereg_replace($this->sip_address,"\\5", $sip);
	}

}


function print_errors($errors){
	if (is_Array($errors))
		foreach ($errors as $val) echo "<div class=\"errors\">".$val."</div>\n";
}

function print_message($msg){
	if ($msg) echo "<div class=\"message\">".$msg."</div>\n";
}

function get_sess_url($url){
	global $sess;

	if ($sess) return $sess->url($url);
	else return $url;

}

function print_search_links($actual, $num, $rows, $url){
// print bar for search
// actual - from each item it is printed
// num - how many items total
// rows - how many items on one page
// url - href in <a> takgs

	$links=10; //max num of links at one page

	if ($num<=$rows) return;

	$lfrom=$actual-($links*$rows); if ($lfrom<0) $lfrom=0;
	$lto=$actual+($links*$rows); if ($lto>$num) $lto=$num;

	if ($actual>0) echo '<a href="'.get_sess_url($url.((($actual-$rows)>0)?($actual-$rows):0)).'">&lt;&lt;&lt;</a>&nbsp;';
	for ($i=$lfrom; $i<$lto; $i+=$rows){
		if ($i<=$actual and $actual<($i+$rows)) echo '<span class="f14b">'.(floor($i/$rows)+1).'</span>&nbsp;';
		else echo '<a href="'.get_sess_url($url.$i).'">'.(floor($i/$rows)+1).'</a>&nbsp;';
	}
 	if (($actual+$rows)<$num) echo '<a href="'.get_sess_url($url.($actual+$rows)).'">&gt;&gt;&gt;</a>';

}

function connect_to_db(){
	global $config;

	@$db=MySQL_PConnect($config->db_host, $config->db_user, $config->db_pass);
	if (! $db) return false;

	if (!MySQL_Select_DB($config->db_name)) return false;
	return $db;
}

function get_status($sip_uri, &$errors){
	global $config;

	$reg=new Creg;
	if (!eregi("^sip:([^@]+@)?".$reg->host, $sip_uri, $regs)) return "<div class=\"statusunknown\">non-local</div>";

	if (strtolower($regs[2])!=strtolower($config->default_domain)) return "<div class=\"statusunknown\">non-local</div>";

	$user=substr($regs[1],0,-1);

	$q="select count(*) from ".$config->table_subscriber." where username='$user'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; return "<div class=\"statusunknown\">unknown</div>";}
	$row=mysql_fetch_row($res);
	if (!$row[0]) return "<div class=\"statusunknown\">non-existent</div>";


	$fifo_cmd=":ul_show_contact:".$config->reply_fifo_filename."\n".
	$config->ul_table."\n".		//table
	$user."\n\n";	//username

	$out=write2fifo($fifo_cmd, $errors, $status);
	if ($errors) return;

	if (substr($status,0,3)=="200") return "<div class=\"statusonline\">on line</div>";
	else return "<div class=\"statusoffline\">off line</div>";
}

function send_mail($to, $subj, $text, $headers = ""){
	global $config;

	$headers .= "From: ".$config->mail_header_from."\n";

	@$a= Mail($to, $subj, $text, $headers);
	return $a;
}

function write2fifo($fifo_cmd, &$errors, &$status){
	global $config;

	/* open fifo now */
	$fifo_handle=fopen( $config->fifo_server, "w" );
	if (!$fifo_handle) {
		$errors[]="sorry -- cannot open write fifo"; return;
	}

	/* create fifo for replies */
	@system("mkfifo -m 666 ".$config->reply_fifo_path );


	/* add command separator */
	$fifo_cmd=$fifo_cmd."\n";

	/* write fifo command */
	if (fwrite( $fifo_handle, $fifo_cmd)==-1) {
	    @unlink($config->reply_fifo_path);
	    @fclose($fifo_handle);
		$errors[]="sorry -- fifo writing error"; return;
	}
	@fclose($fifo_handle);

	/* read output now */
	@$fp = fopen( $config->reply_fifo_path, "r");
	if (!$fp) {
	    @unlink($config->reply_fifo_path);
		$errors[]="sorry -- reply fifo opening error"; return;
	}

	$status=fgetS($fp,256);
	if (!$status) {
	    @unlink($config->reply_fifo_path);
		$errors[]="sorry -- reply fifo reading error"; return;
	}
	
	$rd=fread($fp,8192);
	@unlink($config->reply_fifo_path);
	
	return $rd;
}

function get_user_name(&$errors){
	global $auth, $config;

	$q="select first_name, last_name from ".$config->table_subscriber." where username='".$auth->auth["uname"]."'";
	@$res=MySQL_Query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; return false;}
	if (!MySQL_Num_rows($res)) return false;
	
	$row=MySQL_Fetch_Object($res);
	
	$name=$row->first_name;
	if ($name) $name.=" ";
	return $name.=$row->last_name;

}

function get_time_zones(&$errors){
	global $config;

	@$fp=fopen($config->zonetab_file, "r");
	if (!$fp) {$errors[]="Can´t open zone.tab file"; return false;}
	
	while (!feof($fp)){
		$line=FgetS($fp, 512);
		if (substr($line,0,1)=="#") continue; //skip comments
		
		$line_a=explode("\t", $line);
		
		$line_a[2]=trim($line_a[2]);
		if ($line_a[2]) $out[]=$line_a[2];
	}

	fclose($fp);
	sort($out);
	return $out;
}

function set_timezone(&$errors){
	global $config, $auth;

	$q="select timezone from ".$config->table_subscriber." where username='".$auth->auth["uname"]."'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; return;}
	$row=mysql_fetch_object($res);

	putenv("TZ=".$row->timezone); //set timezone	
}

//get location of domainname in sip_adr from netgeo_cache
function get_location($sip_adr, &$errors){
	global $config;
	static $reg;
	
	$reg = new Creg();
	
	$domainname=$reg->get_domainname($sip_adr);
	
	$q="select location from ".$config->table_netgeo_cache.
		" where domainname='".$domainname."'";
	$res=mySQL_query($q);
	/* if this query failed netgeo is probably not installed -- ignore */
	if (!$res) return "n/a";
	/* {$errors[]="error in SQL query, line: ".__LINE__; return;} */
	$row=mysql_fetch_object($res);

	if (!$row) return "n/a";
	return $row->location;
}

function filter_fl($in){
	$line=0;
	$result="";
	
	$in_arr=explode("\n", $in);
	
	foreach($in_arr as $row){
		$line++;
		
		//skip body
		if ($row=="") break;
		
		// uri and outbound uri at line 2,3: copy and paste
		if ($line==1 || $line==2) {
			$result.=$row."\n";
			continue;
		}
		
		// line 4: Route; empty if ".", copy and paste otherwise
		if ($line==3 && $row=".") continue;
		// if non-empty, copy and paste it
		if ($line==3) {
			$result.=$row."\n";
			continue;
		}

		// filter out to header field for use in next requests	
		if (ereg ("^(To|t):", $row)){
			$result.=$row."\n";
			continue;
		}
		
		// anything else will be ignored
		
	}
	
	return $result;
}

function click_to_dial($target, $uri, &$errors){
	global $config;

	$from="<sip:controller@foo.bar>";
	$callidnr = uniqid("");
	$callid = $callidnr.".fifouacctd";
	$cseq=1;
	$fixed_dlg="From: ".$from.";tag=".$callidnr."\nCall-ID: ".$callid."\nContact: <sip:caller@!!>";
	$status="";


/* initiate dummy INVITE with pre-3261 "on-hold"
   (note the dots -- they mean in order of appearance:
   outbound uri, end of headers, end of body; eventualy
   the FIFO request must be terminated with an empty line) */
	
	$fifo_cmd=":t_uac_dlg:".$config->reply_fifo_filename."\n".
		"INVITE\n".
		$uri."\n".
		".\n".
		"$fixed_dlg\n".
		"To: <".$uri.">\n".
		"CSeq: ".$cseq." INVITE\n".
		"Content-Type: application/sdp\n".
		".\n".
		"v=0\n".
		"o=click-to-dial 0 0 IN IP4 0.0.0.0\n".
		"s=session\n".
		"c=IN IP4 0.0.0.0\n".
		"b=CT:1000\n".
		"t=0 0\n".
		"m=audio 9 RTP/AVP 0\n".
		"a=rtpmap:0 PCMU/8000\n".
		".\n\n";
		
	$dlg=write2fifo($fifo_cmd, $errors, $status);
	if (substr($status,0,1)!="2") {$errors[]=$status; return; }

	$dlg=filter_fl($dlg);
	
	$cseq++;
	
	// start reader now so that it is ready for replies
	// immediately after a request is out
	
	$fifo_cmd=":t_uac_dlg:".$config->reply_fifo_filename."\n".
		"REFER\n".
		$dlg.		//"\n".
		"$fixed_dlg\n".
		"CSeq: ".$cseq." REFER\n".
		"Referred-By: ".$from."\n".
		"Refer-To: ".$target."\n".
		".\n".
		".\n\n";

	write2fifo($fifo_cmd, $errors, $status);
	if (substr($status,0,1)!="2") {$errors[]=$status; return; }

	$cseq++;

/* well, URI is trying to call TARGET but still maintains the
   dummy call we established with previous INVITE transaction:
   tear it down */

	$fifo_cmd=":t_uac_dlg:".$config->reply_fifo_filename."\n".
		"BYE\n".
		$dlg.  // "\n".
		$fixed_dlg."\n".
		"CSeq: ".$cseq." BYE\n".
		".\n".
		".\n\n";

	write2fifo($fifo_cmd, $errors, $status);
	if (substr($status,0,1)!="2") {$errors[]=$status; return; }
   
}
?>
