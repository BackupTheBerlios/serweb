<?
/*
 * $Id: page.php,v 1.21 2004/03/09 12:01:08 kozlik Exp $
 */

	function put_headers(){
		Header("Pragma:  no-cache");
		Header("Cache-Control: no-cache");
		Header("Expires: ".GMDate("D, d M Y H:i:s")." GMT");
	}
	
	function print_html_head(){
		global $config;	?>
	<meta http-equiv="Content-Type" content="text/html; charset=<?echo $config->charset;?>">
	<meta name="Author" content="Karel Kozlik <kozlik@kufr.cz>">
	<meta http-equiv="PRAGMA" content="no-cache"> 
	<meta http-equiv="Cache-control" content="no-cache">
	<meta http-equiv="Expires" content="<?echo GMDate("D, d M Y H:i:s")." GMT";?>"> 

	<LINK REL="StyleSheet" HREF="<?echo $config->style_src_path;?>iptel_styles.css" TYPE="text/css">
	<style>
	all.clsMenuItemNS, .clsMenuItemIE{text-decoration: none; font: bold 10px Verdana; color: black; cursor: hand; z-index:100}
	#MainTable A:hover {color: white;}
	</style>

	<script language="JavaScript">
	var keepstatic=1 //specify whether menu should stay static (works only in IE4+)
	var menucolor="#75C5F0" //specify menu color
	var submenuwidth=150 //specify sub menus' color
	</script>

	<script language="JavaScript" fptype="dynamicanimation">
	<!--
	function dynAnimation() {}
	function clickSwapImg() {}
	//-->
	</script>
	<!--
	<script language="JavaScript" src="/preload.js"></script>
	-->
<?	}

	$_page_tab=0;

	function print_html_body_begin($tab=false, $target_blank=false, $show_logout=false, $user_name=""){
		global $config, $_page_tab, $sess;

		virtual($config->prolog);
		echo $config->realm." User Management";
		virtual($config->separator);

		if ($user_name){?>
			<div class="f12">
				<table width="100%">
				<tr><td align="left"><?echo $user_name;?></td>
				<td align="right">&nbsp;<a href=logout.php>Logout</a></td>
				<td align="right">&nbsp;<a href=http://iptel.org/cgi-bin/fom?file=73>FAQ</a></td>
				</tr>
				</table>
			</div>
			<br>
		<?}
		
		if ($tab) { print_tabs($tab); $_page_tab=1;?>	

 		<table bgcolor="#B1C9DC" width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr><td>
			<table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr valign="top"><td>
		<?} //endif

	}

	function print_admin_html_body_begin($tab_selected=null){
		global $config, $_page_tab, $sess, $perm;

		virtual($config->prolog);
		echo $config->realm." User Management";
		virtual($config->separator);

		?>
			<div class="f12">
				<table width="200" align="right">
				<td align="right">&nbsp;<a href=logout.php>Logout</a></td>
				<td align="right">&nbsp;<a href=/phpBB/>FAQ</a></td>
				</tr>
				</table>
			</div>
			<br clear="all">
		<?

		/* if user haven't privilege 'change_priv' disable tab 'admin privileges' */
		if (!$perm->have_perm("change_priv"))
			foreach($config->admin_tabs as $k=>$v){
				if ($v->page == "list_of_admins.php") { $config->admin_tabs[$k]->enabled=false; break; }
			}
		
		print_tabs_new($config->admin_tabs, $config->admin_pages_path, $tab_selected); $_page_tab=1;?>	

 		<table bgcolor="#B1C9DC" width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr><td>
			<table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="20">
			<tr valign="top"><td>
			
<?	} //print_admin_html_body_begin


	function print_resell_html_body_begin($tab_selected=null){
		global $config, $_page_tab, $sess, $auth;

		virtual($config->prolog);
		echo $config->realm." Reseller Management";
		virtual($config->separator);

		?>
			<div class="f12">
				<table width="200" align="right">
				<td align="right">&nbsp;<a href=logout.php>Logout</a></td>
				<td align="right">&nbsp;<a href=/phpBB/>FAQ</a></td>
				</tr>
				</table>
			</div>
			<br clear="all">
		<?
		
		if ($auth->auth["uname"]==$config->ppaid->operator_id)
			$tabs=$config->ppaid->op_tabs;
		else
			$tabs=$config->ppaid->r_tabs;
		
		print_tabs_new($tabs, $config->ppaid->resell_pages_path, $tab_selected); $_page_tab=1;?>	

 		<table bgcolor="#B1C9DC" width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr><td>
			<table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="20">
			<tr valign="top"><td>
			
<?	} //print_admin_html_body_begin

	
	function print_html_body_end(){
		global $config, $_page_tab;	

	if ($_page_tab) { ?>	
			</td></tr>
			</table>
		</td></tr>
		</table>
	<?} //endif

	virtual($config->epilog);

	}

function print_tabs($tab){
global $config, $sess;
?>
		<table border="0" cellspacing="0" cellpadding="0">
		<tr valign="top">
<? 

//find index of last enabled tab
$lasttab=0;
foreach($config->enable_tabs as $i => $value) 
	if ($value) $lasttab=$i;

foreach($config->enable_tabs as $i => $value){
	if ($value){
		if ($tab==$i){?>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_left.gif" alt="" width="7" height="25" border="0"></td>
		<td width="78" bgcolor="#B1C9DC" height="1"><img src="<?echo $config->img_src_path;?>title/background_pixel.gif" alt="" width="78" height="1" border="0"></td>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_right.gif" alt="" width="7" height="25" border="0"></td>
<?			if ($i!=$lasttab){?>
		<td width="2" rowspan="2"><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" alt="" width="2" height="1" border="0"></td>
<?			}
		}
		else{?>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_left_w.gif" alt="" width="7" height="25" border="0"></td>
		<td width="78" bgcolor="#B1C9DC" height="1"><img src="<?echo $config->img_src_path;?>title/background_pixel.gif" alt="" width="78" height="1" border="0"></td>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_right_w.gif" alt="" width="7" height="25" border="0"></td>
<?			if ($i!=$lasttab){?>
		<td width="2" rowspan="2"><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" alt="" width="2" height="1" border="0"></td>
<?			}
		}//if ($tab==$i)
	
	}// if ($value)
}
	
?>
		</tr>

		<tr>
<?if ($config->enable_tabs[1]){ if ($tab==1){?>
		<td width="78" class="tab" bgcolor="#B1C9DC">my account</td>
<?}else{?>
		<td width="78" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl("my_account.php?kvrk=".uniqID(""));?>" class="tabl">my account</a></td>
<?}}if ($config->enable_tabs[2]){ if ($tab==2){?>
		<td width="78" class="tab" bgcolor="#B1C9DC">phone<br>book</td>
<?}else{?>
		<td width="78" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl("phonebook.php?kvrk=".uniqID(""));?>" class="tabl">phone<br>book</a></td>
<?}}if ($config->enable_tabs[3]){ if ($tab==3){?>
		<td width="78" class="tab" bgcolor="#B1C9DC">missed<br>calls</td>
<?}else{?>
		<td width="78" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl("missed_calls.php?kvrk=".uniqID(""));?>" class="tabl">missed<br>calls</a></td>
<?}}if ($config->enable_tabs[4]){ if ($tab==4){?>
		<td width="78" class="tab" bgcolor="#B1C9DC">accounting</td>
<?}else{?>
		<td width="78" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl("accounting.php?kvrk=".uniqID(""));?>" class="tabl">accounting</a></td>
<?}}if ($config->enable_tabs[5]){ if ($tab==5){?>
		<td width="78" class="tab" bgcolor="#B1C9DC">send IM</td>
<?}else{?>
		<td width="78" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl("send_im.php?kvrk=".uniqID(""));?>" class="tabl">send IM</a></td>
<?}}if ($config->enable_tabs[6]){ if ($tab==6){?>
		<td width="78" class="tab" bgcolor="#B1C9DC">notification<br>subscription</td>
<?}else{?>
		<td width="78" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl("notification_subscription.php?kvrk=".uniqID(""));?>" class="tabl">notification<br>subscription</a></td>
<?}}if ($config->enable_tabs[7]){ if ($tab==7){?>
		<td width="78" class="tab" bgcolor="#B1C9DC">message<br>store</td>
<?}else{?>
		<td width="78" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl("message_store.php?kvrk=".uniqID(""));?>" class="tabl">message<br>store</a></td>
<?}}if ($config->enable_tabs[8]){ if ($tab==8){?>
		<td width="78" class="tab" bgcolor="#B1C9DC">voicemail</td>
<?}else{?>
		<td width="78" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl("voicemail.php?kvrk=".uniqID(""));?>" class="tabl">voicemail</a></td>
<?}}if ($config->enable_tabs[9]){ if ($tab==9){?>
		<td width="78" class="tab" bgcolor="#B1C9DC">charging</td>
<?}else{?>
		<td width="78" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl("charging.php?kvrk=".uniqID(""));?>" class="tabl">charging</a></td>
<?}}if ($config->enable_tabs[10]){ if ($tab==10){?>
		<td width="78" class="tab" bgcolor="#B1C9DC">accounting</td>
<?}else{?>
		<td width="78" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl("accounting_b.php?kvrk=".uniqID(""));?>" class="tabl">accounting</a></td>
<?}}if ($config->enable_tabs[11]){ if ($tab==11){?>
		<td width="78" class="tab" bgcolor="#B1C9DC">user preferences</td>
<?}else{?>
		<td width="78" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl("user_preferences.php?kvrk=".uniqID(""));?>" class="tabl">user preferences</a></td>
<?}}if ($config->enable_tabs[12]){ if ($tab==12){?>
		<td width="78" class="tab" bgcolor="#B1C9DC">speed dial</td>
<?}else{?>
		<td width="78" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl("speed_dial.php?kvrk=".uniqID(""));?>" class="tabl">speed dial</a></td>
<?}}?>
		</tr>
		</table>

<?
}


function print_tabs_new($tabs, $path="", $selected=null){
	global $config, $sess, $PATH_TRANSLATED;

	if (!$selected){
		$selected=basename($PATH_TRANSLATED);
	}
?>
		<table border="0" cellspacing="0" cellpadding="0">
		<tr valign="top">
<? 

//find index of last enabled tab
$lasttab=0;
foreach($tabs as $i => $value) 
	if ($value->enabled) $lasttab=$i;

foreach($tabs as $i => $value){
	if ($value->enabled){
		if ($value->page==$selected){?>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_left.gif" alt="" width="7" height="25" border="0"></td>
		<td width="78" bgcolor="#B1C9DC" height="1"><img src="<?echo $config->img_src_path;?>title/background_pixel.gif" alt="" width="78" height="1" border="0"></td>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_right.gif" alt="" width="7" height="25" border="0"></td>
<?		}
		else{?>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_left_w.gif" alt="" width="7" height="25" border="0"></td>
		<td width="78" bgcolor="#FFFFFF" height="1"><img src="<?echo $config->img_src_path;?>title/background_pixel.gif" alt="" width="78" height="1" border="0"></td>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_right_w.gif" alt="" width="7" height="25" border="0"></td>
<?		}//if ($value->page==$selected)

		if ($i!=$lasttab){?>
		<td width="2" rowspan="2"><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" alt="" width="2" height="1" border="0"></td>
<?		}
	
	}// if ($value->enabled)
} //foreach		?>
		</tr>

		<tr>
<?
foreach($tabs as $i => $value){
	if ($value->enabled){
		if ($value->page==$selected){?>
		<td width="78" class="tab" bgcolor="#B1C9DC"><?echo $value->name;?></td>
<?		}
		else{?>
		<td width="78" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl($path.$value->page."?kvrk=".uniqID(""));?>" class="tabl"><?echo $value->name;?></a></td>
<?		}//if ($value->page==$selected)
	}// if ($value->enabled)
} //foreach		?>
		</tr>
		</table>

<?
}

?>
