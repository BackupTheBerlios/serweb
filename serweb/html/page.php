<?
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
<?	}

	$_page_tab=0;

	function print_html_body_begin($tab=false, $target_blank=false, $show_logout=false){
		global $config, $_page_tab, $sess;	?>
<body bgcolor="#FFFFFF" text="#000000" link="#33CCFF" vlink="#33CCCC" alink="#33FFFF" MARGINHEIGHT="0" MARGINWIDTH="0" leftmargin="0" topmargin="0">
<table bgcolor=#B1C9DC width="100%" border="0" cellspacing="0" cellpadding="0"> 
<tr><td>
 
	<table width="100%" background="<?echo $config->img_src_path;?>title/background_top.gif" border="0" cellspacing="0" cellpadding="0">
	<tr>
 	<td>
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="431"><a href="<?echo $config->link_abstract;?>" <?echo $target_blank?"target=\"_blank\"":"";?>><img src="<?echo $config->img_src_path;?>title/banner_top.gif" width="431" height="47" border="0" alt="Abstract"></a></td>
		<td width="271"><a href="<?echo $config->link_home;?>" <?echo $target_blank?"target=\"_blank\"":"";?>><img src="<?echo $config->img_src_path;?>title/iptel_logo.gif" width="271" height="47" border="0" alt="Shortcut to iptel.org Home Page"></a></td>
		</tr>
		</table>
	</td>
	<td align="left">&nbsp;</td>
	</tr>
	</table> 
</td></tr>
<!-- <tr><td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#75C5F0">
	<tr align="left"><form action="/cgi-bin/webglimpse/home/www/wg2" target=_top> 
	<td width="55"><span class="txt_norm"><font color="#FFFFFF">&nbsp;Search</font></span></td>
	<td width="15"><input type="text" name="query" size="15" value=""></td>
	<td>&nbsp;<INPUT type=image src="<?echo $config->img_src_path;?>arrows.gif" name=submit VALUE="Submit"></td></form>
	</tr>
	</table>
</td></tr> -->
<tr><td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td width="156">&nbsp;</td>
	<td colspan="4" valign="bottom" height="34">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="32" rowspan="2"><img src="<?echo $config->img_src_path;?>title/table_top_left.gif" width="32" height="34"></td>
		<td height="15" bgcolor="#B1C9DC"><img src="<?echo $config->img_src_path;?>title/background_pixel.gif" width="1" height="15"></td>
		<td width="10" rowspan="2"><img src="<?echo $config->img_src_path;?>title/table_right.gif" width="23" height="34"></td>
		</tr>
		<tr> 
		<td height="19" bgcolor="#FFFFFF" width="100%"><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" width="1" height="19"></td>
		</tr>
		</table>
	</td>
	<td rowspan="3" width="5"><img src="<?echo $config->img_src_path;?>title/background_pixel.gif" width="5" height="1"></td>
	</tr>

	<tr> 
	<td width="156" valign="top" align="left"> 
		<table width="156" border="0" cellspacing="0" cellpadding="0"  background="<?echo $config->img_src_path;?>title/background_pixel.gif" bgcolor="#B1C9DC">
		<tr> 
		<td colspan="3"><img src="<?echo $config->img_src_path;?>title/box_top.gif" width="156" height="61"></td>
		</tr>
		<tr> 
		<td colspan="3">
			<script language="JavaScript">
			<!--
			image1 = new Image();
			image1.src = "<?echo $config->img_src_path;?>preload/sip_tutorial_a.gif";
			
			image2 = new Image();
			image2.src = "<?echo $config->img_src_path;?>preload/sip_products_a.gif";
			
			image3 = new Image();
			image3.src = "<?echo $config->img_src_path;?>preload/sip_standards_a.gif";
			
			image4 = new Image();
			image4.src = "<?echo $config->img_src_path;?>preload/iptel_workshop_a.gif";
			
			image5 = new Image();
			image5.src = "<?echo $config->img_src_path;?>preload/glossary_a.gif";
			
			image6 = new Image();
			image6.src = "<?echo $config->img_src_path;?>preload/logout_a.gif";
			//-->
			</script>
			<table width="156" border="0" cellspacing="0" cellpadding="0"  bgcolor="#B1C9DC">
			<tr> 
			<td><a href="<?echo $config->link_siptutorial;?>" onMouseOver="image1.src='<?echo $config->img_src_path;?>preload/sip_tutorial_a.gif';" onMouseOut="image1.src='<?echo $config->img_src_path;?>preload/sip_tutorial.gif';" <?echo $target_blank?"target=\"_blank\"":"";?>><img name="image1" src="<?echo $config->img_src_path;?>preload/sip_tutorial.gif" border=0></a></td>
			</tr>
			<tr> 
			<td><a href="<?echo $config->link_sipproducts;?>" onMouseOver="image2.src='<?echo $config->img_src_path;?>preload/sip_products_a.gif';" onMouseOut="image2.src='<?echo $config->img_src_path;?>preload/sip_products.gif';" <?echo $target_blank?"target=\"_blank\"":"";?>><img name="image2" src="<?echo $config->img_src_path;?>preload/sip_products.gif" border=0></a></td>
			</tr>
			<tr> 
			<td><a href="<?echo $config->link_standards;?>" onMouseOver="image3.src='<?echo $config->img_src_path;?>preload/sip_standards_a.gif';" onMouseOut="image3.src='<?echo $config->img_src_path;?>preload/sip_standards.gif';" <?echo $target_blank?"target=\"_blank\"":"";?>><img name="image3" src="<?echo $config->img_src_path;?>preload/sip_standards.gif" border=0></a></td>
			</tr>
			<tr> 
			<td><a href="<?echo $config->link_iptelworkshop;?>" onMouseOver="image4.src='<?echo $config->img_src_path;?>preload/iptel_workshop_a.gif';" onMouseOut="image4.src='<?echo $config->img_src_path;?>preload/iptel_workshop.gif';" <?echo $target_blank?"target=\"_blank\"":"";?>><img name="image4" src="<?echo $config->img_src_path;?>preload/iptel_workshop.gif" border=0></a></td>
			</tr>
			<tr> 
			<td><a href="<?echo $config->link_glossary;?>" onMouseOver="image5.src='<?echo $config->img_src_path;?>preload/glossary_a.gif';" onMouseOut="image5.src='<?echo $config->img_src_path;?>preload/glossary.gif';" <?echo $target_blank?"target=\"_blank\"":"";?>><img name="image5" src="<?echo $config->img_src_path;?>preload/glossary.gif" border=0></a></td>
			</tr>
			</table>
		</td>
		</tr>
		<tr> 
		<td colspan="3" height="48"><img src="<?echo $config->img_src_path;?>title/login.gif" width="156" height="55" alt="Login" border="0"></td>
		</tr>
		<tr> 
		<td width="22" height="11"><img src="<?echo $config->img_src_path;?>title/login_left.gif" width="22" height="14" border="0"></td>
 		<td width="77" height="11"><img src="<?echo $config->img_src_path;?>login_mitte.gif"></td>
		<td width="57" height="11"><img src="<?echo $config->img_src_path;?>title/login_right.gif" width="57" height="14" border="0"></td>
		</tr>
		<tr> 
		<td colspan="3"><img src="<?echo $config->img_src_path;?>title/password.gif" width="156" height="21" border="0"></td>
		</tr>
		<tr> 
		<td width="22" height="11"><img src="<?echo $config->img_src_path;?>title/password_left.gif" width="22" height="14" border="0"></td>
		<td width="77" height="11"><img src="<?echo $config->img_src_path;?>password_mitte.gif"></td>
		<td width="57" height="11"><img src="<?echo $config->img_src_path;?>title/password_right.gif" width="57" height="14" border="0"></td>
		</tr>
		<tr> 
		<td colspan="2" height="15"><img src="<?echo $config->img_src_path;?>title/forgot_left.gif" width="99" height="71"></td>
		<td width="57" height="15"><img src="<?echo $config->img_src_path;?>preload/forgot.gif"></td>
		</tr>
		<tr>
		<td width="22">&nbsp;</td>
		<td colspan="2"> <img src="<?echo $config->img_src_path;?>preload/subscribe.gif"></td>
		</tr>
		<tr>
		<td colspan="3"><img src="<?echo $config->img_src_path;?>title/search.gif" width="156" height="65"></td>
		</tr>
		<tr>
		<td width="22" height="11"><img src="<?echo $config->img_src_path;?>title/search_left.gif" width="22" height="14"></td>
		<?if ($show_logout){?>
		<td width="77" height="11"><a href="<?$sess->purl($config->root_path."user_interface/logout.php");?>" onMouseOver="image6.src='<?echo $config->img_src_path;?>preload/logout_a.gif';" onMouseOut="image6.src='<?echo $config->img_src_path;?>preload/logout.gif';"><img name="image6" src="<?echo $config->img_src_path;?>preload/logout.gif" border="0"></td>
		<?}else{?>
 		<td width="77" height="11"><img src="<?echo $config->img_src_path;?>search_mitte.gif"></td>
		<?}?>
		<td width="57" height="11"><img src="<?echo $config->img_src_path;?>title/search_right.gif" width="57" height="14"></td>
		</tr>
		<tr>
		<td colspan="3" height="15"><img src="<?echo $config->img_src_path;?>preload/advanced.gif"></td>
		</tr>
		<tr valign="top">
		<td colspan="3"><img src="<?echo $config->img_src_path;?>title/right_bottom.gif" width="156" height="41" align="top"></td>
		</tr>
		<tr valign="top">
		<td colspan="3">&nbsp;</td>
		</tr>
		</table>
	</td>
	<td width="10"><img src="<?echo $config->img_src_path;?>title/background_pixel.gif" width="10" height="1"></td>
	<td width="22" bgcolor="#FFFFFF"><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" width="22" height="1"></td>
	<td bgcolor="#FFFFFF" align="left" valign="top" background="<?echo $config->img_src_path;?>title/white_pixel.gif" width="100%"> 

		<table width="<?echo $config->default_width?>" border="0" cellspacing="0" cellpadding="0">
		<tr><td><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" width="<?echo $config->default_width?>" height="1"></td></tr>
		</table>
		
<? if ($tab) { print_tabs($tab); $_page_tab=1;?>	

 		<table bgcolor="#B1C9DC" width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr><td>
			<table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr valign="top"><td>
<?}//endif?>

<?	}

	function print_html_body_end(){
		global $config, $_page_tab;	?>

<? if ($_page_tab) { ?>	
			</td></tr>
			</table>
		</td></tr>
		</table>
<?}//endif?>

	</td>
	<td width="23" bgcolor="#FFFFFF"><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" width="22" height="1"></td>
	</tr>
	<tr> 
	<td width="156">&nbsp;</td>

	<td colspan="4" valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="32" rowspan="2"><img src="<?echo $config->img_src_path;?>title/table_bot_left.gif" width="32" height="30"></td>
		<td bgcolor="#FFFFFF" height="22"><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" width="1" height="22"></td>
		<td width="23" rowspan="2"><img src="<?echo $config->img_src_path;?>title/table_bot_right.gif" width="23" height="30"></td>
		</tr>
		<tr> 
		<td height="8" bgcolor="#B1C9DC" width="100%"><img src="<?echo $config->img_src_path;?>title/background_pixel.gif" width="1" height="8"></td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
</td>
</tr>
</table>
</body>
<?	}

function print_tabs($tab){
global $config, $sess;
?>
		<table border="0" cellspacing="0" cellpadding="0">
		<tr valign="top">
<? 
for ($i=1; $i<6; $i++){
	if ($config->enable_tabs[$i]){
		if ($tab==$i){?>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_left.gif" alt="" width="7" height="25" border="0"></td>
		<td width="78" bgcolor="#B1C9DC" height="1"><img src="<?echo $config->img_src_path;?>title/background_pixel.gif" alt="" width="78" height="1" border="0"></td>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_right.gif" alt="" width="7" height="25" border="0"></td>
		<td width="2" rowspan="2"><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" alt="" width="2" height="1" border="0"></td>
<?		}
		else{?>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_left_w.gif" alt="" width="7" height="25" border="0"></td>
		<td width="78" bgcolor="#B1C9DC" height="1"><img src="<?echo $config->img_src_path;?>title/background_pixel.gif" alt="" width="78" height="1" border="0"></td>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_right_w.gif" alt="" width="7" height="25" border="0"></td>
		<td width="2" rowspan="2"><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" alt="" width="2" height="1" border="0"></td>
<?		}
	}
}

if ($config->enable_tabs[6]){
	if ($tab==6){?>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_left.gif" alt="" width="7" height="25" border="0"></td>
		<td width="80" bgcolor="#B1C9DC" height="1"><img src="<?echo $config->img_src_path;?>title/background_pixel.gif" alt="" width="80" height="1" border="0"></td>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_right.gif" alt="" width="7" height="25" border="0"></td>
<?	}
	else{?>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_left_w.gif" alt="" width="7" height="25" border="0"></td>
		<td width="80" bgcolor="#B1C9DC" height="1"><img src="<?echo $config->img_src_path;?>title/background_pixel.gif" alt="" width="80" height="1" border="0"></td>
		<td width="7" rowspan="2"><img src="<?echo $config->img_src_path;?>tab/tab_right_w.gif" alt="" width="7" height="25" border="0"></td>
<?	}
}?>
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
		<td width="80" class="tab" bgcolor="#B1C9DC">notification<br>subscription</td>
<?}else{?>
		<td width="80" class="tab" bgcolor="#FFFFFF"><a href="<?$sess->purl("notification_subscription.php?kvrk=".uniqID(""));?>" class="tabl">notification<br>subscription</a></td>
<?}}?>
		</tr>
		</table>

<?
}

?>
