{* Smarty *}
{* $Id: 2_new_admin.tpl,v 1.5 2009/12/17 17:12:19 kozlik Exp $ *}


{include file='_head.tpl'}
{include file='_popup_init.tpl' no_select_tab=1}

<h2 class="swTitle">{$lang_str.register_new_admin}</h2>

<div id="orphanlinks">
<a href="{url url='2_existing_admin.php' uniq=1}">&raquo; {$lang_str.assign_existing_admin}</a>
</div>
<br /><br />


<div class="swForm">
{$form.start}

	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="uname">{$lang_str.ff_username}:</label></td>
	<td>{$form.uname}</td>
	</tr>
	<tr>
	<td><label for="domain">{$lang_str.ff_domain}:</label></td>
	<td>{$form.domain}</td>
	</tr>

	{include file="_attr_form.tpl" attributes=$attributes form=$form}
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>{$form.okey}</td>
	</tr>
	</table>

{$form.finish}
</div>

<div class="swBackToMainPage" id="link_close"><a href="javascript: window.close();">{$lang_str.l_close_window}</a></div>
<div class="skipstep" id="link_skip" ><a href="3_finish.php">{$lang_str.l_skip_asignment_of_admin}</a></div>
<div class="swCleaner"></div>
<br>
{include file='_tail.tpl'}


