<?
/*
 * $Id: find_user.php,v 1.15 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

define("max_rows",50);

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

do{
	if (!$data = CData_Layer::create($errors)) break;

	$f->add_element(array("type"=>"text",
	                             "name"=>"fname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"lname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"uname",
								 "size"=>16,
								 "maxlength"=>50,
	                             "value"=>"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"checkbox",
	                             "value"=>1,
	                             "name"=>"onlineonly"));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_find.gif",
								 "extrahtml"=>"alt='find'"));

	$found_users=array();

	if (isset($_POST['okey_x'])){				// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			/* Process data */           // Data ok;
		$found_users=array();
		if(false === $found_users = $data->find_users($_POST['fname'], $_POST['lname'], $_POST['uname'], $config->domain, isset($_POST['onlineonly'])?1:0, $errors)) break;
	}

}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=$data->get_user_name($errors);
$page_attributes['selected_tab']="phonebook.php";
print_html_body_begin($page_attributes);
?>

<h2 class="swTitle">Find user</h2>

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
	<td><label for="uname">user name:</label></td>
	<td><?$f->show_element("uname");?></td>
	</tr>
	<tr>
	<td><label for="onlineonly">show on-line users only:</label></td>
	<td><?$f->show_element("onlineonly");?></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","");					// Finish form?>
</div>

<?if (is_array($found_users) and count($found_users)){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>name</th>
	<th>sip address</th>
	<th>aliases</th>
	<th>timezone</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	foreach($found_users as $row){
		$odd=$odd?0:1;
		$name=$row->last_name;
		if ($name) $name.=" "; $name.=$row->first_name;
		$sip_uri="sip:".$row->username."@".$config->default_domain;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($name);?></td>
	<td align="left"><?echo nbsp_if_empty($sip_uri);?></td>
	<td align="left"><?echo nbsp_if_empty(implode(", ", $row->aliases));?></td>
	<td align="left"><?echo nbsp_if_empty($row->timezone);?></td>
	<td align="center"><a href="<?$sess->purl("phonebook.php?kvrk=".uniqID("")."&okey_x=1&fname=".RawURLEncode($row->first_name)."&lname=".RawURLEncode($row->last_name)."&sip_uri=".RawURLEncode($sip_uri));?>">add to phonebook</a></td>
	</tr>
	<?}?>
	</table>
	<?if (count($found_users)==$config->max_showed_rows){?>
	<div class="swNumOfFoundRecords">The search generated too many matches, please be more specific</div>
	<?}else{?>
	<br />
<?	}?>
<?}elseif(isset($okey_x)){?>
	<div class="swNumOfFoundRecords">No users found</div>
<?}?>

<div class="swBackToMainPage"><a href="<?$sess->purl("phonebook.php?kvrk=".uniqid(""));?>">back to phonebook</a></div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
