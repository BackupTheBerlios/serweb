<?
/*
 * $Id: ser_moni.php,v 1.10 2004/11/10 13:13:06 kozlik Exp $
 */

$_data_layer_required_methods=array('get_ser_moni_values');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

require "prepend.php";
require "ser_moni_funct.php";

$perm->check("admin");
$errors = array();

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

if (isset($values['ts_current']))     $values_html['ts_current']     = sm_get_value ($lang_str['ser_moni_current'], $lang_str['ser_moni_average'], $values['ts_current']);
if (isset($values['ts_waiting']))     $values_html['ts_waiting']     = sm_get_value ($lang_str['ser_moni_waiting_cur'], $lang_str['ser_moni_waiting_avg'], $values['ts_waiting']);
if (isset($values['ts_total']))       $values_html['ts_total']       = sm_get_value ($lang_str['ser_moni_total_cur'], $lang_str['ser_moni_total_avg'], $values['ts_total']);
if (isset($values['ts_total_local'])) $values_html['ts_total_local'] = sm_get_value ($lang_str['ser_moni_local_cur'], $lang_str['ser_moni_local_avg'], $values['ts_total_local']);
if (isset($values['ts_replied']))     $values_html['ts_replied']     = sm_get_value ($lang_str['ser_moni_replies_cur'], $lang_str['ser_moni_replies_avg'], $values['ts_replied']);

if (isset($values['ts_6xx'])) $values_html['ts_6xx'] = sm_get_value ("6xx ".$lang_str['ser_moni_current'], "6xx ".$lang_str['ser_moni_average'], $values['ts_6xx']);
if (isset($values['ts_5xx'])) $values_html['ts_5xx'] = sm_get_value ("5xx ".$lang_str['ser_moni_current'], "5xx ".$lang_str['ser_moni_average'], $values['ts_5xx']);
if (isset($values['ts_4xx'])) $values_html['ts_4xx'] = sm_get_value ("4xx ".$lang_str['ser_moni_current'], "4xx ".$lang_str['ser_moni_average'], $values['ts_4xx']);
if (isset($values['ts_3xx'])) $values_html['ts_3xx'] = sm_get_value ("3xx ".$lang_str['ser_moni_current'], "3xx ".$lang_str['ser_moni_average'], $values['ts_3xx']);
if (isset($values['ts_2xx'])) $values_html['ts_2xx'] = sm_get_value ("2xx ".$lang_str['ser_moni_current'], "2xx ".$lang_str['ser_moni_average'], $values['ts_2xx']);

if (isset($values['sl_200'])) $values_html['sl_200'] = sm_get_value ("200 ".$lang_str['ser_moni_current'], "200 ".$lang_str['ser_moni_average'], $values['sl_200']);
if (isset($values['sl_202'])) $values_html['sl_202'] = sm_get_value ("202 ".$lang_str['ser_moni_current'], "202 ".$lang_str['ser_moni_average'], $values['sl_202']);
if (isset($values['sl_2xx'])) $values_html['sl_2xx'] = sm_get_value ("2xx ".$lang_str['ser_moni_current'], "2xx ".$lang_str['ser_moni_average'], $values['sl_2xx']);

if (isset($values['sl_300'])) $values_html['sl_300'] = sm_get_value ("300 ".$lang_str['ser_moni_current'], "300 ".$lang_str['ser_moni_average'], $values['sl_300']);
if (isset($values['sl_301'])) $values_html['sl_301'] = sm_get_value ("301 ".$lang_str['ser_moni_current'], "301 ".$lang_str['ser_moni_average'], $values['sl_301']);
if (isset($values['sl_302'])) $values_html['sl_302'] = sm_get_value ("302 ".$lang_str['ser_moni_current'], "302 ".$lang_str['ser_moni_average'], $values['sl_302']);
if (isset($values['sl_3xx'])) $values_html['sl_3xx'] = sm_get_value ("3xx ".$lang_str['ser_moni_current'], "3xx ".$lang_str['ser_moni_average'], $values['sl_3xx']);

if (isset($values['sl_400'])) $values_html['sl_400'] = sm_get_value ("400 ".$lang_str['ser_moni_current'], "400 ".$lang_str['ser_moni_average'], $values['sl_400']);
if (isset($values['sl_401'])) $values_html['sl_401'] = sm_get_value ("401 ".$lang_str['ser_moni_current'], "401 ".$lang_str['ser_moni_average'], $values['sl_401']);
if (isset($values['sl_403'])) $values_html['sl_403'] = sm_get_value ("403 ".$lang_str['ser_moni_current'], "403 ".$lang_str['ser_moni_average'], $values['sl_403']);
if (isset($values['sl_404'])) $values_html['sl_404'] = sm_get_value ("404 ".$lang_str['ser_moni_current'], "404 ".$lang_str['ser_moni_average'], $values['sl_404']);
if (isset($values['sl_407'])) $values_html['sl_407'] = sm_get_value ("407 ".$lang_str['ser_moni_current'], "407 ".$lang_str['ser_moni_average'], $values['sl_407']);
if (isset($values['sl_408'])) $values_html['sl_408'] = sm_get_value ("408 ".$lang_str['ser_moni_current'], "408 ".$lang_str['ser_moni_average'], $values['sl_408']);
if (isset($values['sl_483'])) $values_html['sl_483'] = sm_get_value ("483 ".$lang_str['ser_moni_current'], "483 ".$lang_str['ser_moni_average'], $values['sl_483']);
if (isset($values['sl_4xx'])) $values_html['sl_4xx'] = sm_get_value ("4xx ".$lang_str['ser_moni_current'], "4xx ".$lang_str['ser_moni_average'], $values['sl_4xx']);

if (isset($values['sl_500'])) $values_html['sl_500'] = sm_get_value ("500 ".$lang_str['ser_moni_current'], "500 ".$lang_str['ser_moni_average'], $values['sl_500']);
if (isset($values['sl_5xx'])) $values_html['sl_5xx'] = sm_get_value ("5xx ".$lang_str['ser_moni_current'], "5xx ".$lang_str['ser_moni_average'], $values['sl_5xx']);

if (isset($values['sl_6xx'])) $values_html['sl_6xx'] = sm_get_value ("6xx ".$lang_str['ser_moni_current'], "6xx ".$lang_str['ser_moni_average'], $values['sl_6xx']);

if (isset($values['sl_xxx'])) $values_html['sl_xxx'] = sm_get_value ("xxx ".$lang_str['ser_moni_current'], "xxx ".$lang_str['ser_moni_average'], $values['sl_xxx']);

foreach($ul_params as $row){
	if (isset($values['ul_'.$row.'_reg']))  $values_html['ul_'.$row.'_reg'] = sm_get_value ($lang_str['ser_moni_registered_cur'], $lang_str['ser_moni_registered_avg'], $values['ul_'.$row.'_reg']);
	if (isset($values['ul_'.$row.'_exp']))  $values_html['ul_'.$row.'_exp'] = sm_get_value ($lang_str['ser_moni_expired_cur'], $lang_str['ser_moni_expired_avg'], $values['ul_'.$row.'_exp']);
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

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('a_ser_moni.tpl');

?>
<?print_html_body_end();?>
</html>
<?page_close();?>
