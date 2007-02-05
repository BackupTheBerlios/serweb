{* Smarty *}
{* $Id: a_admin_select.tpl,v 1.6 2007/02/05 15:10:38 kozlik Exp $ *}

{literal}
<style type="text/css">
	#uid,    #luid   {width:100px;}
	#usrnm,  #lusrnm {width:100px;}
	#domain,  #ldomain {width:100px;}
	#fname,  #lfname {width:100px;}
	#lname,  #llname {width:100px;}
	#email,  #lemail {width:100px;}


</style>	
{/literal}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.assign_admin_to_domain}</h2>

<br />

<div class="swForm swHorizontalForm">
{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr valign="bottom">
<td><label for="uid" id="luid">{$lang_str.ff_uid}</label></td>
<td><label for="usrnm"  id="lusrnm" >{$lang_str.ff_username}</label></td>
<td><label for="domain"  id="ldomain">{$lang_str.ff_domain}</label></td>
<td><label for="fname"  id="lfname" >{$lang_str.ff_first_name}</label></td>
<td><label for="lname"  id="llname" >{$lang_str.ff_last_name}</label></td>
<td><label for="email"  id="lemail" >{$lang_str.ff_email}</label></td>
</tr>

<tr>
<td>{$form.uid}</td>
<td>{$form.usrnm}</td>
<td>{$form.domain}</td>
<td>{$form.fname}</td>
<td>{$form.lname}</td>
<td>{$form.email}</td>
</tr>

<tr><td colspan="6"><label for="adminsonly" style="display: inline;">{$lang_str.ff_show_admins_only}:</label>{$form.adminsonly}</td></tr>
				
<tr><td colspan="6" align="right">{$form.okey}</td></tr>
</table>
{$form.finish}
</div>

<h2 class="swTitle">{$lang_str.list_of_users}</h2>

{foreach from=$users item='row' name='admins'}
	{if $smarty.foreach.admins.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th><a href="{$url_sort_uid}"      >{$lang_str.th_uid}</a></th>
	<th><a href="{$url_sort_username}" >{$lang_str.th_username}</a></th>
	<th>{$lang_str.th_domain}</th>
	<th><a href="{$url_sort_name}"     >{$lang_str.th_name}</a></th>
	<th><a href="{$url_sort_email}"    >{$lang_str.th_email}</a></th>
	</tr>
	{/if}
	{assign var='usr_class' value='swUserEnabled'}
	{assign var='dom_class' value='swDomainEnabled'}
	{if $row.disabled} {assign var='usr_class' value='swUserDisabled'} {/if}
	{if $row.domain_disabled} {assign var='dom_class' value='swDomainDisabled'} {/if}
	
	<tr valign="top" class="{cycle values='swTrOdd,swTrEven' advance=false}">
	<td align="left"><span class="{$usr_class}">{$row.uid|escape|empty2nbsp}</span></td>
	<td align="left"><span class="{$usr_class}">{$row.username|escape|empty2nbsp}</span></td>
	<td align="left"><span class="{$dom_class}">{$row.domain|escape|empty2nbsp}</span></td>
	<td align="left">{$row.name|escape|empty2nbsp}</td>
	<td align="left"><a href="mailto:{$row.email_address}">{$row.email_address}</a>&nbsp;</td>
	</tr>

	<tr class="{cycle values='swTrOdd,swTrEven'} actionsrow" valign="top">
	<td colspan="5" align="left">
		<a href="javascript: opener.location.href='{url url=$finish_url uniq=1}&{$row.get_param}'; window.close();" class="actionsrow">{$lang_str.l_select}</a>
	</td>
	</tr>

	{if $smarty.foreach.admins.last}
	</table>

	<div class="swNumOfFoundRecords">{$lang_str.showed_users} {$pager.from} - {$pager.to} {$lang_str.from} {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_users_found}</div>
{/foreach}
	
<div class="swBackToMainPage"><a href="javascript: window.close();">{$lang_str.l_close_window}</a></div>

<br>
{include file='_tail.tpl'}

