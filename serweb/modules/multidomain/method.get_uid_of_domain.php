<?php
/**
 *  @author     Karel Kozlik
 *  @version    $Id: method.get_uid_of_domain.php,v 1.1 2008/03/19 12:10:03 kozlik Exp $
 *  @package    serweb
 *  @subpackage mod_multidomain
 */ 

/**
 *  Data layer container holding the method for lookup UIDs from the domain
 * 
 *  @package    serweb
 *  @subpackage mod_multidomain
 */ 
class CData_Layer_get_uid_of_domain {
    var $required_methods = array('get_did_by_realm');
    
    /**
     *  Get array of uids which URIs and credentials asociated ONLY with 
     *  the domain. And not with any other domain.
     *
     *  Possible options:
     *   - none
     *
     *  @param  string  $did        Domain ID
     *  @param  array   $opt        array of options
     *  @return array               FALSE on error
     */ 
      
    function get_uid_of_domain($did, $opt){
        global $config;

        $errors = array();
        if (!$this->connect_to_db($errors)) {
            ErrorHandler::add_error($errors); return false;
        }

        /* table's name */
        $tu_name = &$config->data_sql->uri->table_name;
        $tc_name = &$config->data_sql->credentials->table_name;
        /* col names */
        $cu = &$config->data_sql->uri->cols;
        $cc = &$config->data_sql->credentials->cols;
        /* flags */
        $fu = &$config->data_sql->uri->flag_values;
        $fc = &$config->data_sql->credentials->flag_values;

        /* if 'did' column in credentials table is not used, make list of all
           realms matching this domain
         */
        if (!$config->auth['use_did']){
            $dh = &Domains::singleton();
            if (false === $dom_names = $dh->get_domain_names($did)) return false;

            $da = &Domain_Attrs::singleton($did);
            if (false === $realm = $da->get_attribute($config->attr_names['digest_realm'])) return false;
            
            $realms_w1 = array();
            $realms_w2 = array();
            
            if (!is_null($realm)){
                $realms_w1[] = $cc->realm." = ".$this->sql_format($realm, "s");
                $realms_w2[] = $cc->realm." != ".$this->sql_format($realm, "s");
            }

            foreach ($dom_names as $v){
                $realms_w1[] = $cc->realm." = ".$this->sql_format($v, "s");
                $realms_w2[] = $cc->realm." != ".$this->sql_format($v, "s");
            }
        }

        $uids = array();


        /* get list of UIDs which have URI asociated with the domain */
        $q="select distinct ".$cu->uid." as uid
            from ".$tu_name."
            where  ".$cu->did." = ".$this->sql_format($did, "s")." and 
                  (".$cu->flags." & ".$fu['DB_DISABLED'].") = 0";

        $res=$this->db->query($q);
        if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

        /* add the list to UIDs array */        
        while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) $uids[$row['uid']] = true;


        /* get list of UIDs which have credentials asociated with the domain */
        if ($config->auth['use_did']){
            $q="select distinct ".$cc->uid." as uid
                from ".$tc_name."
                where  ".$cc->did." = ".$this->sql_format($did, "s")." and 
                      (".$cc->flags." & ".$fc['DB_DISABLED'].") = 0";
        }
        else{
            if (!$realms_w1) $realms_w1=array($this-sql_format(false, "b"));

            $q="select distinct ".$cc->uid." as uid
                from ".$tc_name."
                where  (".implode($realms_w1, " or ").") and 
                      (".$cc->flags." & ".$fc['DB_DISABLED'].") = 0";
        }

        $res=$this->db->query($q);
        if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
        
        /* add the list to UIDs array */        
        while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) $uids[$row['uid']] = true;


        /* get list of UIDs which have URI asociated with other domains */
        $q="select distinct ".$cu->uid." as uid
            from ".$tu_name."
            where  ".$cu->did." != ".$this->sql_format($did, "s")." and 
                  (".$cu->flags." & ".$fu['DB_DISABLED'].") = 0";

        $res=$this->db->query($q);
        if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
        
        /* and remove them from UIDs array */
        while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
            if (isset($uids[$row['uid']])) unset($uids[$row['uid']]);
        }


        /* get list of UIDs which have credentials asociated with other domains */
        if ($config->auth['use_did']){
            $q="select distinct ".$cc->uid." as uid
                from ".$tc_name."
                where  ".$cc->did." != ".$this->sql_format($did, "s")." and 
                      (".$cc->flags." & ".$fc['DB_DISABLED'].") = 0";
        }
        else{
            if (!$realms_w2) $realms_w1=array($this-sql_format(true, "b"));

            $q="select distinct ".$cc->uid." as uid
                from ".$tc_name."
                where  (".implode($realms_w2, " and ").") and 
                      (".$cc->flags." & ".$fc['DB_DISABLED'].") = 0";
        }
        
        $res=$this->db->query($q);
        if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

        /* and remove them from UIDs array */
        while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
            if (isset($uids[$row['uid']])) unset($uids[$row['uid']]);
        }

        return array_keys($uids);
    }    
}
?>
