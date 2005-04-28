<?php
/*
 * Application unit server monitoring
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_ser_moni.php,v 1.1 2005/04/28 15:13:06 kozlik Exp $
 * @package   serweb
 */ 

/* Application unit server monitoring 
 *
 *
 *	This application unit is used for displaying values from ser_moni table
 *	   
 *	Configuration:
 *	--------------
 *	
 *	
 *	'smarty_action'				name of smarty variable - see below
 *	'smarty_values'				name of smarty variable - see below
 *	'smarty_values_html'		name of smarty variable - see below
 *	'smarty_ul_params'			name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_action']			(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	
 *	opt['smarty_values']		(values)
 *	
 *	opt['smarty_values_html']	(values_html)
 *	
 *	opt['smarty_ul_params']		(ul_params)
 *	
 *	
 *	
 */

class apu_ser_moni extends apu_base_class{
	var $smarty_action='default';
	var $values = array();
	var $ul_params = array();


	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_ser_moni_values');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_ser_moni(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['smarty_values'] = 'values';
		$this->opt['smarty_values_html'] = 'values_html';
		$this->opt['smarty_ul_params'] = 'ul_params';

		
		/*** names of variables assigned to smarty ***/
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		
		
	}

	function action_default(&$errors){
		global $data, $lang_str;
		
		//get values from database
		if (false === $this->values = $data->get_ser_moni_values($errors)) return false;
	
		//create list of usrloc stats
		foreach($this->values as $row){
			if (substr($row->param, 0, 3) == "ul_" and substr($row->param, -4) == "_reg")
				$this->ul_params[]=substr($row->param, 3, -4);
		}

		//generate html of stats to associative array
		
		if (isset($this->values['ts_current']))     $this->values_html['ts_current']     = sm_get_value ($lang_str['ser_moni_current'], $lang_str['ser_moni_average'], $this->values['ts_current']);
		if (isset($this->values['ts_waiting']))     $this->values_html['ts_waiting']     = sm_get_value ($lang_str['ser_moni_waiting_cur'], $lang_str['ser_moni_waiting_avg'], $this->values['ts_waiting']);
		if (isset($this->values['ts_total']))       $this->values_html['ts_total']       = sm_get_value ($lang_str['ser_moni_total_cur'], $lang_str['ser_moni_total_avg'], $this->values['ts_total']);
		if (isset($this->values['ts_total_local'])) $this->values_html['ts_total_local'] = sm_get_value ($lang_str['ser_moni_local_cur'], $lang_str['ser_moni_local_avg'], $this->values['ts_total_local']);
		if (isset($this->values['ts_replied']))     $this->values_html['ts_replied']     = sm_get_value ($lang_str['ser_moni_replies_cur'], $lang_str['ser_moni_replies_avg'], $this->values['ts_replied']);
		
		if (isset($this->values['ts_6xx'])) $this->values_html['ts_6xx'] = sm_get_value ("6xx ".$lang_str['ser_moni_current'], "6xx ".$lang_str['ser_moni_average'], $this->values['ts_6xx']);
		if (isset($this->values['ts_5xx'])) $this->values_html['ts_5xx'] = sm_get_value ("5xx ".$lang_str['ser_moni_current'], "5xx ".$lang_str['ser_moni_average'], $this->values['ts_5xx']);
		if (isset($this->values['ts_4xx'])) $this->values_html['ts_4xx'] = sm_get_value ("4xx ".$lang_str['ser_moni_current'], "4xx ".$lang_str['ser_moni_average'], $this->values['ts_4xx']);
		if (isset($this->values['ts_3xx'])) $this->values_html['ts_3xx'] = sm_get_value ("3xx ".$lang_str['ser_moni_current'], "3xx ".$lang_str['ser_moni_average'], $this->values['ts_3xx']);
		if (isset($this->values['ts_2xx'])) $this->values_html['ts_2xx'] = sm_get_value ("2xx ".$lang_str['ser_moni_current'], "2xx ".$lang_str['ser_moni_average'], $this->values['ts_2xx']);
		
		if (isset($this->values['sl_200'])) $this->values_html['sl_200'] = sm_get_value ("200 ".$lang_str['ser_moni_current'], "200 ".$lang_str['ser_moni_average'], $this->values['sl_200']);
		if (isset($this->values['sl_202'])) $this->values_html['sl_202'] = sm_get_value ("202 ".$lang_str['ser_moni_current'], "202 ".$lang_str['ser_moni_average'], $this->values['sl_202']);
		if (isset($this->values['sl_2xx'])) $this->values_html['sl_2xx'] = sm_get_value ("2xx ".$lang_str['ser_moni_current'], "2xx ".$lang_str['ser_moni_average'], $this->values['sl_2xx']);
		                                                                                                                                                        
		if (isset($this->values['sl_300'])) $this->values_html['sl_300'] = sm_get_value ("300 ".$lang_str['ser_moni_current'], "300 ".$lang_str['ser_moni_average'], $this->values['sl_300']);
		if (isset($this->values['sl_301'])) $this->values_html['sl_301'] = sm_get_value ("301 ".$lang_str['ser_moni_current'], "301 ".$lang_str['ser_moni_average'], $this->values['sl_301']);
		if (isset($this->values['sl_302'])) $this->values_html['sl_302'] = sm_get_value ("302 ".$lang_str['ser_moni_current'], "302 ".$lang_str['ser_moni_average'], $this->values['sl_302']);
		if (isset($this->values['sl_3xx'])) $this->values_html['sl_3xx'] = sm_get_value ("3xx ".$lang_str['ser_moni_current'], "3xx ".$lang_str['ser_moni_average'], $this->values['sl_3xx']);
		                                                                                                                                                        
		if (isset($this->values['sl_400'])) $this->values_html['sl_400'] = sm_get_value ("400 ".$lang_str['ser_moni_current'], "400 ".$lang_str['ser_moni_average'], $this->values['sl_400']);
		if (isset($this->values['sl_401'])) $this->values_html['sl_401'] = sm_get_value ("401 ".$lang_str['ser_moni_current'], "401 ".$lang_str['ser_moni_average'], $this->values['sl_401']);
		if (isset($this->values['sl_403'])) $this->values_html['sl_403'] = sm_get_value ("403 ".$lang_str['ser_moni_current'], "403 ".$lang_str['ser_moni_average'], $this->values['sl_403']);
		if (isset($this->values['sl_404'])) $this->values_html['sl_404'] = sm_get_value ("404 ".$lang_str['ser_moni_current'], "404 ".$lang_str['ser_moni_average'], $this->values['sl_404']);
		if (isset($this->values['sl_407'])) $this->values_html['sl_407'] = sm_get_value ("407 ".$lang_str['ser_moni_current'], "407 ".$lang_str['ser_moni_average'], $this->values['sl_407']);
		if (isset($this->values['sl_408'])) $this->values_html['sl_408'] = sm_get_value ("408 ".$lang_str['ser_moni_current'], "408 ".$lang_str['ser_moni_average'], $this->values['sl_408']);
		if (isset($this->values['sl_483'])) $this->values_html['sl_483'] = sm_get_value ("483 ".$lang_str['ser_moni_current'], "483 ".$lang_str['ser_moni_average'], $this->values['sl_483']);
		if (isset($this->values['sl_4xx'])) $this->values_html['sl_4xx'] = sm_get_value ("4xx ".$lang_str['ser_moni_current'], "4xx ".$lang_str['ser_moni_average'], $this->values['sl_4xx']);
		                                                                                                                                                        
		if (isset($this->values['sl_500'])) $this->values_html['sl_500'] = sm_get_value ("500 ".$lang_str['ser_moni_current'], "500 ".$lang_str['ser_moni_average'], $this->values['sl_500']);
		if (isset($this->values['sl_5xx'])) $this->values_html['sl_5xx'] = sm_get_value ("5xx ".$lang_str['ser_moni_current'], "5xx ".$lang_str['ser_moni_average'], $this->values['sl_5xx']);
		
		if (isset($this->values['sl_6xx'])) $this->values_html['sl_6xx'] = sm_get_value ("6xx ".$lang_str['ser_moni_current'], "6xx ".$lang_str['ser_moni_average'], $this->values['sl_6xx']);
		
		if (isset($this->values['sl_xxx'])) $this->values_html['sl_xxx'] = sm_get_value ("xxx ".$lang_str['ser_moni_current'], "xxx ".$lang_str['ser_moni_average'], $this->values['sl_xxx']);
		
		foreach($this->ul_params as $row){
			if (isset($this->values['ul_'.$row.'_reg']))  $this->values_html['ul_'.$row.'_reg'] = sm_get_value ($lang_str['ser_moni_registered_cur'], $lang_str['ser_moni_registered_avg'], $this->values['ul_'.$row.'_reg']);
			if (isset($this->values['ul_'.$row.'_exp']))  $this->values_html['ul_'.$row.'_exp'] = sm_get_value ($lang_str['ser_moni_expired_cur'], $lang_str['ser_moni_expired_avg'], $this->values['ul_'.$row.'_exp']);
		}


		return true;
	}
	
	/* this metod is called always at begining */
	function init(){
		parent::init();
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		$this->action=array('action'=>"default",
		                    'validate_form'=>false,
		                    'reload'=>false);
	}
	

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_values'], $this->values);
		$smarty->assign_by_ref($this->opt['smarty_values_html'], $this->values_html);
		$smarty->assign_by_ref($this->opt['smarty_ul_params'], $this->ul_params);
	}




	function sm_get_bar($val, $min, $max, $type){
		global $config;
		
		if ($val <= $min) $width=0;					//for safety
		else if ($val >= $max) $width=100;
		else $width=round(100*($val-$min)/($max-$min));
	
		if ($type=='cur'){
			return '<div class="swBarCurrentBorder"><div class="swBarCurrent" style="width: '.$width.'%"></div></div>';
		}else{
			return '<div class="swBarAverageBorder"><div class="swBarAverage" style="width: '.$width.'%"></div></div>';
		}
	
	}
	
	function sm_corect_bounds(&$min, &$max){
		if ($min==$max){
			$min=floor($min*0.9);	//min = min - 10%
			$max=ceil($max*1.1);	//max = max + 10%
		}
	}
	
	function sm_get_precision($val){
		if ($val<10) return 2;
		if ($val<100) return 1;
		return 0;
	
	}
	
	function sm_get_value($name_c, $name_a, $value){
		global $config;
	
		$min=$value->min_val;
		$max=$value->max_val;
		$val=$value->lv;
		$val_a=$value->av;
	
		$min_i=$value->min_inc;
		$max_i=$value->max_inc;
		$val_i=$value->mv;
		$val_a_i=$value->ad;
		
		sm_corect_bounds($min, $max);
		sm_corect_bounds($min_i, $max_i);
		
		$out=
		'<div class="swSMOneStat">
			<div class="swSMVal">
			<strong>'.$name_c.'</strong><br />
			<span class="swSMStatValue">'.$val.'</span>
			<span class="swSMStatMinVal"><em>&lt;</em>'.$min.'</span><em>;</em><span class="swSMStatMaxVal">'.$max.'<em>&gt;</em></span><br/>
			<span class="swSMStatValueI">'.$val_i.'</span>
			<span class="swSMStatMinValI"><em>&lt;</em>'.$min_i.'</span><em>;</em><span class="swSMStatMaxValI">'.$max_i.'<em>&gt;</em></span><br />
			<span class="swSMbar">'.sm_get_bar($val, $min, $max, "cur").'</span>		
			<span class="swSMbarI">'.sm_get_bar($val_i, $min_i, $max_i, "cur").'</span>		
			</div>
			<div class="swSMValA">
			<strong>'.$name_a.'</strong><br />
			<span class="swSMStatValue">'.$val_a.'</span><br />
			<span class="swSMStatValueI">'.$val_a_i.'</span>
			<span class="swSMbar">'.sm_get_bar($val_a, $min, $max, "avg").'</span>		
			<span class="swSMbarI">'.sm_get_bar($val_a_i, $min_i, $max_i, "avg").'</span>		
			</div>
			<hr>
		</div>';
	
		return $out;
	}
	
}


?>
