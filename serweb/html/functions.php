<?php
/**
 * Miscellaneous functions and variable definitions
 * 
 * @author    Karel Kozlik
 * @version   $Id: functions.php,v 1.102 2012/01/02 11:57:53 kozlik Exp $ 
 * @package   serweb
 */ 


$reg_validate_email="^[^@]+@[^.]+\.[^,;@ ]+$";

$name_of_month[1]="Jan";
$name_of_month[2]="Feb";
$name_of_month[3]="Mar";
$name_of_month[4]="Apr";
$name_of_month[5]="May";
$name_of_month[6]="Jun";
$name_of_month[7]="Jul";
$name_of_month[8]="Aug";
$name_of_month[9]="Sep";
$name_of_month[10]="Oct";
$name_of_month[11]="Nov";
$name_of_month[12]="Dec";

$sip_status_messages_array[100]="100 Trying";
$sip_status_messages_array[180]="180 Ringing";
$sip_status_messages_array[181]="181 Call Is Being Forwarded";
$sip_status_messages_array[182]="182 Queued";
$sip_status_messages_array[183]="183 Session Progress";
$sip_status_messages_array[200]="200 OK";
$sip_status_messages_array[300]="300 Multiple Choices";
$sip_status_messages_array[301]="301 Moved Permanently";
$sip_status_messages_array[302]="302 Moved Temporarily";
$sip_status_messages_array[305]="305 Use Proxy";
$sip_status_messages_array[380]="380 Alternative Service";
$sip_status_messages_array[400]="400 Bad Request";
$sip_status_messages_array[401]="401 Unauthorized";
$sip_status_messages_array[402]="402 Payment Required";
$sip_status_messages_array[403]="403 Forbidden";
$sip_status_messages_array[404]="404 Not Found";
$sip_status_messages_array[405]="405 Method Not Allowed";
$sip_status_messages_array[406]="406 Not Acceptable";
$sip_status_messages_array[407]="407 Proxy Authentication Required";
$sip_status_messages_array[408]="408 Request Timeout";
$sip_status_messages_array[410]="410 Gone";
$sip_status_messages_array[413]="413 Request Entity Too Large";
$sip_status_messages_array[414]="414 Request-URI Too Long";
$sip_status_messages_array[415]="415 Unsupported Media Type";
$sip_status_messages_array[416]="416 Unsupported URI Scheme";
$sip_status_messages_array[420]="420 Bad Extension";
$sip_status_messages_array[421]="421 Extension Required";
$sip_status_messages_array[423]="423 Interval Too Brief";
$sip_status_messages_array[480]="480 Temporarily Unavailable";
$sip_status_messages_array[481]="481 Call/Transaction Does Not Exist";
$sip_status_messages_array[482]="482 Loop Detected";
$sip_status_messages_array[483]="483 Too Many Hops";
$sip_status_messages_array[484]="484 Address Incomplete";
$sip_status_messages_array[485]="485 Ambiguous";
$sip_status_messages_array[486]="486 Busy Here";
$sip_status_messages_array[487]="487 Request Terminated";
$sip_status_messages_array[488]="488 Not Acceptable Here";
$sip_status_messages_array[491]="491 Request Pending";
$sip_status_messages_array[493]="493 Undecipherable";
$sip_status_messages_array[500]="500 Server Internal Error";
$sip_status_messages_array[501]="501 Not Implemented";
$sip_status_messages_array[502]="502 Bad Gateway";
$sip_status_messages_array[503]="503 Service Unavailable";
$sip_status_messages_array[504]="504 Server Time-out";
$sip_status_messages_array[505]="505 Version Not Supported";
$sip_status_messages_array[513]="513 Message Too Large";
$sip_status_messages_array[600]="600 Busy Everywhere";
$sip_status_messages_array[603]="603 Decline";
$sip_status_messages_array[604]="604 Does Not Exist Anywhere";
$sip_status_messages_array[606]="606 Not Acceptable";


/* Hack making serweb to work in PHP4
   for details see: http://acko.net/blog/php-clone 
 */
if (version_compare(phpversion(), '5.0') < 0) {
    eval('function clone($object) { return $object; }');
}


/**
 *	Defining regular expressions
 * 
 *	@package    serweb
 */ 
class Creg{

	var $alphanum;
	var $mark;
	var $reserved;
	var $unreserved;
	var $escaped;
	var $user_unreserved;
	var $user;

	var $port;
	var $hex4;
	var $hexseq;
	var $hexpart;
	var $ipv4address;
	var $ipv6address;
	var $ipv6reference;

	var $toplabel;
	var $domainlabel;
	var $hostname;
	var $host;

	var $token;
	var $param_unreserved;
	var $paramchar;
	var $pname;
	var $pvalue;
	var $uri_parameter;
	var $uri_parameters;

	var $address;
	var $sip_address;

	function Creg(){
		global $config, $reg_validate_email;
		
		$this->SP=" ";
		$this->HTAB="\t";
		$this->alphanum="[a-zA-Z0-9]";
		$this->mark="[-_.!~*'()]";
		$this->reserved="[;/?:@&=+$,]";
		$this->unreserved="(".$this->alphanum."|".$this->mark.")";
		$this->escaped="(%[0-9a-fA-F][0-9a-fA-F])";
		$this->user_unreserved="[&=+$,;?/]";
		$this->user="(".$this->unreserved."|".$this->escaped."|".$this->user_unreserved.")+";

		$this->port="[0-9]+";
		$this->hex4="([0-9a-fA-F]{1,4})";
		$this->hexseq="(".$this->hex4."(:".$this->hex4.")*)";
		$this->hexpart="(".$this->hexseq."|(".$this->hexseq."::".$this->hexseq."?)|(::".$this->hexseq."?))";
		$this->ipv4address="([0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3})";
		$this->ipv6address="(".$this->hexpart."(:".$this->ipv4address.")?)";
		$this->ipv6reference="(\\[".$this->ipv6address."])";

        $this->utf8_cont = "[\x80-\xbf]";
        $this->utf8_nonascii = "([\xc0-\xdf]".$this->utf8_cont.")|".
                               "([\xe0-\xef]".$this->utf8_cont."{2})|".
                               "([\xf0-\xf7]".$this->utf8_cont."{3})|".
                               "([\xf8-\xfb]".$this->utf8_cont."{4})|".
                               "([\xfc-\xfd]".$this->utf8_cont."{5})";

		/* toplabel is the name of top-level DNS domain ".com" -- alphanum only
		   domainlabel is one part of the dot.dot name string in a DNS name ("iptel");
		     it must begin with alphanum, can contain special characters (-) and end
			 with alphanums
		   hostname can include any number of domain lables and end with toplabel
		*/
		$this->toplabel="([a-zA-Z]|([a-zA-Z](-|".$this->alphanum.")*".$this->alphanum."))";
		$this->domainlabel="(".$this->alphanum."|(".$this->alphanum."(-|".$this->alphanum.")*".$this->alphanum."))";
		$this->hostname="((".$this->domainlabel."\\.)*".$this->toplabel."\\.?)";
		$this->host="(".$this->hostname."|".$this->ipv4address."|".$this->ipv6reference.")";

        // domain name according to RFC 1035, section 2.3.1
		$this->domainname="((".$this->toplabel."\\.)*".$this->toplabel.")";

		$this->token="(([-.!%*_+`'~]|".$this->alphanum.")+)";
		$this->param_unreserved="\\[|]|[/:&+$]";
		$this->paramchar="(".$this->param_unreserved."|".$this->unreserved."|".$this->escaped.")";
		$this->pname="((".$this->paramchar.")+)";
		$this->pvalue="((".$this->paramchar.")+)";

        $this->method="((INVITE)|(ACK)|(OPTIONS)|(BYE)|(CANCEL)|(REGISTER)|".$this->token.")";

        $this->transport_param="(transport=((udp)|(tcp)|(sctp)|(tls)|".$this->token."))";
        $this->user_param="(user=((phone)|(ip)|".$this->token."))";
        $this->method_param="(method=".$this->method.")";
        $this->ttl_param="(ttl=[0-9]{1,3})";
        $this->maddr_param="(maddr=".$this->host.")";
        $this->lr_param="(lr)";
        $this->other_param="(".$this->pname."(=".$this->pvalue.")?)";

        $this->uri_parameter="(".$this->transport_param."|".$this->user_param."|".
                    $this->method_param."|".$this->ttl_param."|".$this->maddr_param."|".
                    $this->lr_param."|".$this->other_param.")";
        $this->uri_parameters="((;".$this->uri_parameter.")*)";

        $this->address="(".$this->user."@)?".$this->host."(:".$this->port.")?".$this->uri_parameters;

        /** regex matching sip uri */
        $this->sip_address="[sS][iI][pP]:".$this->address;
        /** regex matching sips uri */
        $this->sips_address="[sS][iI][pP][sS]:".$this->address;
        /** regex matching sip or sips uri */
        $this->sip_s_address="[sS][iI][pP][sS]?:".$this->address;



		/** reg.exp. validating sip header name */
		$this->sip_header_name  = "(\\[|]|\\\\|[-\"'!@#$%^&*()?*+,./;<>=_{}|~A-Za-z0-9])+";
		/** reg.exp. validating value of sip header */
		$this->sip_header_value = "(\\[|]|\\\\|[-\"'!@#$%^&*()?*+,./;<>=_{}|~A-Za-z0-9:])+";

		/** reg.exp. validating sip header name 
		 *  @deprec  this is some old not correct defintion replaced by $this->sip_header_name
         */
		$this->sip_header="([^][ ()<>@,;:\\\\=\"/?{}]+)";
		/** same regex, but for use in javascript 
		 *  @deprec  this is some old not correct defintion replaced by $this->sip_header_name
         */
		$this->sip_header_js="([^\\]\\[ ()<>@,;:\\\\=\"/?{}]+)";

		
		/** regex for phonenumber which could contain some characters as: - / <space> this characters should be removed */		
		$this->phonenumber = $config->phonenumber_regex;		// "\\+?[-/ 1-9]+"

		/** strict phonenumber - only numbers and optional initial + */
		$this->phonenumber_strict = $config->strict_phonenumber_regex;		// "\\+?[1-9]+"
		
		$this->email = $reg_validate_email;

		/** regex matching reason phrase from status line */
		$this->reason_phrase = "(".$this->reserved."|".$this->unreserved."|".
                            $this->escaped."|".$this->utf8_nonascii."|".
                            $this->SP."|".$this->HTAB.")*";

        /** like reason_phrase, but matching only ascii chars */
		$this->reason_phrase_ascii = "(".$this->reserved."|".$this->unreserved."|".
                            $this->escaped."|".$this->SP."|".$this->HTAB.")*";

		/** Regex matching reason phrase from status line.  
		 *  This is javascript version of the above. This uses interval
		 *  of unicode character codes instead of utf8_nonascii regexp.
		 */
		$this->reason_phrase_js = "(".$this->reserved."|".$this->unreserved."|".
                            $this->escaped."|[\\u0080-\\uFFFF]|".
                            $this->SP."|".$this->HTAB.")*";

        /** like reason_phrase_js, but matching only ascii chars */
		$this->reason_phrase_ascii_js = "(".$this->reserved."|".$this->unreserved."|".
                            $this->escaped."|".$this->SP."|".$this->HTAB.")*";

        $this->global_hex_digits = "\\+[0-9]{1,3}(".$this->phonedigit_hex.")*";

        /** regex matching value of rn-context uri param by RFC4636 */
        $this->rn_descriptor = "(".$this->hostname.")|(".$this->global_hex_digits.")";


        /** regex matching natural number */
        $this->natural_num = "[0-9]+";
	}

    /**
     *	Attempts to return a reference to a Creg instance.
     *	Only creating a new instance if no Creg instance currently exists.
     *
     *	@return object Creg		instance of Creg class
     *	@static
     *	@access public 
     */
	function &singleton(){
		static $instance;
		
		if(! isset($instance)) $instance = new Creg();
		
		return $instance;
	}

	/**
	 * parse domain name from sip address
	 *
	 * @param string $sip sip address
	 * @return string domain name
	 */
	function get_domainname($sip){
		return ereg_replace($this->sip_s_address,"\\5", $sip);
	}


	/** return javascript which do the same as method {@link get_domainname} 
	 *
	 *	@param string $in_var 	name of js variable with string containing sip uri 
	 *	@param string $out_var	name of js variable to which hostpart will be stored 
	 *	@return string 			line of javascript code
	 */
	function get_domainname_js($in_var, $out_var){
		return $out_var." = ".$in_var.".replace(/".str_replace('/','\/',$this->sip_s_address)."/, '\$5')";
	}

	/**
	 * parse user name from sip address
	 *
	 * @param string $sip sip address
	 * @return string username
	 */
	function get_username($sip){

		$uname=ereg_replace($this->sip_s_address,"\\1", $sip);

		//remove the '@' at the end
		return substr($uname,0,-1);
	}
	
	/**
	 * parse port number from sip address
	 *
	 * @param string $sip sip address
	 * @return string port
	 */
	function get_port($sip){
		ereg($this->sip_s_address, $sip, $regs);
		
		if (!empty($regs[38])){
			//remove the ':' at the begining
			return substr($regs[38], 1);
		}
		
		return false;
	}
	
	/**
	 * return regular expression for validate hostname
	 *
	 * @return string 
	 */
	function get_hostname_regex(){
		return $this->host;
	}
	
	/**
	 *	Parse parameters from sip uri
	 *
	 *	@param string $sip	sip uri
	 *	@return array 		associative array of parameters and their values
	 */
	function get_parameters($sip){
		$params = explode(';', $sip);
		//first element is containing part of sip uri before parameters
		unset($params[0]);
		
		$out = array();
		if (is_array($params)){
			foreach($params as $param){
				$p = explode('=', $param, 2);
				$out[$p[0]] = $p[1];
			}
		}
		
		return $out;
	}
	
	/** converts string which can be accepted by regex $this->phonenumber to string which can be accepted by regex $this->phonenumber_strict 
	 *
	 *	@param string $phonenumber
	 *	@return string
	 */
	function convert_phonenumber_to_strict($phonenumber){
		return str_replace(array('-', '/', ' ', '(', ')'), "", $phonenumber);
	}
	
	/** return javascript which do the same as method {@link convert_phonenumber_to_strict} 
	 *
	 *	@param string $in_var 	name of js variable with string for conversion 
	 *	@param string $out_var	name of js variable to which converted string will be stored 
	 *	@return string 			line of javascript code
	 */
	function convert_phonenumber_to_strict_js($in_var, $out_var){
		return $out_var." = ".$in_var.".replace(/[-\\/ ()]/g, '')";
	}
	
	/**
	 *	check if given string is in format of IPv4 address
	 *	
	 *	@param	string	$adr	IPv4 address
	 *	@return	bool
	 */
	function is_ipv4address($adr){
		if (ereg("^".$this->ipv4address."$", $adr)) return true;
		else return false;
	}
	
	/**
	 *	check if all parts of given IPv4 address are in range 0-255
	 *	
	 *	@param	string	$adr	IPv4 address
	 *	@return	bool
	 */
	function ipv4address_check_part_range($adr){
		// check if given string is IPv4 address
		if (!$this->is_ipv4address($adr)) return false;
		
		$parts = explode(".", $adr);
		
		foreach ($parts as $v){
			if (!is_numeric($v)) return false;		// part is not numeric
		
			$v = (int)$v;
			if ($v<0 or $v>255) return false;		// wrong range
		}
		
		return true;
	}


	/**
	 *	Return javascript function checking range of parts of IPV4 address in SIP URI
	 *	
	 *	@param	string	$name	name of javascript function which will be generated
	 *	@return	string
	 */
	function ipv4address_check_part_range_js_fn($name){
		$js = "
			function ".$name."(adr){

				// parse host part from SIP uri			
				var re = /".str_replace('/','\/',$this->sip_s_address)."/;
				var hostname = adr.replace(re, '\$5');

				//check if host part is in format of IPv4 address				
				var re = /".str_replace('/','\/',"^".$this->ipv4address."$")."/;
				if (re.test(hostname)){
				
					// split address to parts
					var ipv4_parts = hostname.split('.');
					
					for (var i=0; i < ipv4_parts.length; i++){
						var int_part = Number(ipv4_parts[i]);
						if (int_part == Number.NaN){
							return false;			// part is not numeric
						}
					
						if (int_part < 0 || int_part > 255){
							return false;			// wrong range
						}
					}
				}
				return true;
			}
		";
		return $js;
	}

	/**
	 *	Check range of TCP/UDP port
	 *	
	 *	@param	string	$port
	 *	@return	bool
	 */
	function port_check_range($port){
		if (!is_numeric($port)) return false;
		
		$port = (int)$port;
		if ($port < 1 or $port > 65535) return false;
		
		return true;
	}

	/**
	 *	Return javascript function checking range of TCP/UDP port inside SIP uri
	 *	
	 *	@param	string	$name	name of javascript function which will be generated
	 *	@return	string
	 */
	function port_check_range_js_fn($name){
		$js = "
			function ".$name."(adr){

				/* parse port from sip uri */

				if      (adr.substr(0,4).toLowerCase() == 'sip:')  adr = adr.substr(4); //strip initial 'sip:'
				else if (adr.substr(0,5).toLowerCase() == 'sips:') adr = adr.substr(5); //strip initial 'sips:'
				else    return false; //not valid uri
				

				var ipv6 = 0;
				var portpos = null;
				var ch;
			
				for (var i=0; (i < adr.length) && (portpos == null); i++){
					ch = adr.substr(i, 1);

					switch (ch){
					case '[':  ipv6++; break;
					case ']':  ipv6--; break;
					case ':':
					           if (!ipv6){ //semicolon is not part of ipv6 address
									portpos = i;  //position of port inside address string
									break;
							   } 
					}
				}

				if (portpos == null) return true;	//no port in the uri

				portpos++; //move after the semicolon
				var portlen = 0;

				for (var i=portpos; i < adr.length; i++){
					ch = adr.substr(i, 1);

					if (ch<'0' || ch>'9') break;
					portlen++;
				}

				if (portlen == 0) return false;	//no port in uri, but it contains semicolon

				var port = Number(adr.substr(portpos, portlen));
				if (port == Number.NaN)		return false; //should never happen, but to be sure...
					
				if (port < 1 || port > 65535){
							return false;	//invalid port range
				}
				
				return true;
			}
		";
		return $js;
	}

    /**
     *  check if given IPv4 address is valid netmask
     *  
     *  @param  string  $adr    IPv4 address
     *  @return bool
     */
    function check_netmask($adr){
        // check if given string is IPv4 address
        if (!$this->is_ipv4address($adr)) return false;
        
        $parts = explode(".", $adr);
        
        $starting = true;
        foreach ($parts as $v){
            if (!is_numeric($v)) return false;      // part is not numeric
        
            $v = (int)$v;
            if ($starting){
                /* allow ones at the begining */ 
                if ($v == 255) continue;
                if (!in_array($v, array(254, 252, 248, 240, 224, 192, 128, 0))){
                    return false;
                }
                $starting = false;
            }
            /* allow only zeros at the end */
            elseif ($v != 0) return false;
        }
        
        return true;
    }


    /**
     *  check if given IPv4 address is network address of network with given netmask
     *  
     *  @param  string  $ip         IPv4 address
     *  @param  string  $netmask    IPv4 address
     *  @return bool
     */
    function check_network_address($ip, $netmask){
        // check if given string is IPv4 address
        if (!$this->is_ipv4address($ip)) return false;
        if (!$this->check_netmask($netmask)) return false;
        
        $parts_ip = explode(".", $ip);
        $parts_mask = explode(".", $netmask);
        
        for ($i=0; $i<4; $i++){
            $ip = (int)$parts_ip[$i];
            $mask = (int)$parts_mask[$i];
            if ($ip != ($ip & $mask)) return false;
        }
        
        return true;
    }
    
    /**
     *  validate regular expression
     *
     *  Validate syntax of regular expression (uses PCRE - Perl Compatible  
     *  Regular Expressions)
     *
     *  @param  string  $pattern    RegEx String pattern to validate
     *  @param  string  $format     pcre/posix     
     *  @return bool                true if valid regex, false if not
     *
     */
    function check_regexp($pattern, $format = "pcre"){
        global $config;

        switch ($format){
        case "pcre":
            if ($config->external_regexp_validator_pcre){
            	exec($config->external_regexp_validator_pcre." ".escapeshellarg($pattern), $output);
            
            	if (isset($output[0]) and $output[0]=="1"){
            		return true;
            	}
            	return false;
            }

            /* 
               external validator is not set
               some php validation should be implemented there 
             */

            break;

        case "posix":
            if ($config->external_regexp_validator_posix){
            	exec($config->external_regexp_validator_posix." ".escapeshellarg($pattern), $output);
            
            	if (isset($output[0]) and $output[0]=="1"){
            		return true;
            	}
            	return false;
            }

            /* 
               external validator is not set
               some php validation should be implemented there 
             */

            break;
        default:
            die(__FILE__.":".__LINE__." check_regexp: unknown value of format attribute: '".$format."'");
        }        
                
        return true;
    }
    
    /**
     *  Check whether the argument is natural number (as integer or it's 
     *  string representation). Natural number is non-negative integer.    
     */
    function is_natural_num($val){
        if (ereg("^".$this->natural_num."$", $val)) return true;
        return false;
    }
}


/**
 * send email
 *
 * same as PHP function mail(), but additionaly is set header "From" by 
 * config option {@link $config->mail_header_from}
 *
 * @param string $to address of recipient
 * @param string $text email body
 * @param array $headers email headers (associative array)
 * @return boolean TRUE if the mail was successfully accepted for delivery, FALSE otherwise
 *
 * @todo add error logging/reporting
 */
 
function send_mail($to, $text, $headers = array()){
	global $config;

	/* if subject isn't defined */
	if (!isset($headers['subject'])) $headers['subject'] = "";

	/* add from header */
	if (!isset($headers['from'])) $headers['from'] = $config->mail_header_from;

	/* convert associative array to string */
	$str_headers="";
	foreach ($headers as $k=>$v){
		/* exclude header 'subject'. It is given throught another parameter of function */
		if ($k=='subject') continue;
		$str_headers .= ucfirst($k).": ".$v."\n";
	}

	/* get charset */
	$charset = null;
	if (isset($headers['content-type']) and 
	    eregi("charset=([-a-z0-9]+)", $headers['content-type'], $regs)){
		
		$charset = $regs[1];
	}

	if (!function_exists('imap_8bit')){
		ErrorHandler::log_errors(PEAR::raiseError("Can not send mail. IMAP extension for PHP is not installed."));
		return false;
	}

	/* add information about charset to the header */
	if ($charset)
		$headers['subject'] = "=?".$charset."?Q?".imap_8bit($headers['subject'])."?=";

	/* enable tracking errors */
	ini_set('track_errors', 1);

	/* send email */
	@$a= mail($to, $headers['subject'], $text, $str_headers);

	/* if there was error during sending mail and error message is present, log the error */
	if (!$a and !empty($php_errormsg)){
		ErrorHandler::log_errors(PEAR::raiseError(html_entity_decode($php_errormsg)));
	}

	return $a;
}

/**
 *	Write command to FIFO
 *
 *	@param string $fifo		fifo command
 *	@param array $errors	if some error occur during calling this function is writed here
 *	@param string $status	put result of command execution here
 *	$return string			result of the fifo command, FALSE on error
 */
function write2fifo($fifo_cmd, &$errors, &$status){
	global $config;

	/* check if fifo is running */
	if (!file_exists($config->fifo_server) or 
	     filetype($config->fifo_server)!="fifo"){

		log_errors(PEAR::raiseError("FIFO not running or bad path to it", NULL, NULL, 
		           NULL, "fifo path:".$config->fifo_server), $errors);
		return false;
	}


	/* open fifo now */
	$fifo_handle=fopen( $config->fifo_server, "w" );
	if (!$fifo_handle) {
		$errors[]="sorry -- cannot open write fifo"; return false;
	}

	/* create fifo for replies */
	@system("mkfifo -m 666 ".$config->reply_fifo_path );


	/* add command separator */
	$fifo_cmd=$fifo_cmd."\n";

	/* write fifo command */
	if (fwrite( $fifo_handle, $fifo_cmd)==-1) {
	    @unlink($config->reply_fifo_path);
	    @fclose($fifo_handle);
		$errors[]="sorry -- fifo writing error"; return false;
	}
	@fclose($fifo_handle);

	/* read output now */
	@$fp = fopen( $config->reply_fifo_path, "r");
	if (!$fp) {
	    @unlink($config->reply_fifo_path);
		$errors[]="sorry -- reply fifo opening error"; return false;
	}

	$status=fgets($fp,256);
	if (!$status) {
	    @unlink($config->reply_fifo_path);
		$errors[]="sorry -- reply fifo reading error"; return false;
	}
	

	$rd="";
	while (!feof($fp)) {
		$rd.=fread($fp,8192);
	}
	@unlink($config->reply_fifo_path);
	
	return $rd;
}

/**
 *	Filter result of calling fifo command t_uac_dlg. Is used by function {@link click_to_dial}
 *
 * 	@param	string $in
 *	@return	string
 *	@access	private
 */
function filter_fl($in){
	$line=0;
	$result="";
	
	$in_arr=explode("\n", $in);
	
	foreach($in_arr as $row){
		$line++;
		
		//skip body
		if ($row=="") break;
		
		// uri and outbound uri at line 2,3: copy and paste
		if ($line==1 || $line==2) {
			$result.=$row."\n";
			continue;
		}
		
		// line 4: Route; empty if ".", copy and paste otherwise
		if ($line==3 && $row=".") continue;
		// if non-empty, copy and paste it
		if ($line==3) {
			$result.=$row."\n";
			continue;
		}

		// filter out to header field for use in next requests	
		if (ereg ("^(To|t):", $row)){
			$result.=$row."\n";
			continue;
		}
		
		// anything else will be ignored
		
	}
	
	return $result;
}

/**
 *	Initiate dial request to $target from $uri
 *
 *	@param string $target	sip uri of callee
 *	@param string $uri		sip uri of caller
 *	@param array $errors	if some error occur during calling this function is writed here
 */
function click_to_dial($target, $uri, &$errors){
	global $config;

	$from="<".$config->ctd_from.">";
	$callidnr = uniqid("");
	$callid = $callidnr.".fifouacctd";
	$cseq=1;
	$fixed_dlg="From: ".$from.";tag=".$callidnr."\nCall-ID: ".$callid."\nContact: <sip:caller@!!>";
	$status="";
	$outbound_proxy=".";
	
	if (!empty($config->ctd_outbound_proxy)) $outbound_proxy=$config->ctd_outbound_proxy;
	
	if (!empty($config->ctd_secret)) $secret = "Secret: ".$config->ctd_secret."\n";
	else $secret = "";

/* initiate dummy INVITE with pre-3261 "on-hold"
   (note the dots -- they mean in order of appearance:
   outbound uri, end of headers, end of body; eventualy
   the FIFO request must be terminated with an empty line) */
	
	$fifo_cmd=":t_uac_dlg:".$config->reply_fifo_filename."\n".
		"INVITE\n".
		$uri."\n".
		$outbound_proxy."\n".
		$secret.
		$fixed_dlg."\n".
		"To: <".$uri.">\n".
		"CSeq: ".$cseq." INVITE\n".
		'Reject-Contact: *;automata="YES"'."\n".
		"Content-Type: application/sdp\n".
		".\n".
		"v=0\n".
		"o=click-to-dial 0 0 IN IP4 0.0.0.0\n".
		"s=session\n".
		"c=IN IP4 0.0.0.0\n".
		"b=CT:1000\n".
		"t=0 0\n".
		"m=audio 9 RTP/AVP 0\n".
		"a=rtpmap:0 PCMU/8000\n".
		".\n\n";
		
	$dlg=write2fifo($fifo_cmd, $errors, $status);
	if (substr($status,0,1)!="2") {$errors[]=$status; return; }

	$dlg=filter_fl($dlg);
	
	$cseq++;
	
	// start reader now so that it is ready for replies
	// immediately after a request is out
	
	$fifo_cmd=":t_uac_dlg:".$config->reply_fifo_filename."\n".
		"REFER\n".
		$dlg.		//"\n".
		$fixed_dlg."\n".
		"CSeq: ".$cseq." REFER\n".
		"Referred-By: ".$from."\n".
		"Refer-To: ".$target."\n".
		".\n".
		".\n\n";

	write2fifo($fifo_cmd, $errors, $status);
	if (substr($status,0,1)!="2") {$errors[]=$status; return; }

	$cseq++;

/* well, URI is trying to call TARGET but still maintains the
   dummy call we established with previous INVITE transaction:
   tear it down */

	$fifo_cmd=":t_uac_dlg:".$config->reply_fifo_filename."\n".
		"BYE\n".
		$dlg.  // "\n".
		$fixed_dlg."\n".
		"CSeq: ".$cseq." BYE\n".
		".\n".
		".\n\n";

	write2fifo($fifo_cmd, $errors, $status);
	if (substr($status,0,1)!="2") {$errors[]=$status; return; }
   
}


/**
 *	Get domain ID of domain of current virtualhost (the value in $config->domain)
 *	
 *	If domain ID is not found, this function return NULL
 *	
 *	@return	string		domain ID or FALSE on error
 */

function get_did_of_virtualhost(){
	global $config;

	if (isset($_SESSION['get_did_of_virtualhost']['domain']) and 
	    $_SESSION['get_did_of_virtualhost']['domain'] == $config->domain and
		isset($_SESSION['get_did_of_virtualhost']['did'])){

		return $_SESSION['get_did_of_virtualhost']['did'];
	}

	$dh = &Domains::singleton();
	if (false === $did = $dh->get_did($config->domain)) return false;
	if (is_null($did)) return null;
	
	$_SESSION['get_did_of_virtualhost']['did'] = $did;
	$_SESSION['get_did_of_virtualhost']['domain'] = $config->domain;
	
	return $did;
}

 
/**
 *	Return path to file from directory for concrete domain
 *
 *	Path in this case may be path in html tree or filesystem path - depending on 
 *	param $html_tree. If file is not found in directory for specified domain, 
 *	path to file for default domain directory is returned or FALSE if file not
 *	exists.
 *
 *	@param string $filename
 *	@param bool   $html_tree	if true path in html tree is returned, otherwise path in filesystem is returned
 *	@param string $did			domain from which the file is requested. If not set, the DID of $config->domain is used
 *	@return string				path to file on success, false on error
 */ 
function multidomain_get_file($filename, $html_tree=true, $did=null){
	global $config;
	
	$dir=dirname(__FILE__)."/domains/";
	if (is_null($did)) {
		$did = get_did_of_virtualhost();
		if (is_null($did) or false === $did){
			sw_log("Useing file from default domain. Domain: ".$config->domain." not found", PEAR_LOG_WARNING);
			$did = "_default";
		} 
	}

	if (file_exists($dir.$did."/".$filename)){ 
		return $html_tree ? 
			($config->domains_path.$did."/".$filename) :
			($dir.$did."/".$filename);
	}
	else if (file_exists($dir."_default/".$filename)){
		sw_log("Useing file from default domain for filename: ".$filename.", requested domain: ".$did, PEAR_LOG_DEBUG);
		return $html_tree ? 
			($config->domains_path."_default/".$filename) :
			($dir."_default/".$filename);
	}
	else {
		sw_log("Requested domain specific file not found. Filename:".$filename, PEAR_LOG_ERR);	
		return false;
	}
}

/**
 *	Return path to file for specified language mutation
 *
 *	Path in this case mean filesystem path. This function searching in some 
 *  directories and if corresponfing file is found, return path to it. Directories
 *	are scanned in this order:
 *		- dir for specified language
 *		- dir for default language
 *	If file isn't found, function return false
 *
 *	This function only prefix $filename by language (in which file exists) in 
 *	order to it can be also used as path in html tree
 *
 *	@param string $filename		name of file is searching for
 *	@param string $ddir			subdirectory within html dir
 *	@param string $lang			language in "official" ISO 639 language code see {@link config_lang.php} for more info
 *	@return string				path to file on success, false on error
 */
function get_file_by_lang($filename, $ddir, $lang){
	global $config, $reference_language, $available_languages;
	
	$dir=dirname(__FILE__); //html dir
	$ln = $available_languages[$lang][2];
	$ref_ln = $available_languages[$reference_language][2];

	if (!empty($ddir) and substr($ddir, -1) != "/") $ddir.="/";

	if (file_exists($dir."/".$ddir.$ln."/".$filename)) 
		return $ln."/".$filename;

	else if (file_exists($dir."/".$ddir.$ref_ln."/".$filename)){
		sw_log("Useing file in default language (requested lang: ".$ln.") for filename: ".$filename, PEAR_LOG_DEBUG);
		return $ref_ln."/".$filename;
	}

	else {
		sw_log("File not found. Filename:".$filename.", Language:".$ln.", Subdir:".$ddir, PEAR_LOG_WARNING);	
		return false;
	}
}

/**
 *	Return path to button image
 *
 *	@param string $button 	name of file with button image
 *	@param string $lang		language in "official" ISO 639 language code see {@link config_lang.php} for more info
 *	@return string
 */
function get_path_to_buttons($button, $lang){
	global $config;
	return $config->img_src_path."int/".get_file_by_lang("buttons/".$button, "img/int", $lang);
}

/**
 *	Return path to text file for specified language mutation and concrete domain
 *
 *	Path in this case mean filesystem path. This function searching in some 
 *  directories and if corresponfing file is found, return path to it. Directories
 *	are scanned in this order:
 *		- dir for specified domain and specified language
 *		- dir for specified domain and default language
 *		- dir for default domain and specified language
 *		- dir for default domain and default language
 *	If file isn't found, function return false
 *
 *	@param string $filename		name of file is searching for
 *	@param string $ddir			subdirectory within domain dir
 *	@param string $lang			language in "official" ISO 639 language code see {@link config_lang.php} for more info
 *	@param string $did			domain from which the file is requested. If not set, the DID of $config->domain is used
 *	@return string				path to file on success, false on error
 */
function multidomain_get_lang_file($filename, $ddir, $lang, $did=null){
	global $config, $reference_language, $available_languages;
	
	$dir=dirname(__FILE__)."/domains/";
	$ln = $available_languages[$lang][2];
	$ref_ln = $available_languages[$reference_language][2];

	if (is_null($did)) {
		$did = get_did_of_virtualhost();
		if (is_null($did) or false === $did){
			sw_log("Useing file from default domain. Domain: ".$config->domain." not found", PEAR_LOG_WARNING);
			$did = "_default";
		} 
	}

	if (!empty($ddir) and substr($ddir, -1) != "/") $ddir.="/";

	if (file_exists($dir.$did."/".$ddir.$ln."/".$filename)) 
		return $dir.$did."/".$ddir.$ln."/".$filename;
	
	else if (file_exists($dir.$did."/".$ddir.$ref_ln."/".$filename)){
		sw_log("Useing file in default language (requested lang: ".$ln.") for filename: ".$filename, PEAR_LOG_DEBUG);
		return $dir.$did."/".$ddir.$ref_ln."/".$filename;
	}
		
	else if (file_exists($dir."_default/".$ddir.$ln."/".$filename)){
		sw_log("Useing file from default domain for filename: ".$filename, PEAR_LOG_DEBUG);
		return $dir."_default/".$ddir.$ln."/".$filename;
	}

	else if (file_exists($dir."_default/".$ddir.$ref_ln."/".$filename)){
		sw_log("Useing file from default domain and in default language (requested lang: ".$ln.") for filename: ".$filename, PEAR_LOG_DEBUG);
		return $dir."_default/".$ddir.$ref_ln."/".$filename;
	}

	else {
		sw_log("Text file not found. Filename:".$filename.", Language:".$ln.", Subdir:".$ddir, PEAR_LOG_ERR);	
		return false;
	}
}

/**
 * 	Read txt file in specified language mutation, parse and saparate to headers and body and do replacements.
 *
 *	For more info about choice txt file read {@link multidomain_get_lang_file}
 *	Txt files in serweb (as emails, terms and conditions etc.) are stored in 
 *	special format. At the beginning (but only at beginning) of these files may 
 *	be comments. Lines with comments begins by "#". Rest of file is separated
 *	into two parts separated by empty line: headers and body. 
 *
 *	Each header contain header name and header value. Each header must be on
 *	own line. Header name and header value is separated by ":".
 *
 *	Body is the rest of txt file after first empty line.
 *
 *	When txt file is readed, function replace all strings in form #@#some_name#@#
 *	by replacement. The parametr $replacements is array of pairs. First element
 *	of each pair is name of replacement and second element from pair is value
 *	by which is replaced.
 *
 *	Function's finding replacements in body and in header values.
 *
 *	Function return array with two keys: "headers" and "body". Body is only 
 *	string. Headers contain associative array with header names as keys.
 *
 *	@param string $filename		name of file is searching for
 *	@param string $ddir			subdirectory in of domain dir
 *	@param string $lang			language in "official" ISO 639 language code see {@link config_lang.php} for more info
 *	@param array $replacements	see above
 *	@return array				parsed file or false on error
 */
function read_lang_txt_file($filename, $ddir, $lang, $replacements){
	$f = multidomain_get_lang_file($filename, $ddir, $lang);
	if (!$f) {
		sw_log("Can't find txt file ".$filename.", subdir:".$ddir.", lang:".$lang, PEAR_LOG_ERR);
		return false;
	}
	
	return read_txt_file($f, $replacements);
}

/**
 * 	Read txt file, parse and saparate to headers and body and do replacements.
 *
 *	Txt files in serweb (as emails, terms and conditions etc.) are stored in 
 *	special format. At the beginning (but only at beginning) of these files may 
 *	be comments. Lines with comments begins by "#". Rest of file is separated
 *	into two parts separated by empty line: headers and body. 
 *
 *	Each header contain header name and header value. Each header must be on
 *	own line. Header name and header value is separated by ":".
 *
 *	Body is the rest of txt file after first empty line.
 *
 *	When txt file is readed, function replace all strings in form #@#some_name#@#
 *	by replacement. The parametr $replacements is array of pairs. First element
 *	of each pair is name of replacement and second element from pair is value
 *	by which is replaced.
 *
 *	Function's finding replacements in body and in header values.
 *
 *	Function return array with two keys: "headers" and "body". Body is only 
 *	string. Headers contain associative array with header names as keys.
 *
 *	@param string $filename		name of file is searching for
 *	@param array $replacements	see above
 *	@return array				parsed file or false on error
 */
function read_txt_file($filename, $replacements){
	global $lang_set;
	
	$fp = fopen($filename, "r");
	if (!$fp){
		sw_log("Can't open txt file ".$filename, PEAR_LOG_ERR);
		return false;
	}

	$fcontent = "";
	$accept_comments = true;
	$reading_headers = true;
	$headers = array();
	$body = "";

	while (!feof($fp)){
		$line = fgets($fp);
		if ((substr($line, 0, 1) == "#") and $accept_comments) continue;
		
		/* accept comments only on begin of file */
		$accept_comments = false;
		
		/* after empty line begins body */
		if (trim($line) == "") $reading_headers = false;
		
		if ($reading_headers){
			$h = split(':', $line, 2);
			$headers[strtolower(trim($h[0]))] = trim($h[1]);  
		}
		else{
			/* trim ends of lines of non empty lines */
			if (trim($line) != "") $line = rtrim($line)." ";
			$body .= $line;
		}
	}
	fclose($fp);


	/* get charset */
	$file_charset = null;
	if (isset($headers['content-type']) and 
	    eregi("charset=([-a-z0-9]+)", $headers['content-type'], $regs)){
		
		$file_charset = $regs[1];
	}


	foreach($replacements as $row){
		if (!empty($file_charset) and !empty($lang_set['charset'])){
			$row[1] = iconv($lang_set['charset'], $file_charset."//TRANSLIT", $row[1]);
		}
		
		//do replace in body
		$body=str_replace("#@#".$row[0]."#@#", $row[1], $body);

		//do replace in headers
		foreach($headers as $k => $v){
			$headers[$k] = str_replace("#@#".$row[0]."#@#", $row[1], $headers[$k]);
		}
	}
	
	return array('headers' => $headers,
				 'body' => $body);
}

/**
 *	Write to serweb log if logging is enabled
 *
 *	@param mixed $message  		String or object containing the message to log.
 *	@param mixed $priority  	The priority of the message. Valid values are: PEAR_LOG_EMERG, PEAR_LOG_ALERT, PEAR_LOG_CRIT, PEAR_LOG_ERR, PEAR_LOG_WARNING, PEAR_LOG_NOTICE, PEAR_LOG_INFO, and PEAR_LOG_DEBUG.
 *	@return boolean 			True on success or false on failure
 */
 
function sw_log($message, $priority = null){
	global $serwebLog, $config;

	//if custom log function is defined, use it for log errors
	if (!empty($config->custom_log_function)){
		$db = debug_backtrace();

		return call_user_func($config->custom_log_function, $priority, $message, $db[0]['file'], $db[0]['line']);	
	}

	if ($serwebLog){ 
		return $serwebLog->log($message, $priority);
	}

	return true;
}

/**
 *  Log action of user
 *
 *  Allowed options:
 *   - cancel (bool)  - indicates that submit of html form has been canceled [default: false]
 *   - errors (mixed) - string or array of errors which occurs during action [default: none]
 *
 *
 *  @param string $screen_name  Name of screen where the action has been performed.
 *  @param array $action        Action which has been performed.
 *  @param string $msg          Message describing the action
 *  @param bool $success        Has been action preformed successfully?
 *  @param array $opt           Optional parrameters - reserved for future use
 *  @return none
 */
 
function action_log($screen_name, $action, $msg=null, $success = true, $opt = array()){
    global $config;

    $opt['action_str'] = is_array($action) ? $action['action'] : $action; 

    if (!empty($config->custom_act_log_function)){
        call_user_func($config->custom_act_log_function, $screen_name, $action, $msg, $success, $opt);  
    }
    else{
        if (is_null($msg)) $msg = "action performed";
        sw_log($screen_name." - ".$action['action']." ".$msg." ".($success ? "[successfull]" : "[failed]"), PEAR_LOG_INFO);
    }
}
/**
 *	get error message from PEAR_Error object and write it to $errors array and to error log
 *
 *	@param object $err_object PEAR_Error object
 *	@param array $errors array of error messages
 */

function log_errors($err_object, &$errors){
	global $serwebLog, $config;

	//get name of function from which log_errors is called
	$backtrace=debug_backtrace();
	if (isset($backtrace[1]['function'])) {
		if (isset($backtrace[1]['class']) and	//if this function is called from errorhandler class
			$backtrace[1]['function'] == 'log_errors' and
			$backtrace[1]['class'] == 'errorhandler'){
			
			if (isset($backtrace[2]['function'])) {
				$funct=$backtrace[2]['function'];
			}
			else $funct=null;
		}
		else{
			$funct=$backtrace[1]['function'];
		}
	}
	else $funct=null;


	//get backtrace frame from err_object which correspond to function from which log_errors is called
	$backtrace=$err_object->getBacktrace();
	$last_frame=end($backtrace);
	
	if ($funct and $funct!=__FUNCTION__){
		do{
			if ($last_frame['function']==$funct){
				$last_frame=prev($backtrace);
				break;
			}
		}while($last_frame=prev($backtrace));

		//if matchng frame is not found, use last_frame	
		if (!$last_frame) { 
			//if logging is enabled
			if ($serwebLog){ 
				$serwebLog->log("function: LOG ERRORS - bad parametr ".$funct, PEAR_LOG_ERR);
			}

			$last_frame=end($backtrace);
		}
	}
	
	$err_message = $err_object->getMessage();
	if ($config->log_error_return_location_of_error_to_html){
		$err_message .= ", file: ".$last_frame['file'].":".$last_frame['line'];
	}
	$errors[] = $err_message;


	//if custom log function is defined, use it for log errors
	if (!empty($config->custom_log_function)){
		$log_message= $err_object->getMessage()." - ".$err_object->getUserInfo();

		call_user_func($config->custom_log_function, PEAR_LOG_ERR, $log_message, $last_frame['file'], $last_frame['line']);	
	}

	//otherwise if logging is enabled, use default log function
	elseif ($serwebLog){ 
	
		$log_message= "file: ".$last_frame['file'].":".$last_frame['line'].": ".$err_object->getMessage()." - ".$err_object->getUserInfo();
		//remove endlines from the log message
		$log_message=str_replace(array("\n", "\r"), "", $log_message);
		$log_message=ereg_replace("[[:space:]]{2,}", " ", $log_message);
		$serwebLog->log($log_message, PEAR_LOG_ERR);
		
	}

}


/**
 *	Convert string to CSV format
 *
 *	@param string $str string to convert
 *	@param string $delim delimiter
 *	@return string 
 */
 
function toCSV($str, $delim=','){
	$str = str_replace('"', '""', $str);	//double alll quotes
	$pos1 = strpos($str, '"');				// if $str contains quote or delim, quote it
	$pos2 = strpos($str, $delim);
	if (!($pos1===false and $pos2===false)) $str = '"'.$str.'"';
	return $str;
}

/**
 *	Return true if module $mod_name is loaded
 *
 *	@param string $mod_name		name of module
 *	@return bool
 */
function isModuleLoaded($mod_name){
	global $config;
	
	if (isset($config->modules[$mod_name]) and $config->modules[$mod_name])
		return true;
	else
		return false;
}

/**
 *	Return array of loaded modules
 *
 *	@return array
 */
function getLoadedModules(){
	global $config;
	
	if (isset($config->modules))
		return array_keys($config->modules, true);
	else
		return array();
}


/**
 *	This function is used for method aggregation will work in PHP4 and PHP5
 *
 *	@param object object
 *	@param string class_name 
 */
function my_aggregate_methods(&$object, $class_name){

	if (function_exists('aggregate_methods')){
		return aggregate_methods($object, $class_name);
	}

	if (function_exists('runkit_class_adopt')){
		return @runkit_class_adopt(get_class($object), $class_name);
	}

	if (function_exists('classkit_aggregate_methods')){
		return @classkit_aggregate_methods(get_class($object), $class_name);
	}

	die("Function aggregate_methods() doesn't exists. This is probably because ".
		"PHP 5 or later is running on this server. Try install Runkit or ".
		"Classkit extension from PECL repository (http://pecl.php.net). ".
		"Useing classkit is safe with ".
		"PHP 5.0, but does not work with later versions of PHP. Useing runkit ".
		"is experimental. Type 'pecl install -f runkit' on your command line ".
		"for install the extension. And do not forget enable the extension in ".
		"your php.ini file.");

}

/**
 *	This function return version 4 UUID by RFC4122, which is generating UUIDs 
 *	from truly-random numbers.
 *	
 *	@return string
 */
function rfc4122_uuid(){
   // version 4 UUID
   return sprintf(
       '%08x-%04x-%04x-%02x%02x-%012x',
       mt_rand(),
       mt_rand(0, 65535),
       bindec(substr_replace(
           sprintf('%016b', mt_rand(0, 65535)), '0100', 11, 4)
       ),
       bindec(substr_replace(sprintf('%08b', mt_rand(0, 255)), '01', 5, 2)),
       mt_rand(0, 255),
       mt_rand()
   );
}

/**
 *	This function creates the specified directory using mkdir().  Note
 *	that the recursive feature on mkdir() is added in PHP5 so I need
 *	to create it myself for PHP4
 *
 *	@param string $path
 *	@param int    $mode  	The mode is 0777 by default, which means the widest possible access. For more information on modes, read the details on the chmod() page.
 */
function RecursiveMkdir($path, $mode=0777){

	if (!file_exists($path)){
		// The directory doesn't exist.  Recurse, passing in the parent
		// directory so that it gets created.
		RecursiveMkdir(dirname($path));
		
		mkdir($path, $mode);
	}
}

/**
 * Copy a file, or recursively copy a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function copyr($source, $dest)
{
    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }
 
    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }
 
    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }
 
        // Deep copy directories
        if ($dest !== "$source/$entry") {
            copyr("$source/$entry", "$dest/$entry");
        }
    }
 
    // Clean up
    $dir->close();
    return true;
}

/**
 * rm() -- Vigorously erase files and directories.
 * 
 * @param $fileglob mixed If string, must be a file name (foo.txt), glob pattern (*.txt), or directory name.
 *                        If array, must be an array of file names, glob patterns, or directories.
 */
function rm($fileglob)
{
   if (is_string($fileglob)) {
       if (is_link($fileglob) or is_file($fileglob)) {
           return unlink($fileglob);
       } else if (is_dir($fileglob)) {
           $ok = rm("$fileglob/*");
           if (! $ok) {
               return false;
           }
           return rmdir($fileglob);
       } else {
           $matching = glob($fileglob);
           if ($matching === false) {
//               trigger_error(sprintf('No files match supplied glob %s', $fileglob), E_USER_WARNING);
//               return false;
				return true; //nothing to delete
           }       
           $rcs = array_map('rm', $matching);
           if (in_array(false, $rcs)) {
               return false;
           }
       }       
   } else if (is_array($fileglob)) {
       $rcs = array_map('rm', $fileglob);
       if (in_array(false, $rcs)) {
           return false;
       }
   } else {
       trigger_error('Param #1 must be filename or glob pattern, or array of filenames or glob patterns', E_USER_ERROR);
       return false;
   }
   return true;
}

/**
 * wrapper for function JSON_encode
 *
 * If function JSON_encode call it. In other case call function sw_JSON_encode
 *
 * @param    mixed   $var    any number, boolean, string, array, or object to be encoded.
 *                           if var is a strng, note that sw_JSON_encode() always expects it
 *                           to be in ASCII or UTF-8 format!
 *
 * @return   mixed   JSON string representation of input var or FALSE if a problem occurs
 * @access   public
 */
function my_JSON_encode($var){
	if (function_exists("JSON_encode")){
		return JSON_encode($var);
	}
	else {
		return sw_JSON_encode($var);
	}
}

/**
 * encodes an arbitrary variable into JSON format
 *
 * @param    mixed   $var    any number, boolean, string, array, or object to be encoded.
 *                           if var is a strng, note that sw_JSON_encode() always expects it
 *                           to be in ASCII or UTF-8 format!
 *
 * @return   mixed   JSON string representation of input var or FALSE if a problem occurs
 * @access   public
 */
function sw_JSON_encode($var){

    switch (gettype($var)) {
        case 'boolean':
            return $var ? 'true' : 'false';

        case 'NULL':
            return 'null';

        case 'integer':
            return (int) $var;

        case 'double':
        case 'float':
            return (float) $var;

        case 'string':
            // STRINGS ARE EXPECTED TO BE IN ASCII OR UTF-8 FORMAT

            return '"'.js_escape($var).'"';

        case 'array':
           /*
            * As per JSON spec if any array key is not an integer
            * we must treat the the whole array as an object. We
            * also try to catch a sparsely populated associative
            * array with numeric keys here because some JS engines
            * will create an array with empty indexes up to
            * max_index which can cause memory issues and because
            * the keys, which may be relevant, will be remapped
            * otherwise.
            *
            * As per the ECMA and JSON specification an object may
            * have any string as a property. Unfortunately due to
            * a hole in the ECMA specification if the key is a
            * ECMA reserved word or starts with a digit the
            * parameter is only accessible using ECMAScript's
            * bracket notation.
            */

            // treat as a JSON object
            if (is_array($var) && count($var) && (array_keys($var) !== range(0, sizeof($var) - 1))) {
				$properties  = array();
				foreach($var as $k => $v){
					$en_val = sw_JSON_encode($v);
					if (false === $en_val) return false;
					$properties[] = sw_JSON_encode(strval($k)).':'.$en_val;
				}

                return '{' . implode(',', $properties) . '}';
            }

            // treat it like a regular array
            $elements = array_map('sw_JSON_encode', $var);

			foreach($elements as $k => $v){
				if (false === $v) return false;
			}

            return '[' . implode(',', $elements) . ']';

        case 'object':
            $vars = get_object_vars($var);

			$properties  = array();
			foreach($vars as $k => $v){
				$en_val = sw_JSON_encode($v);
				if (false === $en_val) return false;
				$properties[] = sw_JSON_encode(strval($k)).':'.$en_val;
			}

            return '{' . implode(',', $properties) . '}';
        
        default:
        	return false;
    }
} 

/**
 *	Redirect client to secure connection and stop executing of the script
 */
function redirect_to_HTTPS(){
	/* if useing secure connection return true */
	if (!empty($_SERVER['HTTPS']) and $_SERVER['HTTPS']!='off') return true;

	/* there is something wrong, we already tryed do redirect but it seems 
	   non secure connection is still used */
	if (isset($_GET['redirected_to_https'])) return false;


	/* do redirect to secure connection */
	$server_name = isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : $_SERVER["SERVER_NAME"];
	$separator = (false === strstr($_SERVER['REQUEST_URI'], '?')) ? '?' : '&';

	/* for developer purpose - if need to use diferent port for redirect */
	if (isset($_COOKIE['_server_port'])) {
		/* if server name conatin port number */
		if (strpos($server_name, ":")) {
			/* strip the port from server name */
			$server_name = substr($server_name, 0, strpos($server_name, ":"));
		}
		/* and add port for https */
		$server_name .= ":".$_COOKIE['_server_port'];
	}

	Header("Location: https://".$server_name.$_SERVER['REQUEST_URI'].$separator."redirected_to_https=1");
	exit (0);
}

/**
 *	Add escape characters into string to it could be directly used in javascript
 *	
 *	@param	string	$str
 *	@return	string
 */
function js_escape($str){
    return str_replace("\n", '\n', addslashes($str));
}

/**
 *	Add GET parameter(s) to URL
 *	
 *  Join URL with parameter useing '?' or '&' depending on whether URL already 
 *  contain some parameters	
 *	
 *	@param	string	$url
 *	@param	string	$param
 *	@return	string
 */
function add_param_to_url($url, $param){
    if (strpos($url, "?")) return $url."&".$param;
    else                   return $url."?".$param;
}


/**
 *	Clone array of objects
 *	
 *  Create new array that contains new copies of object, not references 
 *  to same objects 
 *	
 *	@param	array	$array
 *	@return	array
 */
function clone_array($array){

    if (!is_array($array)) return $array;

    $clone = array();
    foreach($array as $k=>$v){
        if (is_object($v))  $clone[$k] = clone($v);
        else                $clone[$k] = $v;
    }
    
    return $clone;
}

?>
