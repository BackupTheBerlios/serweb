{* Smarty *}
{* $Id: ur_finish.tpl,v 1.1 2004/08/10 17:33:50 kozlik Exp $ *}

{include file='_head.tpl'}

<p>
{$lang_str.reg_finish_thanks}.<br>
<br>
{$lang_str.reg_finish_app_forwarded}<br>
{$lang_str.reg_finish_confirm_msg}<br>
<br>
{$lang_str.reg_finish_sip_address} {$sip_address}.<br>
<br>
{$lang_str.reg_finish_questions}<br>
{$lang_str.reg_finish_infomail}<br>
<p>
<br>

<br>
<hr>
<div align="center"><a href="{url url='../index.php'}">{$lang_str.l_back_to_loginform}</a>.</div>
<hr>

{include file='_tail.tpl'}

