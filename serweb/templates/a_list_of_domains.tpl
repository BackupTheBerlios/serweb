{* Smarty *}
{* $Id: a_list_of_domains.tpl,v 1.1 2005/10/19 10:33:07 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.search_filter}:</h2>

{literal}
<style type="text/css">
	#dl_id,  #lid {width:150px;}
	#dl_name, #lname {width:150px;}
	#dl_customer, #lcustomer {width:150px;}
</style>	
{/literal}

<div class="swForm swHorizontalForm">
{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr valign="bottom">
<td><label for="dl_id"  id="lid" >{$lang_str.d_id}</label></td>
<td><label for="dl_name" id="lname">{$lang_str.d_name}</label></td>
<td><label for="dl_customer" id="lcustomer">{$lang_str.owner}</label></td>
</tr>

<tr>
<td>{$form.dl_id}</td>
<td>{$form.dl_name}</td>
<td>{$form.dl_customer}</td>
</tr>
				
<tr><td colspan="3" height="5"></td></tr>
<tr><td colspan="3" align="right">{$form.okey}</td></tr>
</table>
{$form.finish}
</div>

<h2 class="swTitle">{$lang_str.list_of_domains}</h2>

{foreach from=$domains item='row' name='domains'}
	{if $smarty.foreach.domains.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr valign="top">
	<th>{$lang_str.d_id}</th>
	<th>{$lang_str.d_name}</th>
	<th>{$lang_str.owner}</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
{if $hostmaster_actions}
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
{/if}
	</tr>
	{/if}
	
	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.id|empty2nbsp}</td>
	<td align="left">
		{assign var='dom_class' value='swDomainEnabled'}
		{if $row.disabled} {assign var='dom_class' value='swDomainDisabled'} {/if}
		{foreach from=$row.names item='dom' name='one_domain'}
			<span class="{$dom_class}">{$dom.name}</span>{if !$smarty.foreach.one_domain.last}, {/if}
		{/foreach}&nbsp;
	</td>
	<td align="left">{$row.customer|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_layout}">{$lang_str.l_change_layout}</a></td>
	<td align="center"><a href="{$row.url_preferences}">{$lang_str.l_domain_preferences}</a></td>
{if $hostmaster_actions}
	<td align="center"><a href="{$row.url_edit}">{$lang_str.l_edit}</a></td>
	{if $row.disabled}
	<td align="center"><a href="{$row.url_enable}">{$lang_str.l_enable}</a></td>
	{else}
	<td align="center"><a href="{$row.url_disable}">{$lang_str.l_disable}</a></td>
	{/if}
	<td align="center"><a href="{$row.url_dele}" onclick="return confirmDelete(this, '{$lang_str.realy_delete_domain}')">{$lang_str.l_delete}</a></td>
{/if}
	</tr>
	{if $smarty.foreach.domains.last}
	</table>

	<div class="swNumOfFoundRecords">{$lang_str.showed_domains} {$pager.from} - {$pager.to} {$lang_str.from} {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_domains_found}</div>
{/foreach}

{if $hostmaster_actions}
<div><a href="{$url_new_domain}">{$lang_str.l_create_new_domain}</a></div>
{/if}
	
<br>
{include file='_tail.tpl'}
