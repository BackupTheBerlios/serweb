<?
/*
 * $Id: config.php,v 1.3 2004/05/06 19:00:54 kozlik Exp $
 */

		/* ------------------------------------------------------------*/
		/*      basic local configuration options                      */
		/* ------------------------------------------------------------*/
		/* you need to align these values to your local server settings */

		/* serweb will send confirmation emails and SIP IMs -- what sender 
		   address should it claim ?
		   should appear in them ?
		*/
		$this->mail_header_from="registrar@mydomain.org";			
		$this->web_contact="sip:daemon@mydomain.org";

		/* info email address */
		$this->infomail	=	"info@mydomain.org";
		
		/* email address for questions concerning registration */
		$this->regmail	=	"registrar@mydomain.org";

		/* content of html <title> tag */
		$this->html_title="SIP Express Router - web interface";

		/* DOCTYPE of html pages. The default value is 'strict' for XHTML 1.0 Strict. If your prolog.html and epilog.html
			is not coresponding with this, use 'transitional' for HTML 4.0 Transitional or empty string for none DOCTYPE  */		
		$this->html_doctype="strict";

		/* user content of <head> tag. There can be some linked CSS or javascript or <meta> tags
		   for example CSS styles used in prolog.html
		      $this->html_headers[]='<link REL="StyleSheet" HREF="http://www.mydomain.org/styles/my_styles.css" TYPE="text/css">';
		   or some javascript
		      $this->html_headers[]='<script language="JavaScript" src="http://www.mydomain.org/js/main.js"></script>';
		   uncoment following lines if you want add something
		*/	
 
//		$this->html_headers[]='';
//		$this->html_headers[]='';
//		$this->html_headers[]='';

		/* initial nummerical alias for new subscriber -- don't forget to
		   align your SER routing script to it !
		*/
		$this->first_alias_number=82000;

		/* ------------------------------------------------------------*/
		/* text														   */
		/* ------------------------------------------------------------*/
		/* human-readable text containing messages displayed to users
		   in web or sent by email; you may need to hire a lawyer ,
		   a word-smith, a diplomat or a translator to get it right :)
		*/


		/* text of password-reminder email */
		$this->forgot_pass_subj="your login information";
		$this->mail_forgot_pass="Hello,\n".
			"now you can access to your account at the folowing URL within 1 hour:\n".
			$config->root_uri.$config->user_pages_path."my_account.php?#session#\n\n".
			"We recommend change your password after you login\n\n";

		/* text of confirmation email sent during account registration  */
		$this->register_subj="Your ".$config->domain." Registration";
		$this->mail_register=
			"Thank you for registering with ".$config->domain.".\n\n".
			"We are reserving the following SIP address for you: #sip_address#\n\n".
			"To finalize your registration please check the following URL within ".
			"24 hours:\n".
			$config->root_uri.$config->user_pages_path."reg/confirmation.php?nr=#confirm#\n\n".
			"(If you confirm later you will have to re-register.)\n\n".
			"Windows Messenger users may look at additional configuration hints at\n".
			"http://www.iptel.org/phpBB/viewtopic.php?topic=11&forum=1&0\n";

		/* terms and conditions as they appear on the subscription webpage */
		$this->terms_and_conditions=
			"BY PRESSING THE 'I ACCEPT' BUTTON, YOU (HEREINAFTER THE 'USER') ARE ".
			"STATING THAT YOU AGREE TO ACCEPT AND BE BOUND BY ALL OF THE TERMS AND ".
			"CONDITIONS OF THIS AGREEMENT.  DO NOT PROCEED IF YOU ARE UNABLE TO AGREE".
			" TO THE TERMS AND CONDITIONS OF THIS AGREEMENT. THESE TERMS AND CONDITIONS ".
			"OF SERVICE FOR USE OF ".$config->domain." SIP SERVER (THE 'AGREEMENT')".
			" CONSTITUTE A LEGALLY BINDING CONTRACT BETWEEN ".$config->domain.
			" AND THE ENTITY THAT AGREES TO AND ACCEPTS THESE TERMS AND CONDITIONS. ".
			"ACCESS TO ".$config->domain."'s SESSION INITIATION PROTOCOL SERVER ".
			"('SIP SERVER') IS BEING PROVIDED ON AN 'AS IS' AND 'AS AVAILABLE' BASIS, ".
			"AND ".$config->domain." MAKES NO REPRESENTATIONS OR WARRANTIES OF ANY ".
			"KIND, WHETHER EXPRESS OR IMPLIED, WITH RESPECT TO USER'S ACCESS OF THE ".
			"SIP SERVER, INCLUDING BUT NOT LIMITED TO WARRANTIES OF MERCHANTABILITY, ".
			"NONINFRINGEMENT, TITLE OR FITNESS FOR A PARTICULAR PURPOSE. FURTHER, ".
			$config->domain." MAKES NO REPRESENTATIONS OR WARRANTIES THAT THE SIP ".
			"SERVER, OR USER'S ACCESS THERETO, WILL BE AVAILABLE AT ANY GIVEN TIME, ".
			"OR WILL BE FREE FROM ERRORS, DEFECTS, OMISSIONS, INACCURACIES, OR FAILURES".
			" OR DELAYS IN DELIVERY OF DATA. USER ASSUMES, AND ".$config->domain.
			" DISCLAIM, TOTAL RISK, RESPONSIBILITY, AND LIABILITY FOR USER'S ACCESS TO ".
			"AND USE OF THE SIP SERVER.\n\n".
			"Access to ".$config->domain." SIP Server is being provided on a ".
			"non-exclusive basis. User acknowledges and understands that ".
			$config->domain." SIP site is in a developmental stage and that ".
			$config->domain." makes no guarantees regarding the availability or ".
			"functionality thereof. User may not sublicense its access rights to the ".
			"SIP Server to any third party. \n\n".
			"USER AGREES TO INDEMNIFY, DEFEND AND HOLD iptel.org, ITS AFFILIATES, ".
			"DIRECTORS, OFFICERS, EMPLOYEES, AGENTS AND LICENSORS HARMLESS FROM AND ".
			"AGAINST ANY AND ALL CLAIMS, ACTIONS, EXPENSES, LOSSES, AND LIABILITIES ".
			"(INCLUDING COURTS COSTS AND REASONABLE ATTORNEYS' FEES), ".
			"ARISING FROM OR RELATING TO THIS AGREEMENT INCLUDING USER'S ACCESS TO ".
			"AND USE OF THE SIP SERVER TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW,".
			" IN NO EVENT SHALL ".$config->domain." OR ANY OF ITS LICENSORS, BE LIABLE ".
			"FOR ANY INDIRECT, SPECIAL, PUNITIVE, EXEMPLARY, OR CONSEQUENTIAL DAMAGES, ".
			"ARISING OUT OF THE ACCESS TO OR USE OF OR INABILITY TO ACCESS OR USE THE ".
			"SIP SERVER, OR THAT RESULT FROM MISTAKES, OMISSIONS, INTERRUPTIONS, ".
			"DELETIONS OF FILES, ERRORS, DEFECTS, DELAYS IN TRANSMISSION OR OPERATION OR ".
			"ANY FAILURE OF PERFORMANCE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH ".
			"DAMAGES. \n\n".
			"If User commits, in ".$config->domain."'s  sole determination, a default ".
			"of these terms and conditions, ".$config->domain." may immediately ".
			"terminate User's access to the SIP Server. Furthermore, ".$config->domain.
			" reserves the right to discontinue offering access to the SIP Server at any ".
			"time. \n\n".

			"User may not assign its rights hereunder without the prior written ".
			"consent of ".$config->domain.". User agrees to comply with all laws, ".
			"regulations and other legal requirements that apply to these terms and ".
			"conditions.  \n\n".
			"If any provision of this Agreement is held to be unenforceable for any ".
			"reason, such provision shall be reformed only to the extent necessary to ".
			"comply with applicable laws, and the remainder shall remain in full force ".
			"and effect. \n\n".
			"Any failure of ".$config->domain." to enforce any provision of this ".
			"Agreement shall not constitute a waiver of any rights under such provision ".
			"or any other provision of this Agreement. \n\n".
			"USER ACKNOWLEDGES THAT IT HAS READ THIS AGREEMENT, UNDERSTANDS IT, AND ".
			"AGREES THAT IT IS THE COMPLETE AND EXCLUSIVE STATEMENT OF THE ENTIRE ".
			"AGREEMENT BETWEEN COMPANY AND ".$config->domain." WITH RESPECT TO THE ".
			"SUBJECT MATTER HEREIN, AND SUPERSEDES ALL PRIOR AND CONTEMPORANEOUS ".
			"PROPOSALS, DISCUSSIONS, AGREEMENTS, UNDERSTANDINGS, AND COMMUNICATIONS, ".
			"WHETHER WRITTEN OR ORAL AND MAY BE AMENDED ONLY IN A WRITING EXECUTED BY ".
			"BOTH USER AND ".$config->domain.". \n\n";
 
?>