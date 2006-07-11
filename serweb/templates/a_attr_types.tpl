{* Smarty *}
{* $Id: a_attr_types.tpl,v 1.6 2006/07/11 12:15:00 kozlik Exp $ *}

{literal}
<style type="text/css">
	div.flagForm{
		float:left; 
		padding: 0.5em;
	}

	div.flagForm table{
		float:left; /* required by IE */
		display:block;
	}
	
	#attr_order, #attr_name, #attr_type, #attr_label, #attr_access{
		width:150px;
	}
</style>
{/literal}

{include file='_head.tpl'}

{popup_init src="`$cfg->js_src_path`overlib/overlib.js"}


<div class="swForm">
{$form.start}


	<table border="0" cellspacing="0" cellpadding="0" align="center" class="attributesTable">
	<tr>
	<td><label for="attr_order"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_order}>{$lang_str.ff_order}</a>:</label></td>
	<td>{$form.attr_order}</td>
	</tr>
	<tr>
	<td><label for="attr_name">{$lang_str.ff_att_name}:</label></td>
	<td>{$form.attr_name}</td>
	</tr>
	<tr>
	<td><label for="attr_type">{$lang_str.ff_att_type}:</label></td>
	<td>{$form.attr_type}</td>
	</tr>
	<tr>
	<td><label for="attr_access">{$lang_str.ff_att_access}:</label></td>
	<td>{$form.attr_access}</td>
	</tr>
	<tr>
	<td><label for="attr_label"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_label}>{$lang_str.ff_label}</a>:</label></td>
	<td>{$form.attr_label}</td>
	</tr>
	<tr><td colspan="2">

	<div class="flagForm">
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="for_ser"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_for_ser}>{$lang_str.ff_for_ser}</a>:</label></td>
	<td>{$form.for_ser}</td>
	</tr>
	<tr>
	<td><label for="for_serweb"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_for_serweb}>{$lang_str.ff_for_serweb}</a>:</label></td>
	<td>{$form.for_serweb}</td>
	</tr>
	</table>
	</div>

	<div class="flagForm">
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="pr_user"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_user}>{$lang_str.ff_att_user}</a>:</label></td>
	<td>{$form.pr_user}</td>
	</tr>
	<tr>
	<td><label for="pr_domain"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_domain}>{$lang_str.ff_att_domain}</a>:</label></td>
	<td>{$form.pr_domain}</td>
	</tr>
	<tr>
	<td><label for="pr_global"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_global}>{$lang_str.ff_att_global}</a>:</label></td>
	<td>{$form.pr_global}</td>
	</tr>
	</table>
	</div>
	
	<div class="flagForm">
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="multivalue"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_multivalue}>{$lang_str.ff_multivalue}</a>:</label></td>
	<td>{$form.multivalue}</td>
	</tr>
	<tr>
	<td><label for="registration"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_registration}>{$lang_str.ff_att_reg}</a>:</label></td>
	<td>{$form.registration}</td>
	</tr>
	<tr>
	<td><label for="required"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_required}>{$lang_str.ff_att_req}</a>:</label></td>
	<td>{$form.required}</td>
	</tr>
	</table>
	</div>
	
	</td></tr>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>

	<tr>
	<td align="left">{if $form.extended_settings}{$form.extended_settings}{else}&nbsp;{/if}</td>
	<td>{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>
<br />

{foreach from=$attrs item='row' name='at'}
	{if $smarty.foreach.at.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable alternatpoplinkstyle">
	<tr>
	<th class="alternatpoplinkstyle"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_order}>{$lang_str.th_order}</a></th>
	<th class="alternatpoplinkstyle">{$lang_str.th_att_name}</th>
	<th class="alternatpoplinkstyle">{$lang_str.th_att_type}</th>
	<th class="alternatpoplinkstyle"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_registration}>R</a></th>
	<th class="alternatpoplinkstyle"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_user        }>U</a></th>
	<th class="alternatpoplinkstyle"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_domain      }>D</a></th>
	<th class="alternatpoplinkstyle"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_global      }>G</a></th>
	<th class="alternatpoplinkstyle"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_for_ser     }>S</a></th>
	<th class="alternatpoplinkstyle"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_for_serweb  }>SW</a></th>
	<th class="alternatpoplinkstyle"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_label       }>{$lang_str.th_label}</a></th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.order|empty2nbsp}</td>
	<td align="left">{$row.name|escape|empty2nbsp}</td>
	<td align="left">{$row.type|escape|empty2nbsp}</td>
	<td align="center" class="swValignMid">{include file="includes/yes_no.tpl" ok=$row.flags.DB_FILL_ON_REG}</td>
	<td align="center" class="swValignMid">{include file="includes/yes_no.tpl" ok=$row.priority.USER}</td>
	<td align="center" class="swValignMid">{include file="includes/yes_no.tpl" ok=$row.priority.DOMAIN}</td>
	<td align="center" class="swValignMid">{include file="includes/yes_no.tpl" ok=$row.priority.GLOBAL}</td>
	<td align="center" class="swValignMid">{include file="includes/yes_no.tpl" ok=$row.default_flags.DB_LOAD_SER}</td>
	<td align="center" class="swValignMid">{include file="includes/yes_no.tpl" ok=$row.default_flags.DB_FOR_SERWEB}</td>
	<td align="left" class="alternatpoplinkstyle">{
		if $row.translate_desc}{
			if $row.translation_lack
			     }<a href="javascript:void(0);" class="swPopupLink" {popup text=$row.desc_translated|escape|escape|empty2nbsp fgcolor="red"}>{$row.description|truncate:40:"...":true|escape|empty2nbsp}</a>{
			else }<a href="javascript:void(0);" class="swPopupLink" {popup text=$row.desc_translated|escape|escape|empty2nbsp}>{$row.description|truncate:40:"...":true|escape|empty2nbsp}</a>{
			/if}{
		else}{
			if $row.description|count_characters > 40
				}<a href="javascript:void(0);" class="swPopupLink" {popup text=$row.description|escape|escape|empty2nbsp}>{$row.description|truncate:40:"...":true|escape|empty2nbsp}</a>{
			else }{$row.description|truncate:40|escape|empty2nbsp}{
			/if}{
		/if}</td>
	<td align="center"><a href="{$row.url_edit}" class="actionsrow">{$lang_str.l_edit}</a></td>
	<td align="center"><a href="{$row.url_dele}" class="actionsrow" onclick="return confirmDelete(this, '{$lang_str.realy_want_you_delete_this_attr}')">{$lang_str.l_delete}</a></td>
	</tr>

	{if $smarty.foreach.at.last}
	</table>
	{/if}
{/foreach}

<br />
<div class="swWarningBox"><h2>{$lang_str.warning}</h2>
{$lang_str.attr_type_warning}
</div>
<br>

<br />
{include file='_tail.tpl'}

