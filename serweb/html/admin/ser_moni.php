<?
/*
 * $Id: ser_moni.php,v 1.8 2004/08/09 12:21:27 kozlik Exp $
 */

$_data_layer_required_methods=array('get_ser_moni_values');

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

	//get values from database
	if (false === $values = $data->get_ser_moni_values($errors)) break;
	
	//create list of usrloc stats
	foreach($values as $row){
		if (substr($row->param, 0, 3) == "ul_" and substr($row->param, -4) == "_reg")
			$ul_params[]=substr($row->param, 3, -4);
	}

}while (false);

//generate html of stats to associative array

if (isset($values['ts_current']))     $values_html['ts_current']     = sm_get_value ("current", "average", $values['ts_current']);
if (isset($values['ts_waiting']))     $values_html['ts_waiting']     = sm_get_value ("waiting current", "waiting average", $values['ts_waiting']);
if (isset($values['ts_total']))       $values_html['ts_total']       = sm_get_value ("total current", "total average", $values['ts_total']);
if (isset($values['ts_total_local'])) $values_html['ts_total_local'] = sm_get_value ("local current", "local average", $values['ts_total_local']);
if (isset($values['ts_replied']))     $values_html['ts_replied']     = sm_get_value ("replied localy current", "replied localy average", $values['ts_replied']);

if (isset($values['ts_6xx'])) $values_html['ts_6xx'] = sm_get_value ("6xx current", "6xx average", $values['ts_6xx']);
if (isset($values['ts_5xx'])) $values_html['ts_5xx'] = sm_get_value ("5xx current", "5xx average", $values['ts_5xx']);
if (isset($values['ts_4xx'])) $values_html['ts_4xx'] = sm_get_value ("4xx current", "4xx average", $values['ts_4xx']);
if (isset($values['ts_3xx'])) $values_html['ts_3xx'] = sm_get_value ("3xx current", "3xx average", $values['ts_3xx']);
if (isset($values['ts_2xx'])) $values_html['ts_2xx'] = sm_get_value ("2xx current", "2xx average", $values['ts_2xx']);

if (isset($values['sl_200'])) $values_html['sl_200'] = sm_get_value ("200 current", "200 average", $values['sl_200']);
if (isset($values['sl_202'])) $values_html['sl_202'] = sm_get_value ("202 current", "202 average", $values['sl_202']);
if (isset($values['sl_2xx'])) $values_html['sl_2xx'] = sm_get_value ("2xx current", "2xx average", $values['sl_2xx']);

if (isset($values['sl_300'])) $values_html['sl_300'] = sm_get_value ("300 current", "300 average", $values['sl_300']);
if (isset($values['sl_301'])) $values_html['sl_301'] = sm_get_value ("301 current", "301 average", $values['sl_301']);
if (isset($values['sl_302'])) $values_html['sl_302'] = sm_get_value ("302 current", "302 average", $values['sl_302']);
if (isset($values['sl_3xx'])) $values_html['sl_3xx'] = sm_get_value ("3xx current", "3xx average", $values['sl_3xx']);

if (isset($values['sl_400'])) $values_html['sl_400'] = sm_get_value ("400 current", "400 average", $values['sl_400']);
if (isset($values['sl_401'])) $values_html['sl_401'] = sm_get_value ("401 current", "401 average", $values['sl_401']);
if (isset($values['sl_403'])) $values_html['sl_403'] = sm_get_value ("403 current", "403 average", $values['sl_403']);
if (isset($values['sl_404'])) $values_html['sl_404'] = sm_get_value ("404 current", "404 average", $values['sl_404']);
if (isset($values['sl_407'])) $values_html['sl_407'] = sm_get_value ("407 current", "407 average", $values['sl_407']);
if (isset($values['sl_408'])) $values_html['sl_408'] = sm_get_value ("408 current", "408 average", $values['sl_408']);
if (isset($values['sl_483'])) $values_html['sl_483'] = sm_get_value ("483 current", "483 average", $values['sl_483']);
if (isset($values['sl_4xx'])) $values_html['sl_4xx'] = sm_get_value ("4xx current", "4xx average", $values['sl_4xx']);

if (isset($values['sl_500'])) $values_html['sl_500'] = sm_get_value ("500 current", "500 average", $values['sl_500']);
if (isset($values['sl_5xx'])) $values_html['sl_5xx'] = sm_get_value ("5xx current", "5xx average", $values['sl_5xx']);

if (isset($values['sl_6xx'])) $values_html['sl_6xx'] = sm_get_value ("6xx current", "6xx average", $values['sl_6xx']);

if (isset($values['sl_xxx'])) $values_html['sl_xxx'] = sm_get_value ("xxx current", "xxx average", $values['sl_xxx']);

foreach($ul_params as $row){
	if (isset($values['ul_'.$row.'_reg']))  $values_html['ul_'.$row.'_reg'] = sm_get_value ("registered current", "registered average", $values['ul_'.$row.'_reg']);
	if (isset($values['ul_'.$row.'_exp']))  $values_html['ul_'.$row.'_exp'] = sm_get_value ("expired current", "expired average", $values['ul_'.$row.'_exp']);
}


/* ----------------------- HTML begin ---------------------- */
print_html_head();
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$smarty->assign_by_ref('parameters', $page_attributes);

$smarty->assign_by_ref('values', $values);
$smarty->assign_by_ref('values_html', $values_html);
$smarty->assign_by_ref('ul_params', $ul_params);

$smarty->display('a_ser_moni.tpl');
	 
?>
<?print_html_body_end();?>
</html>
<?page_close();?>
