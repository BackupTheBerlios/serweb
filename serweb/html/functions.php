<?
/*
 * $Id: functions.php,v 1.29 2004/01/18 22:15:18 jiri Exp $
 */


$reg_validate_email="^[^@]+@[^.]+\.[^,;@ ]+$";

$reg_validate_username="^((8[0-9]*)|([a-zA-Z][a-zA-Z0-9.]*))$";

$name_of_month[1]="Jan";
$name_of_month[2]="Feb";
$name_of_month[3]="Mar";
$name_of_month[4]="Apr";
$name_of_month[5]="May";
$name_of_month[6]="Jun";
$name_of_month[7]="Jul";
$name_of_month[8]="Aug";
$name_of_month[9]="Sep";
$name_of_month[10]="Oct";
$name_of_month[11]="Nov";
$name_of_month[12]="Dec";

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

		/* toplabel is the name of top-level DNS domain ".com" -- alphanum only
		   domainlabel is one part of the dot.dot name string in a DNS name ("iptel");
		     it must begin with alphanum, can contain special characters (-) and end
			 with alphanums
		   hostname can include any number of domain lables and end with toplable
		*/
		$this->toplabel="([a-zA-Z]|([a-zA-Z](~|".$this->alphanum.")*".$this->alphanum."))";
		$this->domainlabel="(".$this->alphanum."|(".$this->alphanum."([~-]|".$this->alphanum.")*".$this->alphanum."))";
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

	if ($actual>0) echo '<a href="'.get_sess_url($url.((($actual-$rows)>0)?($actual-$rows):0)).'" class="f12">&lt;&lt;&lt;</a>&nbsp;';
	for ($i=$lfrom; $i<$lto; $i+=$rows){
		if ($i<=$actual and $actual<($i+$rows)) echo '<span class="f14b">'.(floor($i/$rows)+1).'</span>&nbsp;';
		else echo '<a href="'.get_sess_url($url.$i).'" class="f12">'.(floor($i/$rows)+1).'</a>&nbsp;';
	}
 	if (($actual+$rows)<$num) echo '<a href="'.get_sess_url($url.($actual+$rows)).'" class="f12">&gt;&gt;&gt;</a>';

}

function connect_to_db(){
	global $config;

	@$db=MySQL_PConnect($config->db_host, $config->db_user, $config->db_pass);
	if (! $db) return false;

	if (!MySQL_Select_DB($config->db_name, $db)) return false;
	return $db;
}

/* must be called before connect_to_db() */
function connect_to_ppaid_db(){
	global $config;

	@$db=MySQL_PConnect($config->ppaid->db_host, $config->ppaid->db_user, $config->ppaid->db_pass);
	if (! $db) return false;

	if (!MySQL_Select_DB($config->ppaid->db_name, $db)) return false;
	return $db;
}

function get_status($sip_uri, &$errors){
	global $config;

	$reg=new Creg;
	if (!eregi("^sip:([^@]+@)?".$reg->host, $sip_uri, $regs)) return "<div class=\"statusunknown\">non-local</div>";

	if (strtolower($regs[2])!=strtolower($config->default_domain)) return "<div class=\"statusunknown\">non-local</div>";

	$user=substr($regs[1],0,-1);

	$q="select count(*) from ".$config->table_subscriber.
		" where username='$user' and domain='$config->realm'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; return "<div class=\"statusunknown\">unknown</div>";}
	$row=mysql_fetch_row($res);
	if (!$row[0]) return "<div class=\"statusunknown\">non-existent</div>";


	$fifo_cmd=":ul_show_contact:".$config->reply_fifo_filename."\n".
	$config->ul_table."\n".		//table
	$user."@".$config->default_domain."\n\n";	//username

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

	$q="select first_name, last_name from ".$config->table_subscriber.
		" where domain='".$config->realm."' and username='".$auth->auth["uname"]."'";
	@$res=MySQL_Query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; return false;}
	if (!MySQL_Num_rows($res)) return false;
	
	$row=MySQL_Fetch_Object($res);

	return $row->first_name." ".$row->last_name." &lt;".$auth->auth["uname"]."@".$config->realm."&gt;";

/*
	$name=$row->first_name;
	if ($name) $name.=" ";
	return $name.=$row->last_name;
*/

}

function get_time_zones(&$errors){
	global $config;

	@$fp=fopen($config->zonetab_file, "r");
	if (!$fp) {$errors[]="Cannot open zone.tab file"; return false;}
	
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

	$q="select timezone from ".$config->table_subscriber.
		" where domain='".$config->realm."' and username='".$auth->auth["uname"]."'";
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

function mylog($st) {
	$handle=fopen("/tmp/fff", 'a');
	fwrite($handle, $st);
	fclose($handle);
}

function click_to_dial($target, $uri, &$errors){
	global $config;

	$from="<".$config->ctd_from.">";
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

function ptitle($title, $width=502){?>
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td class="title" width="<? echo $width; ?>"><?echo $title;?></td></tr>
</table><br>
<?}


function set_password_to_user($user_id, $passwd, &$errors){
	global $config;
	
	$inp=$user_id.":".$config->realm.":".$passwd;
	$ha1=md5($inp);
	
	$inpb=$user_id."@".$config->domainname.":".$config->realm.":".$passwd;
	$ha1b=md5($inpb);

	$q="update ".$config->table_subscriber." set password='$passwd', ha1='$ha1', ha1b='$ha1b' ".
		" where username='".$user_id."' and domain='".$config->realm."'";

	$res=MySQL_Query($q);
	if (!$res) {$errors[]="error in SQL query - ".__FILE__.":".__LINE__; return false;}

	return true;
}

/**************************************
 *         find users class
 **************************************/

class Cfusers{
	var $classname='Cfusers';
	var $persistent_slots = array("usrnm", "fname", "lname", "email", "onlineonly", "act_row");

	var $usrnm, $fname, $lname, $email, $onlineonly, $act_row=0;

	var $f;

	function init(){
		global $sess, $config, $HTTP_POST_VARS, $HTTP_GET_VARS;

		if (isset($HTTP_POST_VARS['usrnm'])) $this->usrnm=$HTTP_POST_VARS['usrnm'];
		if (isset($HTTP_POST_VARS['fname'])) $this->fname=$HTTP_POST_VARS['fname'];
		if (isset($HTTP_POST_VARS['lname'])) $this->lname=$HTTP_POST_VARS['lname'];
		if (isset($HTTP_POST_VARS['email'])) $this->email=$HTTP_POST_VARS['email'];
		if (isset($HTTP_POST_VARS['okey_x'])) $this->onlineonly=$HTTP_POST_VARS['onlineonly'];

		if (isset($HTTP_GET_VARS['act_row'])) $this->act_row=$HTTP_GET_VARS['act_row'];
		if (isset($HTTP_POST_VARS['okey_x'])) $this->act_row=0;

//		if (!$this->act_row or isset($http_get_vars['okey_x'])) $sess_act_row=0;
		
		$this->f = new form;                   // create a form object
		
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"usrnm",
									 "size"=>11,
									 "maxlength"=>50,
		                             "value"=>$this->usrnm,
									 "extrahtml"=>"style='width:120px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"fname",
									 "size"=>11,
									 "maxlength"=>25,
		                             "value"=>$this->fname,
									 "extrahtml"=>"style='width:120px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"lname",
									 "size"=>11,
									 "maxlength"=>45,
		                             "value"=>$this->lname,
									 "extrahtml"=>"style='width:120px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"email",
									 "size"=>11,
									 "maxlength"=>50,
		                             "value"=>$this->email,
									 "extrahtml"=>"style='width:120px;'"));
		$this->f->add_element(array("type"=>"checkbox",
		                             "value"=>1,
									 "checked"=>$this->onlineonly,
		                             "name"=>"onlineonly"));
		
		$this->f->add_element(array("type"=>"submit",
		                             "name"=>"okey",
		                             "src"=>$config->img_src_path."butons/b_find.gif",
									 "extrahtml"=>"alt='find'"));
	
	}
	
	function get_query_where_phrase($tablename=""){
		if ($tablename) $tablename.=".";
	
		$query_c="";
		if ($this->usrnm) $query_c.=$tablename."username like '%".$this->usrnm."%' and ";
		if ($this->fname) $query_c.=$tablename."first_name like '%".$this->fname."%' and ";
		if ($this->lname) $query_c.=$tablename."last_name like '%".$this->lname."%' and ";
		if ($this->email) $query_c.=$tablename."email_address like '%".$this->email."%' and ";
		$query_c.="1 ";
		
		return $query_c;
	
	}
	
	function print_form(){
		$this->f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td class="f12b">username</td>
	<td class="f12b">first name</td>
	<td class="f12b">last name</td>
	<td class="f12b">email</td>
	</tr>
	<tr>
	<td><?$this->f->show_element("usrnm");?></td>
	<td><?$this->f->show_element("fname");?></td>
	<td><?$this->f->show_element("lname");?></td>
	<td><?$this->f->show_element("email");?></td>
	</tr>
	<tr><td colspan="4" class="f12b">show on-line users only: <?$this->f->show_element("onlineonly");?></td></tr>
	<tr><td colspan="4" align="right"><?$this->f->show_element("okey");?></td></tr>
	</table>
<?		$this->f->finish();					// Finish form
	}

}
 

?>
