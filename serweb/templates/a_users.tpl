{* Smarty *}
{* $Id: a_users.tpl,v 1.14 2006/03/08 15:36:27 kozlik Exp $ *}

{include file='_head.tpl'}

{popup_init src="`$cfg->js_src_path`overlib/overlib.js"}

<h2 class="swTitle">{$lang_str.search_filter}:</h2>

{literal}
<style type="text/css">
	#uid,   #luid   {width:120px;}
	#usrnm, #lusrnm {width:120px;}
	#realm, #lrealm {width:120px;}
	#fname, #lfname {width:120px;}
	#lname, #llname {width:120px;}
	#email, #lemail {width:120px;}
</style>	
{/literal}

<div class="swForm swHorizontalForm">

{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr valign="bottom">
<td><label for="uid" id="luid">{$lang_str.ff_uid}</label></td>
<td><label for="usrnm" id="lusrnm">{$lang_str.ff_username}</label></td>
<td><label for="realm" id="lrealm">{$lang_str.ff_realm}</label></td>
<td><label for="fname" id="lfname">{$lang_str.ff_first_name}</label></td>
<td><label for="lname" id="llname">{$lang_str.ff_last_name}</label></td>
<td><label for="email" id="lemail">{$lang_str.ff_email}</label></td>
</tr>

<tr>
<td>{$form.uid}</td>
<td>{$form.usrnm}</td>
<td>{$form.realm}</td>
<td>{$form.fname}</td>
<td>{$form.lname}</td>
<td>{$form.email}</td>
</tr>

<tr><td colspan="6"><label for="onlineonly" style="display: inline;">{$lang_str.ff_show_online_only}:</label>{$form.onlineonly}</td></tr>
				
<tr><td colspan="6" align="right">{$form.okey}</td></tr>
</table>
{$form.finish}
</div>

{foreach from=$users item='row' name='users'}
	{if $smarty.foreach.users.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.th_uid}</th>
	<th>{$lang_str.th_username}</th>
	<th>{$lang_str.th_realm}</th>
	<th>{$lang_str.th_name}</th>
	<th>{$lang_str.th_phone}</th>
	<th>{$lang_str.th_alias}</th>
	<th>{$lang_str.th_email}</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	{assign var='usr_class' value='swUserEnabled'}
	{assign var='dom_class' value='swDomainEnabled'}
	{if $row.disabled} {assign var='usr_class' value='swUserDisabled'} {/if}
	{if $row.domain_disabled} {assign var='dom_class' value='swDomainDisabled'} {/if}
	
	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left"><span class="{$usr_class}">{$row.uid|escape|empty2nbsp}</span></td>
	<td align="left"><span class="{$usr_class}">{$row.username|escape|empty2nbsp}</span></td>
	<td align="left"><span class="{$dom_class}">{$row.domain|escape|empty2nbsp}</span></td>
	<td align="left">{$row.name|escape|empty2nbsp}</td>
	<td align="right">{$row.phone|escape|empty2nbsp}</td>
	<td align="right">{include file="includes/inline_aliases.tpl" uris=$row.uris}</td>
	<td align="left"><a href="mailto:{$row.email_address}">{$row.email_address}</a>&nbsp;</td>
	<td align="center"><a href="{url url='acl.php' uniq=1}&{$row.get_param}">{$lang_str.l_acl}</a></td>
	<td align="center"><a href="{url url='aliases.php' uniq=1}&{$row.get_param}">{$lang_str.l_aliases}</a></td>
	<td align="center"><a href="{$cfg->user_pages_path}{url url='my_account.php' uniq=1}&{$row.get_param}">{$lang_str.l_account}</a></td>
	<td align="center"><a href="{$cfg->user_pages_path}{url url='accounting.php' uniq=1}&{$row.get_param}">{$lang_str.l_accounting}</a></td>
	{if $row.disabled}
	<td align="center"><a href="{$row.url_enable}">{$lang_str.l_enable}</a></td>
	{else}
	<td align="center"><a href="{$row.url_disable}">{$lang_str.l_disable}</a></td>
	{/if}
	<td align="center"><a href="{$row.url_dele}" onclick="return confirmDelete(this, '{$lang_str.realy_you_want_delete_this_user}')">{$lang_str.l_delete}</a></td>
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

<a href="{url url='new_user.php' uniq=1}">{$lang_str.register_new_user}</a>

<br>
{include file='_tail.tpl'}
