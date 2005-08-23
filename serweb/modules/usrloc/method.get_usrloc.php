<?
/*
 * $Id: method.get_usrloc.php,v 1.1 2005/08/23 12:58:13 kozlik Exp $
 */

class CData_Layer_get_usrloc {
	var $required_methods = array('get_location');
	
	/*
	 * get all USRLOC entries of user@domain
	 */
	
	function get_usrloc($user, $domain, &$errors){
		global $config, $sess;

		$ul_name=$user."@".$domain;

		if ($config->use_rpc){
			if (!$this->connect_to_xml_rpc(null, $errors)) return false;
			
			$params = array(new XML_RPC_Value($config->ul_table, 'string'),
			                new XML_RPC_Value($ul_name, 'string'));
			                
			$msg = new XML_RPC_Message_patched('ul_show_contact', $params);
			$res = $this->rpc->send($msg);
	
			if ($this->rpc_is_error($res)){
				/* if usrloc of user is empty */
				if ($res->getCode() == "404") return array();
				
				log_errors($res, $errors); 
				return false;
			}
			
		    $val = $res->value();
			$fifo_out = trim($val->scalarval());

			$out=array();	
			$out_arr=explode("\n", $fifo_out);

			//if status code is on the first line, remove it
			if (is_numeric(substr($out_arr[0], 0, 3))) unset($out_arr[0]);


		}
		else{

			$fifo_cmd=":ul_show_contact:".$config->reply_fifo_filename."\n".
			$config->ul_table."\n".		//table
			$ul_name."\n".				//username
			"\n";				
	
			$fifo_out=write2fifo($fifo_cmd, $err, $status);

			if ($err) {
				$errors=array_merge($errors, $err); 
				return false;
			}
	
			if (substr($status,0,1)!="2" and substr($status,0,3)!="404") {
				$errors[]=$status; 
				return false; 
			}

			$out=array();	
			$out_arr=explode("\n", $fifo_out);
		}

		$i=0;	
		foreach($out_arr as $val){
			if (!ereg("^[[:space:]]*$", $val)){
				if (ereg("<([^>]*)>;q=([0-9.]*);expires=(-?[0-9]*)", $val, $regs)){

					$exp_timestamp = time()+$regs[3];
					$expires=date('Y-m-d H:i', $exp_timestamp);
			
					if (Substr($expires,0,10)==date('Y-m-d')) $date=Substr($expires,11,5);
					else $date=Substr($expires,0,10);

					if ((int)$regs[3] < 0 ) {
						$date = "never";
						$exp_timestamp = -1;
					}

					$out[$i]['uri']=$regs[1];
					$out[$i]['q']=$regs[2];
					$out[$i]['expires']=$date;
					$out[$i]['exp_timestamp']=$exp_timestamp;
					$out[$i]['geo_loc']=$this->get_location($regs[1], $errors);
	
					$i++;
				}
				else { 
					log_errors(PEAR::RaiseError("sorry invalid output from fifo") ,$errors);
					return false; 
				}
			}
		}

		return $out;
	}
	
}
?>
