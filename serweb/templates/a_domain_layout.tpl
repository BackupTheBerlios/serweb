{* Smarty *}
{* $Id: a_domain_layout.tpl,v 1.5 2006/05/23 09:13:38 kozlik Exp $ *}

{literal}
<script language="javascript" type="text/javascript">

	var dialog_win = null;
	var dialog_field_name = null;

	tinyMCE.init({
		mode : "none",
		theme : "advanced",
		plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,paste,directionality,fullscreen,noneditable,contextmenu,filemanager",
//		theme_advanced_buttons1_add_before : "save,newdocument,separator",
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,formatselect",
		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add : "separator,preview,zoom,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add : "iespell,flash,advhr,separator,print,separator,fullscreen,filemanager",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
	    plugin_insertdate_dateFormat : "%Y-%m-%d",
	    plugin_insertdate_timeFormat : "%H:%M:%S",
		extended_valid_elements : "hr[class|width|size|noshade]",
		file_browser_callback : "fileBrowserCallBack",
		paste_use_dialog : false,
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : true
	});

	function fileBrowserCallBack(field_name, url, type, win) {
		dialog_win = win;
		dialog_field_name = field_name;

		tinyMCE.execInstanceCommand('dl_content', 'mceFilemanager', null, "insertFileToTinyMCE");
	}
	
	function insertFileToTinyMCE(url){
		dialog_win.document.forms[0].elements[dialog_field_name].value = url;
		dialog_win.focus();
	}

</script>

{/literal}

{include file='_head.tpl'}

{if $action == 'edit_text' or $action == 'edit_layout'}
	{$form.start}
		<div id="oldVersions">
		{foreach from=$backup_files item='row' name='backup'}
			{if $smarty.foreach.backup.first}
			<table border="1" cellpadding="1" cellspacing="0" class="swTable">
			<tr>
			<th align="left">{$lang_str.th_old_versions}</th>
			</tr>
			{/if}
			
			<tr valign="top">
			<td align="left"><a href="{$row.url_edit}" class="actionsrow">{$row.version|empty2nbsp}{if $row.timestamp} - {$row.timestamp|my_date_format:$lang_set.date_time_format}{/if}</a></td>
			</tr>
			{if $smarty.foreach.backup.last}
			</table>
			{/if}
		{/foreach}
		</div>
	
		<div class="swForm">
		<table border="0" cellspacing="0" cellpadding="0" align="center">
		<tr><td colspan="2">{$form.dl_content}</td></tr>
		<tr><td align="left" width="50%">{if $fileinfo.html}<a href="javascript:toogleEditorMode('dl_content');" class="actionsrow">{$lang_str.l_toggle_wysiwyg}</a>
		                     {else}&nbsp;{/if}</td>
	        <td align="right" width="50%">{$form.okey}</td></tr>
		</table>
		</div>
	{$form.finish}
	
	<br class="cleaner" />
	{if $fileinfo.html}
		<div class="swWarningBox">{$lang_str.wysiwyg_warning}</div>
		<br />
	{/if}
	
	<div class="swBackToMainPage"><a href="{url url='domain_layout.php' uniq=1}">{$lang_str.l_back_to_main}</a></div>


{else}

	<h2 class="swTitle">{$lang_str.layout_files}</h2>
	
	{foreach from=$layout_files item='row' name='layout'}
		{if $smarty.foreach.layout.first}
		<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
		{/if}
		
		<tr valign="top">
		<td align="left">{$row.desc|empty2nbsp}</td>
		<td align="left"><a href="{$row.url_edit}" class="actionsrow">{$lang_str.l_edit}</a></td>
		</tr>
		{if $smarty.foreach.layout.last}
		</table>
		{/if}
	{/foreach}

	<br />
 	<div id="orphanlinks">
 	<a href="javascript:openFileManager();">{$lang_str.l_upload_images}</a>
 	</div>	
	
	<h2 class="swTitle">{$lang_str.text_files}</h2>
	
	{foreach from=$text_files item='row' name='textf'}
		{if $smarty.foreach.textf.first}
		<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
		{/if}
		
		<tr valign="top">
		<td align="left">{$row.desc|empty2nbsp}</td>
		<td align="left">
		{foreach from=$row.languages item='lang' name='lfe'}
		<a href="{$row.lang.$lang.url_edit}" class="actionsrow">{$lang}</a>{if !$smarty.foreach.lfe.last} {/if}
		{/foreach}
		&nbsp;</td>
		</tr>
		{if $smarty.foreach.textf.last}
		</table>
		{/if}
	{/foreach}
	
	
	<div class="swBackToMainPage"><a href="{url url='list_of_domains.php' uniq=1}">{$lang_str.l_back_to_main}</a></div>
{/if}

<br>
{include file='_tail.tpl'}
