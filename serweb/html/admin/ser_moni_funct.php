<?
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
?>