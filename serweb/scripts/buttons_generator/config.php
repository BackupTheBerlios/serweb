<?php
/*
 * $Id: config.php,v 1.3 2006/01/31 11:48:18 kozlik Exp $
 */

/** 
 *	directory where generated images will stored 
 */
$bg_cfg['output_path'] 			= './output/';

/** 
 *	type of genereated images
 *	avaiable values: gif, png, jpeg
 */
$bg_cfg['output_images_type'] 	= 'gif';


/*
 *	Definition of types of buttons
 *	------------------------------
 *
 *	attributes:
 *	font            - file with font used for labels
 *	narrow_font     - file with font used for long labels
 *  font_size		- size of font used for labels
 *  font_color		- color of labels (hexadecimal values RRGGBB)
 *  horizontal_pos  - horizontal position of label (optional)
 *  width           - width of created image
 *	height          - height of created image
 *  image_left      - image with background on left size of button
 *  image_right     - image with background on right size of button
 *  image_center    - image with background on center of button
 */

/* Definition of type 'blue' */
$bg_cfg['btn_type']['blue']['font']				= "arial.ttf";
$bg_cfg['btn_type']['blue']['narrow_font'] 		= "arialn.ttf";
$bg_cfg['btn_type']['blue']['font_size']		= 12;
$bg_cfg['btn_type']['blue']['font_color']		= "000000";
$bg_cfg['btn_type']['blue']['horizontal_pos']	= 13;
$bg_cfg['btn_type']['blue']['width']			= 65;
$bg_cfg['btn_type']['blue']['height']			= 16;
$bg_cfg['btn_type']['blue']['image_left']		= "blue_l.gif";
$bg_cfg['btn_type']['blue']['image_right']		= "blue_r.gif";
$bg_cfg['btn_type']['blue']['image_center']		= "blue_c.gif";

/* Definition of type 'blue_80' */
$bg_cfg['btn_type']['blue_80']					= $bg_cfg['btn_type']['blue'];
$bg_cfg['btn_type']['blue_80']['width']			= 80;

/* Definition of type 'blue_wide' */
$bg_cfg['btn_type']['blue_wide']				= $bg_cfg['btn_type']['blue'];
$bg_cfg['btn_type']['blue_wide']['width']		= 165;

/* Definition of type 'white' */
$bg_cfg['btn_type']['white']					= $bg_cfg['btn_type']['blue'];
$bg_cfg['btn_type']['white']['image_left']		= "white_l.gif";
$bg_cfg['btn_type']['white']['image_right']		= "white_r.gif";
$bg_cfg['btn_type']['white']['image_center']	= "white_c.gif";

/* Definition of type 'white_100' */
$bg_cfg['btn_type']['white_100']				= $bg_cfg['btn_type']['white'];
$bg_cfg['btn_type']['white_100']['width']		= 100;



/*
 *	Definition of buttons
 *	------------------------------
 *
 *	attributes:
 *	filename  - name of file which will be created (without extension)
 *	lang_str  - index into array $lang_str (see files in directory /lang)
 *              containing label of button
 *  type      - index into array $bg_cfg['btn_type']
 */

$bg_cfg['button'][0]['filename']	= 'btn_add';
$bg_cfg['button'][0]['lang_str']	= 'b_add';
$bg_cfg['button'][0]['type']		= 'blue_80';

$bg_cfg['button'][1]['filename']	= 'btn_back';
$bg_cfg['button'][1]['lang_str']	= 'b_back';
$bg_cfg['button'][1]['type']		= 'white_100';

$bg_cfg['button'][2]['filename']	= 'btn_delete_calls';
$bg_cfg['button'][2]['lang_str']	= 'b_delete_calls';
$bg_cfg['button'][2]['type']		= 'blue_wide';

$bg_cfg['button'][3]['filename']	= 'btn_dial_your_voicemail';
$bg_cfg['button'][3]['lang_str']	= 'b_dial_your_voicemail';
$bg_cfg['button'][3]['type']		= 'blue_wide';

$bg_cfg['button'][4]['filename']	= 'btn_download_greeting';
$bg_cfg['button'][4]['lang_str']	= 'b_download_greeting';
$bg_cfg['button'][4]['type']		= 'blue_wide';

$bg_cfg['button'][5]['filename']	= 'btn_edit_items_of_the_list';
$bg_cfg['button'][5]['lang_str']	= 'b_edit_items_of_the_list';
$bg_cfg['button'][5]['type']		= 'blue_wide';

$bg_cfg['button'][6]['filename']	= 'btn_find';
$bg_cfg['button'][6]['lang_str']	= 'b_find';
$bg_cfg['button'][6]['type']		= 'blue';

$bg_cfg['button'][7]['filename']	= 'btn_get_pass';
$bg_cfg['button'][7]['lang_str']	= 'b_forgot_pass_submit';
$bg_cfg['button'][7]['type']		= 'blue_wide';

$bg_cfg['button'][8]['filename']	= 'btn_login';
$bg_cfg['button'][8]['lang_str']	= 'b_login';
$bg_cfg['button'][8]['type']		= 'white';

$bg_cfg['button'][9]['filename']	= 'btn_next';
$bg_cfg['button'][9]['lang_str']	= 'b_next';
$bg_cfg['button'][9]['type']		= 'white';

$bg_cfg['button'][10]['filename']	= 'btn_register';
$bg_cfg['button'][10]['lang_str']	= 'b_register';
$bg_cfg['button'][10]['type']		= 'white_100';

$bg_cfg['button'][11]['filename']	= 'btn_send';
$bg_cfg['button'][11]['lang_str']	= 'b_send';
$bg_cfg['button'][11]['type']		= 'blue';

$bg_cfg['button'][12]['filename']	= 'btn_submit';
$bg_cfg['button'][12]['lang_str']	= 'b_submit';
$bg_cfg['button'][12]['type']		= 'blue_80';

$bg_cfg['button'][13]['filename']	= 'btn_test_firewall_NAT';
$bg_cfg['button'][13]['lang_str']	= 'b_test_firewall_NAT';
$bg_cfg['button'][13]['type']		= 'blue_wide';

$bg_cfg['button'][14]['filename']	= 'btn_upload_greeting';
$bg_cfg['button'][14]['lang_str']	= 'b_upload_greeting';
$bg_cfg['button'][14]['type']		= 'blue_wide';

$bg_cfg['button'][15]['filename']	= 'btn_select';
$bg_cfg['button'][15]['lang_str']	= 'b_select';
$bg_cfg['button'][15]['type']		= 'blue';



?>
