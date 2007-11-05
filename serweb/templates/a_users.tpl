{* Smarty *}
{* $Id: a_users.tpl,v 1.26 2007/11/05 12:55:11 kozlik Exp $ *}

{include file='_head.tpl'}

{popup_init src="`$cfg->js_src_path`overlib/overlib.js"}

<h2 class="swTitle">{$lang_str.search_filter}:</h2>

{literal}
<style type="text/css">
	#uid,   #luid   {width:150px;}
	#username, #lusrnm {width:150px;}
	#domain, #ldomain {width:150px;}
	#sipuri, #lsipuri {width:150px;}
	#fname, #lfname {width:150px;}
	#lname, #llname {width:150px;}
	#email, #lemail {width:150px;}
</style>	
{/literal}

<div class="swForm swHorizontalForm">

{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr valign="bottom">
<td><label for="uid" id="luid">{$lang_str.ff_uid}</label></td>
<td><label for="username" id="lusrnm">{$lang_str.ff_username}</label></td>
<td><label for="domain" id="ldomain">{$lang_str.ff_domain}</label></td>
<td><label for="sipuri" id="lsipuri">{$lang_str.ff_sip_address}</label></td>
</tr>

<tr>
<td>{$form.uid}</td>
<td>{$form.username}</td>
<td>{$form.domain}</td>
<td>{$form.sipuri}</td>
</tr>

<tr valign="bottom">
<td><label for="fname" id="lfname">{$lang_str.ff_first_name}</label></td>
<td><label for="lname" id="llname">{$lang_str.ff_last_name}</label></td>
<td><label for="email" id="lemail">{$lang_str.ff_email}</label></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>{$form.fname}</td>
<td>{$form.lname}</td>
<td>{$form.email}</td>
<td>&nbsp;</td>
</tr>

<tr><td colspan="4"><label for="onlineonly" style="display: inline;">{$lang_str.ff_show_online_only}:</label>{$form.onlineonly}</td></tr>

<tr><td colspan="4" class="note">{$lang_str.filter_wildcard_note}</td></tr>
				
<tr><td colspan="4" align="right">{$form.okey}</td></tr>
</table>
{$form.finish}
</div>

{foreach from=$users item='row' name='users'}
	{if $smarty.foreach.users.first}
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
	{include file="includes/popup_uris.tpl" uris=$row.uris assign="popup_uri"}
		

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven' advance=false} informationrow">
	<td align="left"><span class="{$usr_class}"><a href="javascript:void(0);" class="swPopupLink" {popup text=$popup_uri caption=$lang_str.l_uris}>{$row.uid|escape|empty2nbsp}</a></span></td>
	<td align="left"><span class="{$usr_class}">{$row.username|escape|empty2nbsp}</span></td>
	<td align="left"><span class="{$dom_class}">{if $row.domain}{$row.domain|escape|empty2nbsp}{else}<i>&lt;no domain&gt;</i>{/if}</span></td>
	<td align="left">{$row.name|escape|empty2nbsp}</td>
	<td align="left"><a href="mailto:{$row.email_address}">{$row.email_address}</a>&nbsp;</td>
	</tr>
	
	<tr class="{cycle values='swTrOdd,swTrEven'} actionsrow" valign="top">
	<td colspan="5" align="left">
		<a href="{url url='acl.php' uniq=1}&{$row.get_param}">{$lang_str.l_acl}</a>
		<a href="{url url='aliases.php' uniq=1}&{$row.get_param}">{$lang_str.l_aliases}</a>
		<a href="{$cfg->user_pages_path}{url url='my_account.php' uniq=1}&{$row.get_param}">{$lang_str.l_account}</a>
		<a href="{$cfg->user_pages_path}{url url='accounting.php' uniq=1}&{$row.get_param}">{$lang_str.l_accounting}</a>
{*		<a href="{url url='credentials.php' uniq=1}&{$row.get_param}">{$lang_str.l_credentials}</a>*}
	{if $row.disabled}
		<a href="{$row.url_enable}">{$lang_str.l_enable}</a>
	{else}
		<a href="{$row.url_disable}">{$lang_str.l_disable}</a>
	{/if}
		<a href="{$row.url_dele}" onclick="return confirmDelete(this, '{$lang_str.realy_you_want_delete_this_user}')">{$lang_str.l_delete}</a>
	</td>
	</tr>
	{if $smarty.foreach.users.last}
	</table>

	<div class="swNumOfFoundRecords">{$lang_str.showed_users} {$pager.from} - {$pager.to} {$lang_str.from} {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_users_found}</div>
{/foreach}

<div id="orphanlinks"><a href="{url url='new_user.php' uniq=1}">{$lang_str.register_new_user}</a></div>

<br>
{include file='_tail.tpl'}
