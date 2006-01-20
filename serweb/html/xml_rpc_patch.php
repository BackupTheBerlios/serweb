<?

/**
 *	This class overides method parseResponseFile() from class XML_RPC_Message
 *	
 *	The diference is that this class parse content-length header and read only
 *	number of bytes specified by this header from server. This method do not 
 *	wait to closing the TCP connection by the server.
 */
class XML_RPC_Message_patched extends XML_RPC_Message{

    function parseResponseFile($fp){

		//are there still data to read?		
		$continue_reading = true;
		//read headers or body of response?
		$read_body = false;
		//length of body of response
		$content_length = null;
		
		$last_line = "";
		//counter of received bytes of body
		$received = 0;
		//number of bytes readed by function fread()
		$max_recv_len = 8192;
		//number of bytes readed by function fread() on last call of this function
		$recv_len = $max_recv_len;

        $ipd = '';

        while ($continue_reading) {
        	if (false === ($data = @fread($fp, $recv_len))) break;
        	if (! strlen($data)) break;

			// save received data for parseResponse method
            $ipd .= $data;

			if ($read_body){
				//reading body of response
				
				//count how many bytes are received
				$received += strlen($data);
			}
			else {
				//reading headers of response

				//concat received data with end of previously received data (probably incoplete line)
				$data = $last_line.$data;
				//split received data to lines
				$lines = explode("\n", $data);

				//don't process last line now (may be incoplete), save it for later processing
				$last_line = $lines[count($lines)-1];
				unset ($lines[count($lines)-1]);

				//walk through lines
				foreach($lines as $k => $v){
					// stop reading headers on empty line
					if (trim($v) == "") {
						$read_body = true;
						continue;
					}

					// if reading body of response
					if($read_body){
						//only count length of received response
						$received += strlen($v);
						
						//add "\n" striped by function explode()
						$received++;
						continue;
					}
					
					// parse header content-length
					if (eregi('^Content-Length:(.*)$', $v, $regs)){
						$regs[1] = trim($regs[1]);
						if (!is_numeric($regs[1])) continue;
						
						$content_length = $regs[1];
					}
					
				}
				
				// if reading body of response
				if($read_body){
					// add length of $last_line
					$received += strlen($last_line);
				}
				
			}
			
			// is there still data to read
			if (!is_null($content_length) and ($received >= $content_length)){
				$continue_reading = false;
			}

			// length of last batch of data
			if (!is_null($content_length) and ($content_length - $received < $max_recv_len)){
				$recv_len = $content_length - $received;
			}
        }
        
        
        return $this->parseResponse($ipd);
    }

}

?>
