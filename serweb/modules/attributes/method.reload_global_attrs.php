<?php
/*
 * $Id: method.reload_global_attrs.php,v 1.1 2009/10/22 07:44:09 kozlik Exp $
 */

class CData_Layer_reload_global_attrs {
    var $required_methods = array('get_DB_time');
    
    /**
     *  reload domains table of SER from DB
     *
     *  Possible options parameters:
     *   none
     *
     *  @param array $opt       associative array of options
     *  @param array $errors    error messages
     *  @return bool            TRUE on success, FALSE on failure
     */ 
    function reload_global_attrs($opt, &$errors){
        global $config;

        $ga_h = &Global_Attrs::singleton();
        /* get current timestamp on DB server */
        if (false === $now = $this->get_DB_time(null)) return false;
        /* update attribute holding timestamp of last data change */
        if (false === $ga_h->set_attribute($config->attr_names['gattr_timestamp'], $now)) return false;

        /* If notifing of sip proxies to reload the data is disabled, 
         * finish here
         */
        if (empty($config->g_attrs_reload_ser_notify)) return true;

        if ($config->use_rpc){
//          if (!$this->connect_to_xml_rpc(null, $errors)) return false;

            $params = array();
                            
            $msg = new XML_RPC_Message('gflags.reload', $params);
            $res = $this->rpc_send_to_all($msg, array('break_on_error'=>false));

            if (!$res->ok){
                foreach($res->results as $v){
                    if (PEAR::isError($v)) {
                        ErrorHandler::log_errors($v);
                    }
                }
                return false;
            }
            return true;

        }
        else{   
            /* construct FIFO command */
            $fifo_cmd=":gflags.reload:".$config->reply_fifo_filename."\n";
    
            $message=write2fifo($fifo_cmd, $errors, $status);
            if ($errors) return false;
            if (substr($status,0,1)!="2") {$errors[]=$status; return false; }
        }

        return true;            
    }
}

?>
