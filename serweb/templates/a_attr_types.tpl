{* Smarty *}
{* $Id: a_attr_types.tpl,v 1.13 2009/10/29 13:01:06 kozlik Exp $ *}

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
	
	#attr_order, #attr_name, #attr_type, #attr_label, #attr_access, #attr_group{
		width:150px;
	}
</style>
{/literal}

{include file='_head.tpl'}

{popup_init src="`$cfg->js_src_path`overlib/overlib.js"}

<div class="toggler">
	<a href="javascript:toggle_visibility(document.getElementById('stretcher_f'));" style="display:block;"><h2 class="swTitle">{$lang_str.search_filter}:</h2></a>
	
	<div class="stretcher" id="stretcher_f">
		<div class="swForm swHorizontalForm">
		
		{$filter_form.start}
		<table border="0" cellspacing="0" cellpadding="0" align="center">
		<tr valign="bottom">
		<td><label for="at_name" id="luid">{$filter_label.at_name}</label></td>
		<td><label for="rich_type" id="lusrnm">{$filter_label.rich_type}</label></td>
		<td><label for="group" id="ldomain">{$filter_label.group}</label></td>
		<td><label for="desc" id="lfname">{$filter_label.desc}</label></td>
		</tr>
		
		<tr>
		<td>{$filter_form.at_name}</td>
		<td>{$filter_form.rich_type}</td>
		<td>{$filter_form.group}</td>
		<td>{$filter_form.desc}</td>
		</tr>
		
		<tr><td colspan="4">
		
			<div class="flagForm">
			<table border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
			<td><label for="d_flags_s"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_for_ser}>{$filter_label.d_flags_s}</a>:</label></td>
			<td>{$filter_form.d_flags_s_en}{$filter_form.d_flags_s}</td>
			</tr>
			<tr>
			<td><label for="d_flags_sw"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_for_serweb}>{$filter_label.d_flags_sw}</a>:</label></td>
			<td>{$filter_form.d_flags_sw_en}{$filter_form.d_flags_sw}</td>
			</tr>
			</table>
			</div>
		
			<div class="flagForm">
			<table border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
			<td><label for="priority_u"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_user}>{$filter_label.priority_u}</a>:</label></td>
			<td>{$filter_form.priority_u_en}{$filter_form.priority_u}</td>
			</tr>
			<tr>
			<td><label for="priority_d"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_domain}>{$filter_label.priority_d}</a>:</label></td>
			<td>{$filter_form.priority_d_en}{$filter_form.priority_d}</td>
			</tr>
			<tr>
			<td><label for="priority_g"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_global}>{$filter_label.priority_g}</a>:</label></td>
			<td>{$filter_form.priority_g_en}{$filter_form.priority_g}</td>
			</tr>
			</table>
			</div>
			
			<div class="flagForm">
			<table border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
			<td><label for="flags_m"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_multivalue}>{$filter_label.flags_m}</a>:</label></td>
			<td>{$filter_form.flags_m_en}{$filter_form.flags_m}</td>
			</tr>
			<tr>
			<td><label for="flags_r"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_registration}>{$filter_label.flags_r}</a>:</label></td>
			<td>{$filter_form.flags_r_en}{$filter_form.flags_r}</td>
			</tr>
			<tr>
			<td><label for="flags_e"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.at_hint_required}>{$filter_label.flags_e}</a>:</label></td>
			<td>{$filter_form.flags_e_en}{$filter_form.flags_e}</td>
			</tr>
			</table>
			</div>
		
			<div class="flagForm">
				<div style="width:110px;">
					<a href={$url_toggle_groups|escape}>{$lang_str.l_attr_grp_toggle}</a>
				</div>
			</div>
		</td></tr>
						
        <tr><td colspan="4" class="note">{$lang_str.filter_wildcard_note}</td></tr>
        
		<tr><td colspan="4" align="right">{$filter_form.okey}{$filter_form.f_clear}</td></tr>
		</table>
		{$filter_form.finish}
		</div>
		
	</div><!-- stretcher -->
</div><!-- toggler -->

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
	<td><label for="attr_group">{$lang_str.ff_att_group}:</label></td>
	<td>{$form.attr_group}{$form.attr_new_group}</td>
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

{setvar grp=""}
{foreach from=$attrs item='row' name='at'}
{*foreach from=$groups item='grp' name='grp'}
	{if $smarty.foreach.grp.first*}
	{if $smarty.foreach.at.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable alternatpoplinkstyle">
	<tr>
	<th class="alternatpoplinkstyle"><a href="{$url_sort_order|escape}"      class="swPopupLink" {popup text=$lang_str.at_hint_order}>{$lang_str.th_order}</a></th>
	<th class="alternatpoplinkstyle"><a href="{$url_sort_name|escape}"      >{$lang_str.th_att_name}</a></th>
	<th class="alternatpoplinkstyle"><a href="{$url_sort_rich_type|escape}" >{$lang_str.th_att_type}</a></th>
	<th class="alternatpoplinkstyle"><a href="{$url_sort_flags_r|escape}"    class="swPopupLink" {popup text=$lang_str.at_hint_registration}>R</a></th>
	<th class="alternatpoplinkstyle"><a href="{$url_sort_priority_u|escape}" class="swPopupLink" {popup text=$lang_str.at_hint_user        }>U</a></th>
	<th class="alternatpoplinkstyle"><a href="{$url_sort_priority_d|escape}" class="swPopupLink" {popup text=$lang_str.at_hint_domain      }>D</a></th>
	<th class="alternatpoplinkstyle"><a href="{$url_sort_priority_g|escape}" class="swPopupLink" {popup text=$lang_str.at_hint_global      }>G</a></th>
	<th class="alternatpoplinkstyle"><a href="{$url_sort_d_flags_s|escape}"  class="swPopupLink" {popup text=$lang_str.at_hint_for_ser     }>S</a></th>
	<th class="alternatpoplinkstyle"><a href="{$url_sort_d_flags_sw|escape}" class="swPopupLink" {popup text=$lang_str.at_hint_for_serweb  }>SW</a></th>
	<th class="alternatpoplinkstyle"><a href="{$url_sort_desc|escape}"       class="swPopupLink" {popup text=$lang_str.at_hint_label       }>{$lang_str.th_label}</a></th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}

	{if $show_groups and $grp != $row.group}{setvar grp=$row.group}
		<tr>
		<td colspan="12">{$lang_str.th_att_group}: {$row.group}</td>
    	<td align="center"><a href="{$row.url_grp_rename|escape}" class="actionsrow">{$lang_str.l_rename}</a></td>
		</tr>
	{/if}
{*foreach from=$attrs item='row' name='at'}
	{if $smarty.foreach.at.first}
	<tr>
	<td colspan="13">{$lang_str.th_att_group}: {$grp}</td>
	</tr>
	{/if}

{if $row.group==$grp*}
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
	<td align="center">{
		if $row.url_ext}<a href="{$row.url_ext|escape}" class="actionsrow">{$lang_str.l_extended}</a>{
		else}&nbsp;{
		/if}</td>
	<td align="center"><a href="{$row.url_edit|escape}" class="actionsrow">{$lang_str.l_edit}</a></td>
	<td align="center"><a href="{$row.url_dele|escape}" class="actionsrow" onclick="return confirmDelete(this, '{$lang_str.realy_want_you_delete_this_attr}')">{$lang_str.l_delete}</a></td>
	</tr>
{*/if}

{/foreach}
	{if $smarty.foreach.grp.last*}
	{if $smarty.foreach.at.last}
	</table>

	<div class="swNumOfFoundRecords">{$lang_str.displaying_records} {$pager.from} - {$pager.to} {$lang_str.from} {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_records_found}</div>
{/foreach}

<div id="orphanlinks">
<div>
    <a href="{url url='attr_types_import.php'}">{$lang_str.l_import_xml}</a> - 
    <a href="{url url=$url_export_xml|escape}">{$lang_str.l_export_xml}</a> - 
    <a href="{url url=$url_export_sql|escape}">{$lang_str.l_export_sql}</a></div>
</div>

<br />
<div class="swWarningBox"><h2>{$lang_str.warning}</h2>
{$lang_str.attr_type_warning}
</div>
<br />

<br />
{include file='_tail.tpl'}

