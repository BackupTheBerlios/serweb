<?
/*
 * $Id: method.get_status.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_status {
	var $required_methods = array('domain_exists', 'is_user_exists', 'get_status_visibility');
	
	/*
	 * get status of sip user
	 * return: "non-local", "unknown", "non-existent", "on line", "off line"
	 */

	function get_status($sip_uri, &$errors){
		global $config;

		$reg=new Creg;
		if (!eregi("^sip:([^@]+@)?".$reg->host, $sip_uri, $regs)) return "<div class=\"statusunknown\">non-local</div>";

		$user=substr($regs[1],0,-1);
		$domain=$regs[2];

		$local = $this->domain_exists($domain, $errors);
		if ($local < 0) return "<div class=\"statusunknown\">unknown</div>";
		if (! $local) return "<div class=\"statusunknown\">non-local</div>";


		//check if user exists

		$exists=$this->is_user_exists($user, $domain, $errors);
		if ($exists<0) return "<div class=\"statusunknown\">unknown</div>";
		elseif(!$exists) return "<div class=\"statusunknown\">non-existent</div>";

		//check if others can see status of user
		if ($config->allow_change_status_visibility){
			$status_visibility=$this->get_status_visibility($user, $domain, $errors);
			if ($status_visibility === false or $status_visibility<0) return "<div class=\"statusunknown\">unknown</div>";
		}
		
		//check usrloc if user is online
		
		$fifo_cmd=":ul_show_contact:".$config->reply_fifo_filename."\n".
		$config->ul_table."\n".		//table
		$user."@".$domain."\n\n";	//username

		$out=write2fifo($fifo_cmd, $errors, $status);
		if ($errors) return;

		if (substr($status,0,3)=="200") return "<div class=\"statusonline\">on line</div>";
		else return "<div class=\"statusoffline\">off line</div>";
	}
	
}
?>
