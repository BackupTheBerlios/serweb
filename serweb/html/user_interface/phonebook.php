<?
/*
 * $Id: phonebook.php,v 1.16 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

set_global('okey_x');
set_global('id');
set_global('fname');
set_global('lname');
set_global('sip_uri');

do{
	if (!$data = CData_Layer::create($errors)) break;

	if (isset($_GET['dele_id'])){
		if (!$data->del_phonebook_entry($auth->auth["uname"], $config->domain, $_GET['dele_id'], $errors)) break;

        Header("Location: ".$sess->url("phonebook.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if (isset($_GET['edit_id'])){
		if (false === $row = $data->get_phonebook_entry($auth->auth["uname"], $config->domain, $_GET['edit_id'], $errors)) break;
	}

	$f->add_element(array("type"=>"text",
	                             "name"=>"fname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>isset($row->fname)?$row->fname:"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"lname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>isset($row->lname)?$row->lname:"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"sip_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->sip_uri)?$row->sip_uri:"",
	                             "valid_regex"=>"^".$reg->sip_address."$",
	                             "valid_e"=>"not valid sip address",
								 "extrahtml"=>"onBlur='sip_address_completion(this)' style='width:120px;'"));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"id",
	                             "value"=>isset($_GET['edit_id'])?$_GET['edit_id']:""));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));


	if (!is_null($okey_x)){				// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			/* Process data */           // Data ok;

		if (!$data->update_phonebook_entry($auth->auth["uname"], $config->domain, $id, $fname, $lname, $sip_uri, $errors)) break;
		
        Header("Location: ".$sess->url("phonebook.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while (false);

do{
	$pb_res=array();
	if ($data){
		// get phonebook
		if (false === $pb_res = $data->get_phonebook_entries($auth->auth["uname"], $config->domain, isset($_GET['edit_id'])?$_GET['edit_id']:NULL, $errors)) break;
	}
}while (false);

if (!is_null($okey_x)){							//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>sip_address_completion.js.php"></script>
<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
$page_attributes['user_name']=$data->get_user_name($errors);
print_html_body_begin($page_attributes);
?>

<div class="swForm">
<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="fname">first name:</label></td>
	<td><?$f->show_element("fname");?></td>
	</tr>
	<tr>
	<td><label for="lname">last name:</label></td>
	<td><?$f->show_element("lname");?></td>
	</tr>
	<tr>
	<td><label for="sip_uri">sip address:</label></td>
	<td><?$f->show_element("sip_uri");?></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","sip_address_completion(f.sip_uri);");					// Finish form?>
</div>

<?if (is_array($pb_res) and count($pb_res)){?>
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>name</th>
	<th>sip address</th>
	<th>aliases</th>
	<th>status</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	foreach($pb_res as $row){
		$odd=$odd?0:1;
		$name=$row->lname;
		if ($name) $name.=" "; $name.=$row->fname;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($name);?></td>
	<td align="left"><a href="javascript: open_ctd_win2('<?echo rawURLEncode($row->sip_uri);?>', '<?echo RawURLEncode("sip:".$auth->auth["uname"]."@".$config->default_domain); ?>');"><?echo $row->sip_uri;?></a></td>
	<td align="left"><?echo nbsp_if_empty(implode(", ", $row->aliases));?></td>
	<td align="center"><?echo nbsp_if_empty($row->status);?></td>
	<td align="center"><a href="<?$sess->purl("phonebook.php?kvrk=".uniqID("")."&edit_id=".$row->id);?>">edit</a></td>
	<td align="center"><a href="<?$sess->purl("phonebook.php?kvrk=".uniqID("")."&dele_id=".$row->id);?>">delete</a></td>
	</tr>
	<?}?>
	</table>
<?}?>

<div class="swLinkToTabExtension"><a href="<?$sess->purl("find_user.php?kvrk=".uniqid(""));?>">find user</a></div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
