<?
/*
 * $Id: caller_screening.php,v 1.5 2004/04/04 19:42:14 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

if (isset($_POST['edit_caller'])) $edit_caller=$_POST['edit_caller'];
elseif (isset($_GET['edit_caller'])) $edit_caller=$_GET['edit_caller'];
else $edit_caller=null;

do{
	if (!$db = connect_to_db($errors)) break;

	if (isset($_GET['dele_caller'])){
		$q="delete from ".$config->table_calls_forwarding." where ".
			"username='".$auth->auth["uname"]."' and domain='".$config->realm."' and purpose='screening' and uri_re='".$_GET['dele_caller']."'";
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}

        Header("Location: ".$sess->url("caller_screening.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if ($edit_caller){
		$q="select uri_re, action, param1, param2 from ".$config->table_calls_forwarding.
			" where domain='".$config->realm."' and username='".$auth->auth["uname"]."' and purpose='screening' and uri_re='".$edit_caller."'";
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}
		$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();
	}

	//create array of options of select
	$opt=array();
	foreach($config->calls_forwarding["screening"] as $k => $v){
		$opt[]=array("label" => $v->label, "value" => $k);
	}

	$f->add_element(array("type"=>"text",
	                             "name"=>"uri_re",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->uri_re)?$row->uri_re:"",
								 "minlength"=>1,
								 "length_e"=>"you must fill caller uri",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"select",
	                             "name"=>"action_key",
								 "size"=>1,
	                             "value"=>isset($row->action)?(
								 			Ccall_fw::get_key($config->calls_forwarding["screening"],
																$row->action,
																$row->param1,
																$row->param2)
											):"",
								 "options"=>$opt,
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"edit_caller",
	                             "value"=>$edit_caller?$edit_caller:""));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));


	if (isset($_POST['okey_x'])){				// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			/* Process data */           // Data ok;

		if ($edit_caller) $q="update ".$config->table_calls_forwarding." set ".
									"uri_re='$uri_re', ".
									"action='".$config->calls_forwarding["screening"][$action_key]->action."', ".
									"param1='".$config->calls_forwarding["screening"][$action_key]->param1."', ".
									"param2='".$config->calls_forwarding["screening"][$action_key]->param2."' ".
			"where uri_re='$edit_caller' and purpose='screening' and domain='".$config->realm."' and username='".$auth->auth["uname"]."'";

		else $q="insert into ".$config->table_calls_forwarding." (username, domain, uri_re, purpose, action, param1, param2) ".
			"values ('".$auth->auth["uname"]."',
					'".$config->realm."',
					'$uri_re',
					'screening',
					'".$config->calls_forwarding["screening"][$action_key]->action."',
					'".$config->calls_forwarding["screening"][$action_key]->param1."',
					'".$config->calls_forwarding["screening"][$action_key]->param2."')";

		$res=$db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_ALREADY_EXISTS)
				$errors[]="Record with this caller uri already exists";
			else log_errors($res, $errors); 
			break;
		}

        Header("Location: ".$sess->url("caller_screening.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while (false);

do{
	if ($db){
		// get speed dials
		if ($edit_caller) $qw=" and uri_re!='$edit_caller' "; else $qw="";

		$q="select uri_re, action, param1, param2 from ".$config->table_calls_forwarding.
			" where domain='".$config->realm."' and username='".$auth->auth["uname"]."' and purpose='screening'".$qw." order by uri_re";
		$cs_res=$db->query($q);
		if (DB::isError($cs_res)) {log_errors($cs_res, $errors); break;}

	}
}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
$page_attributes['user_name']=get_user_name($db, $errors);
print_html_body_begin($page_attributes);
?>

<div class="swForm">
<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="uri_re">caller uri (regular expression):</label></td>
	<td><?$f->show_element("uri_re");?></td>
	</tr>
	<tr>
	<td><label for="action_key">action:</label></td>
	<td><?$f->show_element("action_key");?></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","");					// Finish form?>
</div>

<?if (!DB::isError($cs_res) and $cs_res->numRows()){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>caller uri</th>
	<th>action</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	while ($row=$cs_res->fetchRow(DB_FETCHMODE_OBJECT)){
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($row->uri_re);?></td>
	<td align="left"><?echo nbsp_if_empty(Ccall_fw::get_label($config->calls_forwarding["screening"], $row->action, $row->param1, $row->param2));?></td>
	<td align="center"><a href="<?$sess->purl("caller_screening.php?kvrk=".uniqID("")."&edit_caller=".$row->uri_re);?>">edit</a></td>
	<td align="center"><a href="<?$sess->purl("caller_screening.php?kvrk=".uniqID("")."&dele_caller=".$row->uri_re);?>">delete</a></td>
	</tr>
	<?}//while
	$cs_res->free();?>
	</table>
<?}else{?>

<div class="swNumOfFoundRecords">No caller screenings defined</div>

<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
