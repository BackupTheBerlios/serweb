<?
/*
 * $Id: ser_moni.php,v 1.7 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";
require "ser_moni_funct.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

$values=array();
do{
	$values=array();
	$ul_params=array();

	if (!$data = CData_Layer::create($errors)) break;
	
	//get values from database
	if (false === $values = $data->get_ser_moni_values($errors)) break;
	
	//create list of usrloc stats
	foreach($values as $row){
		if (substr($row->param, 0, 3) == "ul_" and substr($row->param, -4) == "_reg")
			$ul_params[]=substr($row->param, 3, -4);
	}

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
	<? if (isset($values['ts_current'])) 		print_value ("current", "average", $values['ts_current']);?>
	<? if (isset($values['ts_waiting'])) 		print_value ("waiting current", "waiting average", $values['ts_waiting']);?>
	<? if (isset($values['ts_total'])) 			print_value ("total current", "total average", $values['ts_total']);?>
	<? if (isset($values['ts_total_local']))	print_value ("local current", "local average", $values['ts_total_local']);?>
	<br/>
	<? if (isset($values['ts_replied']))		print_value ("replied localy current", "replied localy average", $values['ts_replied']);?>
	<br/>

	<h2 class="swTitle">Completion status</h2>

	<div class="swSMgdval">
		<span class="swSMgval"><em>general values</em></span>
		<span class="swSMdval"><em>diferencial values</em></span>
	</div>
	<? if (isset($values['ts_6xx']))	print_value ("6xx current", "6xx average", $values['ts_6xx']);?>
	<? if (isset($values['ts_5xx']))	print_value ("5xx current", "5xx average", $values['ts_5xx']);?>
	<? if (isset($values['ts_4xx']))	print_value ("4xx current", "4xx average", $values['ts_4xx']);?>
	<? if (isset($values['ts_3xx']))	print_value ("3xx current", "3xx average", $values['ts_3xx']);?>
	<? if (isset($values['ts_2xx']))	print_value ("2xx current", "2xx average", $values['ts_2xx']);?>

	<h2 class="swTitle">Stateless Server Statistics</h2>

	<div class="swSMgdval">
		<span class="swSMgval"><em>general values</em></span>
		<span class="swSMdval"><em>diferencial values</em></span>
	</div>
	
	<? if (isset($values['sl_200']))	print_value ("200 current", "200 average", $values['sl_200']);?>
	<? if (isset($values['sl_202']))	print_value ("202 current", "202 average", $values['sl_202']);?>
	<? if (isset($values['sl_2xx']))	print_value ("2xx current", "2xx average", $values['sl_2xx']);?>
	<br />
	<? if (isset($values['sl_300']))	print_value ("300 current", "300 average", $values['sl_300']);?>
	<? if (isset($values['sl_301']))	print_value ("301 current", "301 average", $values['sl_301']);?>
	<? if (isset($values['sl_302']))	print_value ("302 current", "302 average", $values['sl_302']);?>
	<? if (isset($values['sl_3xx']))	print_value ("3xx current", "3xx average", $values['sl_3xx']);?>
	<br />
	<? if (isset($values['sl_400']))	print_value ("400 current", "400 average", $values['sl_400']);?>
	<? if (isset($values['sl_401']))	print_value ("401 current", "401 average", $values['sl_401']);?>
	<? if (isset($values['sl_403']))	print_value ("403 current", "403 average", $values['sl_403']);?>
	<? if (isset($values['sl_404']))	print_value ("404 current", "404 average", $values['sl_404']);?>
	<? if (isset($values['sl_407']))	print_value ("407 current", "407 average", $values['sl_407']);?>
	<? if (isset($values['sl_408']))	print_value ("408 current", "408 average", $values['sl_408']);?>
	<? if (isset($values['sl_483']))	print_value ("483 current", "483 average", $values['sl_483']);?>
	<? if (isset($values['sl_4xx']))	print_value ("4xx current", "4xx average", $values['sl_4xx']);?>
	<br />
	<? if (isset($values['sl_500']))	print_value ("500 current", "500 average", $values['sl_500']);?>
	<? if (isset($values['sl_5xx']))	print_value ("5xx current", "5xx average", $values['sl_5xx']);?>
	<br />
	<? if (isset($values['sl_6xx']))	print_value ("6xx current", "6xx average", $values['sl_6xx']);?>
	<br />
	<? if (isset($values['sl_xxx']))	print_value ("xxx current", "xxx average", $values['sl_xxx']);?>
	
	<?if (isset($ul_params) and is_array($ul_params)){?>
	<h2 class="swTitle">UsrLoc Stats</h2>

	<div class="swSMgdval">
		<span class="swSMgval"><em>general values</em></span>
		<span class="swSMdval"><em>diferencial values</em></span>
	</div>

	<?foreach($ul_params as $row){?>
	<div class="swSMdomain"><em>domain:</em> <strong><?echo $row;?></strong></div>
	<? if (isset($values['ul_'.$row.'_reg']))	print_value ("registered current", "registered average", $values['ul_'.$row.'_reg']);?>
	<? if (isset($values['ul_'.$row.'_exp']))	print_value ("expired current", "expired average", $values['ul_'.$row.'_exp']);?>
	<br />
	<?}}?>

<br />
<?print_html_body_end();?>
</html>
<?page_close();?>
