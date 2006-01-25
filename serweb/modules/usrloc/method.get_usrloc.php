<?
/*
 * $Id: method.get_usrloc.php,v 1.2 2006/01/25 12:40:33 kozlik Exp $
 */

class CData_Layer_get_usrloc {
	var $required_methods = array('get_location');
	
	/*
	 * get all USRLOC entries of user@domain
	 */
	
	function get_usrloc($uid, &$errors){
		global $config, $sess;

		$contacts = array();

		if ($config->use_rpc){
			/* 
			 * get contacts useing xml-rpc and store them into array $contacts 
			 */
			 
			if (!$this->connect_to_xml_rpc(null, $errors)) return false;
			
			$params = array(new XML_RPC_Value($config->ul_table, 'string'),
			                new XML_RPC_Value($uid, 'string'));
			                
			$msg = new XML_RPC_Message_patched('usrloc.show_contacts', $params);
			$res = $this->rpc->send($msg);
	
			if ($this->rpc_is_error($res)){
				/* if usrloc of user is empty */
				if ($res->getCode() == "404") return array();
				
				log_errors($res, $errors); 
				return false;
			}

		    $val = $res->value();
		    if ($val->kindOf() != "array"){
				log_errors(PEAR::RaiseError("sorry invalid output from SER") ,$errors);
				return false; 
			}

			$size = $val->arraySize();
			for ($i=0; $i<$size; $i++){
				$contact=$val->arrayMem($i);
			    
				if ($contact->kindOf() != "struct"){
					log_errors(PEAR::RaiseError("sorry invalid output from SER") ,$errors);
					return false; 
				}
			
				$contacts[$i] = new stdClass();
				$contacts[$i]->contact 	= $contact->structMem("contact");
				$contacts[$i]->q		= $contact->structMem("q");
				$contacts[$i]->expires	= $contact->structMem("expires");

				$contacts[$i]->contact 	= $contacts[$i]->contact->scalarval();
				$contacts[$i]->q		= $contacts[$i]->q->scalarval();
				$contacts[$i]->expires	= $contacts[$i]->expires->scalarval();
			}

		}
		else{

			/* 
			 * get contacts useing xml-rpc and store them into array $contacts 
			 */

			$fifo_cmd=":usrloc.show_contacts:".$config->reply_fifo_filename."\n".
			$config->ul_table."\n".		//table
			$uid."\n".				//username
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

			
			/*
			 *	Example of FIFO output:
			 *
			 *	expires:834277,q:1.000000,contact:sip\okk@kk.cz
			 *	expires:34303,q:1.000000,contact:sip\osdaf@dfdf.cd
			 *	expires:3335,q:0.000000,contact:sip\odsa@iptel.org
			 *	expires:3191,q:0.000000,contact:sip\osdaf@iptedfl.org
			 */
			
			$out_arr=explode("\n", $fifo_out);

			$i=0;	
			foreach($out_arr as $val){
				if (!ereg("^[[:space:]]*$", $val)){
//					if (ereg("<([^>]*)>;q=([0-9.]*);expires=(-?[0-9]*)", $val, $regs)){
					if (ereg('expires:(-?[0-9]*),q:([0-9.]*),contact:(.*)$', $val, $regs)){
	
						$contacts[$i] = new stdClass();
						$contacts[$i]->contact 	= str_replace('\o', ':', $regs[3]);
						$contacts[$i]->q		= $regs[2];
						$contacts[$i]->expires	= $regs[1];
	
						$i++;
					}
					else { 
						log_errors(PEAR::RaiseError("sorry invalid output from fifo") ,$errors);
						return false; 
					}
				}
			}
		}

		/* 
		 * format array of contact for output
		 */

		$i=0;	
		$out=array();	
		foreach($contacts as $val){
			$exp_timestamp = time()+$val->expires;
			$expires=date('Y-m-d H:i', $exp_timestamp);
	
			if (substr($expires,0,10)==date('Y-m-d')) $date=substr($expires,11,5);
			else $date=substr($expires,0,10);

			if ((int)$val->expires < 0 ) {
				$date = "never";
				$exp_timestamp = -1;
			}

			$out[$i]['uri']=$val->contact;
			$out[$i]['q']=$val->q;
			$out[$i]['expires']=$date;
			$out[$i]['exp_timestamp']=$exp_timestamp;
			$out[$i]['geo_loc']=$this->get_location($val->contact, $errors);

			$i++;
		}

		return $out;
	}
	
}
?>
