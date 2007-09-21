{* Smarty *}
{* $Id: reg_domain_1.tpl,v 1.1 2007/09/21 14:21:21 kozlik Exp $ *}

{include file='_head.tpl'}


{*if $action == "pass_was_sended"}
<div class="swForgotPassw">
	<h2><font face="Arial" color="#000000">{$lang_str.forgot_pass_head}</font></h2>
	{$lang_str.forgot_pass_sended}
</div>

{else*}

<div class="rbroundboxB" style="margin: 1em auto; width: 60%;">
    <div class="rbtopB">
    	<h1>{$lang_str.have_a_domain_head}</h1>
    </div>

    <div class="rbcontentwrapB">
        <div class="rbcontentB">
        	<p>{$lang_str.have_a_domain_introduction|replace:'<srv_host>':"<strong>$srv_host</strong>"|replace:'<srv_port>':"<strong>$srv_port</strong>"}</p>
        	<p>{$lang_str.have_a_domain_introduction2}</p>
            <ol>
                <li>{$lang_str.have_a_domain_step1}</li>
                <li>{$lang_str.have_a_domain_step2}</li>
            </ol>
        	<p>{$lang_str.have_a_domain_introduction3}</p>
        </div>
    </div>

    <div class="rbbotB">
    <div><div>&nbsp;</div></div>
    </div>
</div>

<br />
<div class="swForm swHorizontalForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td><label for="domainname">{$lang_str.ff_domain}:</label></td>
		<td>{$form.domainname}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><br />{$form.okey}</td>
	</table>
{$form.finish}
</div>
{*/if*}

<br />
<div class="swBackToMainPage"><a href="{url url='../index.php'}">{$lang_str.l_back_to_loginform}</a></div>
{include file='_tail.tpl'}

