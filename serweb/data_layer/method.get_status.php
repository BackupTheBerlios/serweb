<?
/*
 * $Id: method.get_status.php,v 1.4 2005/05/03 11:15:03 kozlik Exp $
 */

class CData_Layer_get_status {
	var $required_methods = array('domain_exists', 'is_user_exists', 'get_status_visibility');
	
	/*
	 * get status of sip user
	 * return: "non-local", "unknown", "non-existent", "on line", "off line"
	 */

	function get_status($sip_uri, &$errors){
		global $config, $lang_str, $data;

		/* create connection to proxy where are stored data of user */
		if (isModuleLoaded('xxl') and $this->name != "get_status_tmp"){

			$tmp_data = CData_Layer::singleton("get_status_tmp", $errors);
			$tmp_data->set_xxl_user_id($sip_uri);
			//$tmp_data->expect_user_id_may_not_exists(); //do not need this?

			return $tmp_data->get_status($sip_uri, $errors);
		}


		$reg = Creg::singleton();
		if (!eregi("^sip:([^@]+@)?".$reg->host, $sip_uri, $regs)) 
			return "<div class=\"statusunknown\">".$lang_str['status_nonlocal']."</div>";

		$user=substr($regs[1],0,-1);
		$domain=$regs[2];

		if ($config->multidomain){
			$local = $data->domain_exists($domain, $errors);
			if ($local < 0) return "<div class=\"statusunknown\">".$lang_str['status_unknown']."</div>";
			if (! $local) return "<div class=\"statusunknown\">".$lang_str['status_nonlocal']."</div>";
		}
		else{
			if ($domain != $config->domain) return "<div class=\"statusunknown\">".$lang_str['status_nonlocal']."</div>";
		}


		//check if user exists

		$exists=$this->is_user_exists($user, $domain, $errors);
		if ($exists<0) return "<div class=\"statusunknown\">".$lang_str['status_unknown']."</div>";
		elseif(!$exists) return "<div class=\"statusunknown\">".$lang_str['status_nonexists']."</div>";

		//check if others can see status of user
		if ($config->status_vibility){
			$status_visibility=$this->get_status_visibility($user, $domain, $errors);
			if ($status_visibility === false or $status_visibility<0) return "<div class=\"statusunknown\">".$lang_str['status_unknown']."</div>";
		}
		
		//check usrloc if user is online
		
		if ($config->use_rpc){
			if (!$this->connect_to_xml_rpc(null, $errors)) 
				return "<div class=\"statusunknown\">".$lang_str['status_unknown']."</div>";
			
			$params = array(new XML_RPC_Value($config->ul_table, 'string'),
			                new XML_RPC_Value($user."@".$domain, 'string'));
			$msg = new XML_RPC_Message_patched('ul_show_contact', $params);
			$res = $this->rpc->send($msg);
	
			if ($this->rpc_is_error($res)){
				if ($res->getCode() == "404"){
					return "<div class=\"statusoffline\">".$lang_str['status_offline']."</div>";
				}

				log_errors($res, $errors); 
				return "<div class=\"statusunknown\">".$lang_str['status_unknown']."</div>";
			}
			return "<div class=\"statusonline\">".$lang_str['status_online']."</div>";
		}
		else{

			$fifo_cmd=":ul_show_contact:".$config->reply_fifo_filename."\n".
			$config->ul_table."\n".		//table
			$user."@".$domain."\n\n";	//username

			$out=write2fifo($fifo_cmd, $errors, $status);
			if ($errors) return;

			if (substr($status,0,3)=="200") return "<div class=\"statusonline\">".$lang_str['status_online']."</div>";
			else return "<div class=\"statusoffline\">".$lang_str['status_offline']."</div>";
		}
	}
	
}
?>
