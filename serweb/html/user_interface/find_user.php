<?
/*
 * $Id: find_user.php,v 1.14 2004/04/04 19:42:14 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

define("max_rows",50);

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

class Cuser{
	var $first_name, $last_name, $username, $timezone, $aliases;
	function Cuser($fname, $lname, $username, $timezone){
		$this->first_name 	= $fname;
		$this->last_name 	= $lname;
		$this->username 	= $username;
		$this->timezone 	= $timezone;

		$this->aliases=array();
	}
}

do{
	if (!$db = connect_to_db($errors)) break;

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
		if ($onlineonly)
			$q=	"select distinct s.timezone, s.first_name, s.last_name, s.username ".
				"from ".$config->table_subscriber." s, ".$config->table_location." l ".
				" where s.username=l.username and s.allow_find='1' and ".
					"s.first_name like '%$fname%' and s.last_name like '%$lname%' ".
					"and s.username like '%$uname%' and s.domain='$config->realm' limit 0,".$config->max_showed_rows;
		else
			$q=	"select timezone, first_name, last_name, username from ".$config->table_subscriber.
				" where allow_find='1' and first_name like '%$fname%' and last_name like '%$lname%' ".
				"and username like '%$uname%' and domain='$config->realm' limit 0,".$config->max_showed_rows;

		$find_res=$db->query($q);
		if (DB::isError($find_res)) {log_errors($find_res, $errors); break;}

		$found_users=array();
		while ($row=$find_res->fetchRow(DB_FETCHMODE_OBJECT)){
			$found_users[$row->username]=new Cuser($row->first_name, $row->last_name, $row->username, $row->timezone);
                        $found_users[$row->username]->aliases = get_aliases("sip:".$row->username."@".$config->realm, $db, $errors);
		}
	}

}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=get_user_name($db, $errors);
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
	<?if ($find_res->numRows()==$config->max_showed_rows){?>
	<div class="swNumOfFoundRecords">The search generated too many matches, please be more specific</div>
	<?}else{?>
	<br />
<?	}
	$find_res->free();?>
<?}elseif(isset($okey_x)){?>
	<div class="swNumOfFoundRecords">No users found</div>
<?}?>

<div class="swBackToMainPage"><a href="<?$sess->purl("phonebook.php?kvrk=".uniqid(""));?>">back to phonebook</a></div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
