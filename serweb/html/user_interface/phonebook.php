<?
require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

class Cphonebook{
	var $id;
	var $fname;
	var $lname;
	var $sip_uri;
	var $status;
	
	function Cphonebook($id, $fname, $lname, $sip_uri, $status='unknown'){
		$this->id=$id;
		$this->fname=$fname;
		$this->lname=$lname;
		$this->sip_uri=$sip_uri;
		$this->status=$status;
	}
}

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}
	
	if ($dele_id){
		$q="delete from ".$config->table_phonebook." where user='".$auth->auth["uname"]."' and id=".$dele_id;
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	
        Header("Location: ".$sess->url("phonebook.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
	
	if ($edit_id){
		$q="select fname, lname, sip_uri from ".$config->table_phonebook." where user='".$auth->auth["uname"]."' and id=".$edit_id;
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		$row=mysql_fetch_object($res);
	}	
	
	$f->add_element(array("type"=>"text",
	                             "name"=>"fname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>$row->fname?$row->fname:"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"lname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>$row->lname?$row->lname:"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"sip_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>$row->sip_uri?$row->sip_uri:"",
	                             "valid_regex"=>"^".$reg->sip_address."$",
	                             "valid_e"=>"not valid sip address",
								 "extrahtml"=>"onBlur='sip_address_completion(this)' style='width:120px;'"));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"id",
	                             "value"=>$edit_id?$edit_id:""));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));
	
	
	if (isset($okey_x)){								// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			/* Process data */           // Data ok; 

		if ($id) $q="update ".$config->table_phonebook." set fname='$fname', lname='$lname', sip_uri='$sip_uri' where id=$id and user='".$auth->auth["uname"]."'";
		else $q="insert into ".$config->table_phonebook." (fname, lname, sip_uri, user) values ('$fname', '$lname', '$sip_uri', '".$auth->auth["uname"]."')";
		
//		echo "".$q;
		
		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		
        Header("Location: ".$sess->url("phonebook.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while (false);

do{
	if ($db){
		// get phonebook
		if ($edit_id) $qw=" and id!=$edit_id "; else $qw="";
		
		$q="select id, fname, lname, sip_uri from ".$config->table_phonebook." where user='".$auth->auth["uname"]."'".$qw." order by lname";
		$phonebook_res=MySQL_Query($q);
		if (!$phonebook_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		
		while ($row=MySQL_Fetch_Object($phonebook_res)){
			$pb_arr[]=new Cphonebook($row->id, $row->fname, $row->lname, $row->sip_uri, get_status($row->sip_uri, $errors));
		}
		
	}
}while (false);

if ($okey_x){							//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>iptel.org, the IP Telephony Site</title>
<?print_html_head();?>
<script language="JavaScript">
<!--
	function sip_address_completion(adr){
		var default_domain='<?echo $config->default_domain;?>';
		
		var re = /^<?echo str_replace('/','\/',$reg->user);?>$/i;
//		var re = new RegExp("^<?echo $reg->user;?>$","i");
		if (re.test(adr.value)) {
			adr.value=adr.value+'@'+default_domain;
		}

		var re = /^<?echo str_replace('/','\/',$reg->address);?>$/i
//		var re = new RegExp("^<?echo $reg->address;?>$","i");
		var re2= /^sip:/i;
		if (re.test(adr.value) && !re2.test(adr.value)) {
			adr.value='sip:'+adr.value;
		}
	}
//-->
</script>
</head>
<?
	print_html_body_begin(2, true, true, get_user_name($errors));
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>

<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="right" class="f12b">first name:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("fname");?></td>
	</tr>
	<tr>
	<td align="right" class="f12b">last name:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("lname");?></td>
	</tr>
	<tr>
	<td align="right" class="f12b">sip address:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("sip_uri");?></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","sip_address_completion(f.sip_uri);");					// Finish form?>

<?if (is_array($pb_arr)){?>

<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT" width="160">name</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="205">sip address</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="85">status</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="50">&nbsp;</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="50">&nbsp;</td>
	</tr>
	<tr><td colspan="9" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?foreach($pb_arr as $row){
		$name=$row->lname;
		if ($name) $name.=" "; $name.=$row->fname;
	?>
	<tr valign="top">
	<td align="left" class="f12" width="160">&nbsp;<?echo $name;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12" width="205">&nbsp;<a href="javascript: alert('click to dial not implemented');"><?echo $row->sip_uri;?></a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="85"><?echo $row->status;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="50"><a href="<?$sess->purl("phonebook.php?kvrk=".uniqID("")."&edit_id=".$row->id);?>">edit</a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="50"><a href="<?$sess->purl("phonebook.php?kvrk=".uniqID("")."&dele_id=".$row->id);?>">delete</a></td>
	</tr>
	<?}?>
	</table>
</td></tr>
</table>

<?}?>


<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
