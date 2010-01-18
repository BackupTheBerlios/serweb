<?php

class URI_functions{

    /**
     *  Suggests list of usernames similar to given $uname that ara still
     *  available within given domain.
     *  
     *  @param  string  $uname
     *  @param  string  $did
     *  @return array                         
     */         
    function suggest_uri($uname, $did){
        global $data;

        $data->add_method("get_uris");

        if (ereg("^[0-9]+$", $uname)){
            // suggestion for numeric aliases
            $uris = array();
            for ($i=0; $i<strlen($uname); $i++){
                $gen_uris = array();
                for ($j=0; $j<10; $j++) $gen_uris[substr_replace($uname, $j, $i, 1)] = 1;
            
                $opt = array(); 
                $opt['filter']['did'] = new Filter("did", $did, "=", false, false);
                $opt['filter']['username'] = new Filter("username", substr_replace($uname, "?", $i, 1), "like", false, false);
                if (false === $used_uris = $data->get_uris(null, $opt)) return false;
    
                foreach($used_uris as $v) unset($gen_uris[$v->username]);
    
                $uris = array_merge($uris, array_keys($gen_uris));
            }
        }
        else{
            $gen_uris = array();
            for ($j=0; $j<10; $j++) $gen_uris[$uname.$j] = 1;

            $opt = array(); 
            $opt['filter']['did'] = new Filter("did", $did, "=", false, false);
            $opt['filter']['username'] = new Filter("username", $uname."?", "like", false, false);
            if (false === $used_uris = $data->get_uris(null, $opt)) return false;

            foreach($used_uris as $v) unset($gen_uris[$v->username]);

            $uris = array_keys($gen_uris);
        }

        sort($uris);
        
        return $uris;
    }

}

?>
