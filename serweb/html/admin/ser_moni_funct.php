<?
function print_bar($val, $min, $max, $image){
	global $config;
	
	$bar_width=150;

	if ($val <= $min) $width=0;					//for safety
	else if ($val >= $max) $width=$bar_width;
	else $width=round($bar_width*($val-$min)/($max-$min));
?>
<table border="0" cellspacing="0" cellpadding="0">
<tr><td colspan="3" height="1"><img src="<? echo $config->img_src_path.$image ?>" width="<? echo $bar_width+2; ?>" height="1" border="0" alt=""></td></tr>
<tr>
	<td width="1" height="5"><img src="<? echo $config->img_src_path.$image ?>" width="1" height="5" border="0" alt=""></td>
	<td width="150" height="5" align="left"><img src="<? echo $config->img_src_path.$image ?>" width="<? echo $width; ?>" height="5" border="0" alt="<? echo $val; ?>"></td>
	<td width="1" height="5"><img src="<? echo $config->img_src_path.$image ?>" width="1" height="5" border="0" alt=""></td>
</tr>
<tr><td colspan="3" height="1"><img src="<? echo $config->img_src_path.$image ?>" width="<? echo $bar_width+2; ?>" height="1" border="0" alt=""></td></tr>
</table>
<?
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
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="<? echo $config->img_src_path."invisible.gif" ?>" width="150" height="1" border="0" alt=""></td>
		<td align="left" class="f12"><?echo $min;?></td>
		<td align="right" class="f12"><?echo $max;?></td>
		<td ><img src="<? echo $config->img_src_path."invisible.gif" ?>" width="60" height="1" border="0" alt=""></td>
		<td ><img src="<? echo $config->img_src_path."invisible.gif" ?>" width="10" height="1" border="0" alt=""></td>
		<td align="left" class="f12"><?echo $min_i;?></td>
		<td align="right" class="f12"><?echo $max_i;?></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="f14b"><?echo $name_c;?></td>
		<td colspan=2><?print_bar($val, $min, $max, "blue_pixel.gif");?></td>
		<td class="f14b">&nbsp;<?echo $val;?></td>
		<td width="10">&nbsp;</td>
		<td colspan=2><?print_bar($val_i, $min_i, $max_i, "blue_pixel.gif");?></td>
		<td class="f14b">&nbsp;<?echo $val_i;?></td>
	</tr>
	<tr>
		<td class="f14b"><?echo $name_a;?></td>
		<td colspan=2><?print_bar($val_a, $min, $max, "red_pixel.gif");?></td>
		<td class="f14b">&nbsp;<?echo round($val_a, get_precision($val_a));?></td>
		<td width="10">&nbsp;</td>
		<td colspan=2><?print_bar($val_a_i, $min_i, $max_i, "red_pixel.gif");?></td>
		<td class="f14b">&nbsp;<?echo round($val_a_i, get_precision($val_a_i));?></td>
	</tr>
	</table>
<?
}
?>