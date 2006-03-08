{* Smarty *}
{* $Id: 2_existing_admin.tpl,v 1.4 2006/03/08 15:36:28 kozlik Exp $ *}

{literal}
<style type="text/css">
	#uid,    #luid   {width:100px;}
	#usrnm,  #lusrnm {width:100px;}
	#realm,  #lrealm {width:100px;}
	#fname,  #lfname {width:100px;}
	#lname,  #llname {width:100px;}
	#email,  #lemail {width:100px;}

	#link_close{
		float: left;
		text-align: left;
	}

	#link_skip{
		float: right;
		text-align: right;
	}
</style>	
{/literal}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.assign_admin_to_domain}</h2>

<div class="swLinkSelector"><span class="swLsNotSelected"><a href="{url url='2_new_admin.php' uniq=1}">{$lang_str.register_new_admin}</a></span> | <span class="swLsSelected">{$lang_str.assign_existing_admin}</a></span></div>
<br /><br />

<div class="swForm swHorizontalForm">
{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr valign="bottom">
<td><label for="uid" id="luid">{$lang_str.ff_uid}</label></td>
<td><label for="usrnm"  id="lusrnm" >{$lang_str.ff_username}</label></td>
<td><label for="realm"  id="lrealm">{$lang_str.ff_realm}</label></td>
<td><label for="fname"  id="lfname" >{$lang_str.ff_first_name}</label></td>
<td><label for="lname"  id="llname" >{$lang_str.ff_last_name}</label></td>
<td><label for="email"  id="lemail" >{$lang_str.ff_email}</label></td>
</tr>

<tr>
<td>{$form.uid}</td>
<td>{$form.usrnm}</td>
<td>{$form.realm}</td>
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
	<th>{$lang_str.th_uid}</th>
	<th>{$lang_str.th_username}</th>
	<th>{$lang_str.th_realm}</th>
	<th>{$lang_str.th_name}</th>
	<th>{$lang_str.th_email}</th>
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
	<td align="left"><a href="mailto:{$row.email_address}">{$row.email_address}</a>&nbsp;</td>
	<td align="center"><a href="{url url=$finish_url uniq=1}&{$row.get_param}">{$lang_str.l_select}</a></td>
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
	
<div class="swBackToMainPage" id="link_close"><a href="javascript: window.close();">{$lang_str.l_close_window}</a></div>
<div class="swBackToMainPage" id="link_skip" ><a href="3_finish.php">{$lang_str.l_skip_asignment_of_admin}</a></div>

<br>
{include file='_tail.tpl'}
