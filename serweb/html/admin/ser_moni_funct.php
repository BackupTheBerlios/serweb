<?
function print_bar($val, $min, $max, $type){
	global $config;
	
	if ($val <= $min) $width=0;					//for safety
	else if ($val >= $max) $width=100;
	else $width=round(100*($val-$min)/($max-$min));

	if ($type=='cur'){
?><div class="swBarCurrentBorder"><div class="swBarCurrent" style="width: <?echo $width;?>%"></div></div><?
	}else{
?><div class="swBarAverageBorder"><div class="swBarAverage" style="width: <?echo $width;?>%"></div></div><?
	}

}

function corect_bounds(&$min, &$max){
	if ($min==$max){
		$min=floor($min*0.9);	//min = min - 10%
		$max=ceil($max*1.1);	//max = max + 10%
	}
}

function get_precision($val){
	if ($val<10) return 2;
	if ($val<100) return 1;
	return 0;

}

function print_value($name_c, $name_a, $value){
	global $config;

	$min=$value->min_val;
	$max=$value->max_val;
	$val=$value->lv;
	$val_a=$value->av;

	$min_i=$value->min_inc;
	$max_i=$value->max_inc;
	$val_i=$value->mv;
	$val_a_i=$value->ad;
	
	corect_bounds($min, $max);
	corect_bounds($min_i, $max_i);
?>
	<div class="swSMOneStat">
		<div class="swSMVal">
		<strong><?echo $name_c;?></strong><br />
		<span class="swSMStatValue"><?echo $val;?></span>
		<span class="swSMStatMinVal"><em>&lt;</em><?echo $min;?></span><em>;</em><span class="swSMStatMaxVal"><?echo $max;?><em>&gt;</em></span><br/>
		<span class="swSMStatValueI"><?echo $val_i;?></span>
		<span class="swSMStatMinValI"><em>&lt;</em><?echo $min_i;?></span><em>;</em><span class="swSMStatMaxValI"><?echo $max_i;?><em>&gt;</em></span><br />
		<span class="swSMbar"><?print_bar($val, $min, $max, "cur");?></span>		
		<span class="swSMbarI"><?print_bar($val_i, $min_i, $max_i, "cur");?></span>		
		</div>
		<div class="swSMValA">
		<strong><?echo $name_a;?></strong><br />
		<span class="swSMStatValue"><?echo $val_a;?></span><br />
		<span class="swSMStatValueI"><?echo $val_a_i;?></span>
		<span class="swSMbar"><?print_bar($val_a, $min, $max, "avg");?></span>		
		<span class="swSMbarI"><?print_bar($val_a_i, $min_i, $max_i, "avg");?></span>		
		</div>
		<hr>
	</div>
<?
}
?>