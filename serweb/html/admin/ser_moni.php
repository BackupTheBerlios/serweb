<?
/*
 * $Id: ser_moni.php,v 1.3 2003/10/13 19:56:43 kozlik Exp $
 */

require "prepend.php";
require "ser_moni_funct.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}

	//get values from database
	$q="select param, lv, av, mv, ad, min_val, max_val, min_inc, max_inc  from ".$config->table_ser_mon_agg;
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

	//create assoc. array
	while ($row=MySQL_Fetch_Object($res)) {
		$values[$row->param]=$row;

		//create list of usrloc stats
		if (substr($row->param, 0, 3) == "ul_" and substr($row->param, -4) == "_reg")
			$ul_params[]=substr($row->param, 3, -4);

	}//while



}while (false);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?echo $config->title;?></title>
<?print_html_head();?>
</head>
<?
	print_admin_html_body_begin();
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>

	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr><td align="center"><strong>Transaction Statistics</strong></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td>&nbsp;</td><td width="220" align="center"><em>general values</em></td><td width="220" align="center"><em>diferencial values</em></td></tr>
		</table></td></tr>
	<tr><td><? print_value ("current", "average", $values['ts_current']);?></td></tr>
	<tr><td><? print_value ("waiting current", "waiting average", $values['ts_waiting']);?></td></tr>
	<tr><td><? print_value ("total current", "total average", $values['ts_total']);?></td></tr>
	<tr><td><? print_value ("local current", "local average", $values['ts_total_local']);?></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><? print_value ("replied localy current", "replied localy average", $values['ts_replied']);?></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td align="center"><em>Completion status</em></td></tr>
	<tr><td><? print_value ("6xx current", "6xx average", $values['ts_6xx']);?></td></tr>
	<tr><td><? print_value ("5xx current", "5xx average", $values['ts_5xx']);?></td></tr>
	<tr><td><? print_value ("4xx current", "4xx average", $values['ts_4xx']);?></td></tr>
	<tr><td><? print_value ("3xx current", "3xx average", $values['ts_3xx']);?></td></tr>
	<tr><td><? print_value ("2xx current", "2xx average", $values['ts_2xx']);?></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td align="center"><strong>Stateless Server Statistics</strong></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td>&nbsp;</td><td width="220" align="center"><em>general values</em></td><td width="220" align="center"><em>diferencial values</em></td></tr>
		</table></td></tr>
	<tr><td><? print_value ("200 current", "200 average", $values['sl_200']);?></td></tr>
	<tr><td><? print_value ("202 current", "202 average", $values['sl_202']);?></td></tr>
	<tr><td><? print_value ("2xx current", "2xx average", $values['sl_2xx']);?></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><? print_value ("300 current", "300 average", $values['sl_300']);?></td></tr>
	<tr><td><? print_value ("301 current", "301 average", $values['sl_301']);?></td></tr>
	<tr><td><? print_value ("302 current", "302 average", $values['sl_302']);?></td></tr>
	<tr><td><? print_value ("3xx current", "3xx average", $values['sl_3xx']);?></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><? print_value ("400 current", "400 average", $values['sl_400']);?></td></tr>
	<tr><td><? print_value ("401 current", "401 average", $values['sl_401']);?></td></tr>
	<tr><td><? print_value ("403 current", "403 average", $values['sl_403']);?></td></tr>
	<tr><td><? print_value ("404 current", "404 average", $values['sl_404']);?></td></tr>
	<tr><td><? print_value ("407 current", "407 average", $values['sl_407']);?></td></tr>
	<tr><td><? print_value ("408 current", "408 average", $values['sl_408']);?></td></tr>
	<tr><td><? print_value ("483 current", "483 average", $values['sl_483']);?></td></tr>
	<tr><td><? print_value ("4xx current", "4xx average", $values['sl_4xx']);?></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><? print_value ("500 current", "500 average", $values['sl_500']);?></td></tr>
	<tr><td><? print_value ("5xx current", "5xx average", $values['sl_5xx']);?></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><? print_value ("6xx current", "6xx average", $values['sl_6xx']);?></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><? print_value ("xxx current", "xxx average", $values['sl_xxx']);?></td></tr>
	<tr><td>&nbsp;</td></tr>
	<?if (is_array($ul_params)){?>
	<tr><td align="center"><strong>UsrLoc Stats</strong></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td>&nbsp;</td><td width="220" align="center"><em>general values</em></td><td width="220" align="center"><em>diferencial values</em></td></tr>
		</table></td></tr>
	<?foreach($ul_params as $row){?>
	<tr><td><em>domain:</em> <strong><?echo $row;?></strong></td></tr>
	<tr><td><? print_value ("registered current", "registered average", $values['ul_'.$row.'_reg']);?></td></tr>
	<tr><td><? print_value ("expired current", "expired average", $values['ul_'.$row.'_exp']);?></td></tr>
	<tr><td>&nbsp;</td></tr>
	<?}}?>
	</table>

<?print_html_body_end();?>
</html>
<?page_close();?>
