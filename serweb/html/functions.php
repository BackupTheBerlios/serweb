<?

$reg_validate_email="^[^@]+@[^.]+\.[^,;@ ]+$";

$reg_validate_username="^(8[0-9]*)|([a-zA-Z][a-zA-Z0-9.]*)$";

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
		$this->ipv4address="([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})";
		$this->ipv6address="(".$this->hexpart."(:".$this->ipv4address.")?)";
		$this->ipv6reference="(\\[".$this->ipv6address."])";
			
		$this->toplabel="([a-zA-Z]|([a-zA-Z](~|".$this->alphanum.")*".$this->alphanum."))";
		$this->domainlabel="(".$this->alphanum."|(".$this->alphanum."(~|".$this->alphanum.")*".$this->alphanum."))";
		$this->hostname="((".$this->domainlabel."\.)*".$this->toplabel."(\.)?)";
		$this->host="(".$this->hostname."|".$this->ipv4address."|".$this->ipv6reference.")";
	
		$this->token="(([-.!%*_+`'~]|".$this->alphanum.")+)";
		$this->param_unreserved="[][/:&+$]";
		$this->paramchar="(".$this->param_unreserved."|".$this->unreserved."|".$this->escaped.")";
		$this->pname="((".$this->paramchar.")+)";
		$this->pvalue="((".$this->paramchar.")+)";
		$this->uri_parameter="(".$this->pname."(=".$this->pvalue.")?)";
		$this->uri_parameters="((;".$this->uri_parameter.")*)";
		
		$this->address="(".$this->user."@)?".$this->host."(:".$this->port.")?".$this->uri_parameters;
		$this->sip_address="[sS][iI][pP]:".$this->address;
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
	
	$q="select count(*) from ".$config->table_subscriber." where user_id='$user'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; return "<div class=\"statusunknown\">unknown</div>";}
	$row=mysql_fetch_row($res);
	if (!$row[0]) return "<div class=\"statusunknown\">non-existent</div>";

	$q="select count(*) from ".$config->table_location." where user='$user'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; return "<div class=\"statusunknown\">unknown</div>";}
	$row=mysql_fetch_row($res);
	
	if ($row[0]) return "<div class=\"statusonline\">on line</div>";
	else return "<div class=\"statusoffline\">off line</div>";
}

function send_mail($to, $subj, $text, $headers = ""){
	global $config;
	
	$headers .= "From: ".$config->mail_header_from."\n";
	
	@$a= Mail($to, $subj, $text, $headers);
	return $a;
}

function write2fifo($fifo_cmd, &$errors){
	global $config;

	/* open fifo now */
	$fifo_handle=fopen( $config->fifo_server, "w" );
	if (!$fifo_handle) {
		$errors[]="sorry -- cannot open fifo"; return;
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
		$errors[]="sorry -- fifo reading error"; return;
	}

	$rd=fread($fp,255);
	if (!eregi("200 *OK",$rd)){
	    @unlink($config->reply_fifo_path);
		if (!$rd) $errors[]="sorry -- fifo reading error";
		/* just write it out as you get it in; -jiri
		else $errors[]="sorry error ".$rd; 
		*/
		else $errors[]=$rd;
		return;
	}

	@unlink($config->reply_fifo_path);
}

function get_user_name(&$errors){
	global $auth, $config;

	$q="select first_name, last_name from ".$config->table_subscriber." where user_id='".$auth->auth["uname"]."'";
	@$res=MySQL_Query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; return false;}
	if (!MySQL_Num_rows($res)) return false;
	
	$row=MySQL_Fetch_Object($res);
	
	$name=$row->first_name;
	if ($name) $name.=" ";
	return $name.=$row->last_name;

}
?>
