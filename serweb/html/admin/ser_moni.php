<?
/*
 * $Id: ser_moni.php,v 1.5 2004/03/24 21:39:46 kozlik Exp $
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

/* ----------------------- HTML begin ---------------------- */
print_html_head();
print_html_body_begin($page_attributes);
?>

	<h2 class="swTitle">Transaction Statistics</h2>

	<div class="swSMgdval">
		<span class="swSMgval"><em>general values</em></span>
		<span class="swSMdval"><em>diferencial values</em></span>
	</div>
	<? print_value ("current", "average", $values['ts_current']);?>
	<? print_value ("waiting current", "waiting average", $values['ts_waiting']);?>
	<? print_value ("total current", "total average", $values['ts_total']);?>
	<? print_value ("local current", "local average", $values['ts_total_local']);?>
	<br/>
	<? print_value ("replied localy current", "replied localy average", $values['ts_replied']);?>
	<br/>

	<h2 class="swTitle">Completion status</h2>

	<div class="swSMgdval">
		<span class="swSMgval"><em>general values</em></span>
		<span class="swSMdval"><em>diferencial values</em></span>
	</div>
	<? print_value ("6xx current", "6xx average", $values['ts_6xx']);?>
	<? print_value ("5xx current", "5xx average", $values['ts_5xx']);?>
	<? print_value ("4xx current", "4xx average", $values['ts_4xx']);?>
	<? print_value ("3xx current", "3xx average", $values['ts_3xx']);?>
	<? print_value ("2xx current", "2xx average", $values['ts_2xx']);?>

	<h2 class="swTitle">Stateless Server Statistics</h2>

	<div class="swSMgdval">
		<span class="swSMgval"><em>general values</em></span>
		<span class="swSMdval"><em>diferencial values</em></span>
	</div>
	
	<? print_value ("200 current", "200 average", $values['sl_200']);?>
	<? print_value ("202 current", "202 average", $values['sl_202']);?>
	<? print_value ("2xx current", "2xx average", $values['sl_2xx']);?>
	<br />
	<? print_value ("300 current", "300 average", $values['sl_300']);?>
	<? print_value ("301 current", "301 average", $values['sl_301']);?>
	<? print_value ("302 current", "302 average", $values['sl_302']);?>
	<? print_value ("3xx current", "3xx average", $values['sl_3xx']);?>
	<br />
	<? print_value ("400 current", "400 average", $values['sl_400']);?>
	<? print_value ("401 current", "401 average", $values['sl_401']);?>
	<? print_value ("403 current", "403 average", $values['sl_403']);?>
	<? print_value ("404 current", "404 average", $values['sl_404']);?>
	<? print_value ("407 current", "407 average", $values['sl_407']);?>
	<? print_value ("408 current", "408 average", $values['sl_408']);?>
	<? print_value ("483 current", "483 average", $values['sl_483']);?>
	<? print_value ("4xx current", "4xx average", $values['sl_4xx']);?>
	<br />
	<? print_value ("500 current", "500 average", $values['sl_500']);?>
	<? print_value ("5xx current", "5xx average", $values['sl_5xx']);?>
	<br />
	<? print_value ("6xx current", "6xx average", $values['sl_6xx']);?>
	<br />
	<? print_value ("xxx current", "xxx average", $values['sl_xxx']);?>
	
	<?if (is_array($ul_params)){?>
	<h2 class="swTitle">UsrLoc Stats</h2>

	<div class="swSMgdval">
		<span class="swSMgval"><em>general values</em></span>
		<span class="swSMdval"><em>diferencial values</em></span>
	</div>

	<?foreach($ul_params as $row){?>
	<div class="swSMdomain"><em>domain:</em> <strong><?echo $row;?></strong></div>
	<? print_value ("registered current", "registered average", $values['ul_'.$row.'_reg']);?>
	<? print_value ("expired current", "expired average", $values['ul_'.$row.'_exp']);?>
	<br />
	<?}}?>

<br />
<?print_html_body_end();?>
</html>
<?page_close();?>
