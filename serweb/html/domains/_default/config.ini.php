; <?php die( 'Please do not access this page directly.' ); ?>
; $Id: config.ini.php,v 1.1 2005/10/19 11:07:23 kozlik Exp $

;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;       basic local configuration options                       ;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
; you need to align these values to your local server settings 
;
; serweb will send confirmation emails and SIP IMs -- what sender 
; address should it claim ?
; should appear in them ?

mail_header_from = "registrar@mydomain.org"			
web_contact      = "sip:daemon@mydomain.org"

; info email address 
infomail         = "info@mydomain.org"

; email address for questions concerning registration 
regmail          = "registrar@mydomain.org"

; content of html <title> tag 
html_title       = "SIP Express Router - web interface"

; true if should be displayed heading like 'domain.org user management' or 
; 'domain.org admin interface' at all pages 
display_page_heading = true

; DOCTYPE of html pages. The default value is 'strict' for XHTML 1.0 Strict. 
; If your prolog.html and epilog.html is not coresponding with this, use 
; 'transitional' for HTML 4.0 Transitional or empty string for none DOCTYPE  		
html_doctype = "strict"

; user content of <head> tag. There can be some linked CSS or javascript or 
; <meta> tags for example CSS styles used in prolog.html
;     html_headers_1 = "<link REL='StyleSheet' HREF='http://www.mydomain.org/styles/my_styles.css' TYPE='text/css'>"
;  or some javascript
;     html_headers_2 = "<script language='JavaScript' src='http://www.mydomain.org/js/main.js'></script>"
;  uncoment following lines if you want add something
;	
; 
;		html_headers_1 = ''
;		html_headers_2 = ''
;		html_headers_3 = ''


;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;  Language settings                                            ;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

;  Default language to use, if not browser-defined or user-defined

default_lang = "en-utf-8"

;  Force: always use this language - must be defined in
;  config/config_lang.php
;
;  force_lang = "en-iso-8859-1" 

force_lang = ""

;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;  aliases generation                                           ;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;  Don't forget to align your SER routing script to it !        

;  If true, serweb will create numeric alias for new subscribers

create_numeric_alias_to_new_users = true

;  Nummerical aliases can be generated randomly or incrementaly.
;  Values 'rand' or 'inc' on the next line.
;
;  notice: if xxl module is loaded always 'rand' is used 

alias_generation = "inc"

;  initial nummerical alias for new subscriber - only if aliases
;  are generated incrementaly
  
first_alias_number = 82000

;  next lines are only for randomly generated aliases 

;  prefix of generated alias 
alias_prefix  = "8"
;  postfix of generated alias 
alias_postfix = ""
;  length of random part of alias 
alias_lenght  = 5
;  how many times will serweb try find unused alias number until error will occured 
alias_generation_retries = 10		
