{* Smarty *}
{* $Id: a_attr_types_int.tpl,v 1.1 2006/12/07 13:47:27 kozlik Exp $ *}

{include file='_head.tpl'}
{popup_init src="`$cfg->js_src_path`overlib/overlib.js"}

<h2 class="swTitle">{$lang_str.at_int_title}</h2>

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="at_int_min"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.ff_at_int_min_hint}>{$lang_str.ff_at_int_min}</a>:</label></td>
	<td>{$form.at_int_min}</td>
	</tr>
	<tr>
	<td><label for="at_int_max"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.ff_at_int_max_hint}>{$lang_str.ff_at_int_max}</a>:</label></td>
	<td>{$form.at_int_max}</td>
	</tr>
	<tr>
	<td><label for="at_int_err"><a href="javascript:void(0);" class="swPopupLink" {popup text=$lang_str.ff_at_int_err_hint}>{$lang_str.ff_at_int_err}</a>:</label></td>
	<td>{$form.at_int_err}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>
<br />


<div class="swBackToMainPage"><a href="{$url_back}">{$lang_str.l_back_to_editing_attributes}</a></div>

<br>
{include file='_tail.tpl'}

