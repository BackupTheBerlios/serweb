<?php

class DomainManipulator{
    var $did;
    var $dom_names = null;

    /**
     *
     * @access private
     */
    function DomainManipulator($did){
        $this->did = $did;
    }

    /**
     * Return a reference to a DomainManipulator instance, only creating a new instance 
     * if no DomainManipulator instance currently exists.
     *
     * You should use this if there are multiple places you might create a
     * DomainManipulator, you don't want to create multiple instances, and you don't 
     * want to check for the existance of one each time. The singleton pattern 
     * does all the checking work for you.
     *
     * <b>You MUST call this method with the $var = &DomainManipulator::singleton($did) 
     * syntax. Without the ampersand (&) in front of the method name, you will 
     * not get a reference, you will get a copy.</b>
     *
     * @access public
     */

    function &singleton($did) {
        static $instances = array();

        if (!isset($instances[$did])) $instances[$did] = new DomainManipulator($did);
        return $instances[$did];
    }



    /**
     *  Method create new alias of the domain. 
     *
     *  Allowed options:
     *    - 'enabled'
     *
     *  @param string $did      domain ID
     *  @param string $alias    name of new alias
     *  @param array $opt       array of options
     *  @return bool            TRUE on success, FALSE on error
     *  @static
     */
    function add_alias($did, $alias, $opt){
        global $data;

        $data->add_method('add_domain_alias');
        $data->add_method('reload_domains');

        $errors = array();

        $values['id'] = $did;
        $values['name'] = $alias;

        $dm_h = &DomainManipulator::singleton($did);
        if (false === $dom_names = $dm_h->get_domain_names(null)) return false;
        
        if (isset($opt['enabled'])){
            $disabled = !(bool)$opt['enabled'];
        }
        else{
            if (count($dom_names)){
                $disabled = true;
                // domain is disabled if all domain names are disabled
                foreach($dom_names as $v){
                    $disabled = ($disabled and ((bool)$v['disabled']));
                }
            }
            else{
                // POZOR! tady muze obcas nastavat problem pokud neexistujou zaznamy v DB
                // muze dojit k znovuaktivovani domeny
                $disabled = false;
            }
        }

        $o = array();
        $o['disabled'] = $disabled;
        $o['set_canon'] = !(bool)count($dom_names);

        if (false === $data->add_domain_alias($values, $o, $errors)) {
            ErrorHandler::add_error($errors);
            return false;
        }
                
        /* create symlinks and notify ser only if domain isn't disabled */
        if (!$disabled){
            if (false === domain_create_symlinks($did, $values['name'], $errors)) {
                ErrorHandler::add_error($errors);
                return false;
            }
    
            /* notify SER to reload domains */
            if (false === $data->reload_domains(null, $errors)) {
                ErrorHandler::add_error($errors);
                return false;
            }
        }
    
        return true;
    }


    /**
     *  Method update the owner of domain, digest realm and sop_vm_domain. 
     *
     *  Attributes:
     *    - owner_id    - ID of owner (customer)
     *    - alias       - ???
     *
     *  @param string $did      doamin ID
     *  @param array  $attrs    array of attribute values
     *  @return bool            TRUE on success, FALSE on error
     *  @static
     */
    function update_domain_attrs($did, $attrs){
        global $config;
        
        $an = &$config->attr_names;
        $da_h = &Domain_Attrs::singleton($did);
        
        if (false === $domain_attrs = $da_h->get_attributes()) return false;

        if (isset($attrs['owner_id'])){
            $cur_owner_id = null;
            if (isset($domain_attrs[$an['dom_owner']])){
                $cur_owner_id = $domain_attrs[$an['dom_owner']];
            }
    
    
            if (!is_null($attrs['owner_id']) and $attrs['owner_id'] != $cur_owner_id){
                if ($attrs['owner_id'] == -1){
                    if (false === $da_h->unset_attribute($an['dom_owner'])){
                        return false;
                    }
                }
                else{
                    if (false === $da_h->set_attribute($an['dom_owner'], $attrs['owner_id'])){
                        return false;
                    }
                }
            }
        }

        /*
         *  If digest realm is not set, (or 'sop_vm_domain' is defined and is not set) 
         *  set it by the canonical domain name
         */
        $digest_realm = "";
        if ( !isset($domain_attrs[$an['digest_realm']]) or 
            (!empty($an['sop_vm_domain']) and !isset($domain_attrs[$an['sop_vm_domain']]))){


            $dm_h = &DomainManipulator::singleton($did);
            if (false === $dom_names = $dm_h->get_domain_names(null)) return false;
    
            foreach($dom_names as $v){
                if ($v['canon']) {
                    $digest_realm = $v['name'];
                    break;
                }
            }

            if (!$digest_realm and !empty($attrs['alias'])) 
                $digest_realm = $attrs['alias'];

            if ($digest_realm and 
                false === $da_h->set_attribute($an['digest_realm'], $digest_realm)){
                return false;
            }
        }

        if ($digest_realm and !isset($domain_attrs[$an['digest_realm']])){
            if (false === $da_h->set_attribute($an['digest_realm'], $digest_realm)){
                return false;
            }
        }

        if ($digest_realm and !empty($an['sop_vm_domain']) and !isset($domain_attrs[$an['sop_vm_domain']])){
            if (false === $da_h->set_attribute($an['sop_vm_domain'], $digest_realm)){
                return false;
            }
        }


        return true;    
    }




    /**
     *  Method return a list of all domain names (aliases) 
     *
     *  @param array $opt       osociative array of options - reserved for future use
     *  @return array           array of domain names or FALSE on error
     */

    function &get_domain_names($opt){
        global $data;

        $data->add_method('get_domain');

        if (!is_null($this->dom_names)) return $this->dom_names;

        $false = false;

        $opt = array();
        $opt['filter']['did'] = $this->did;
        
        if (false === $dom_names = $data->get_domain($opt)) return $false;
        $this->dom_names = $dom_names;

        return $this->dom_names;        
    }

	/**
	 *	Method create or remove symlinks for all aliases of the domain
	 *
	 *	Method create or remove symlinks in directory with domain specific config and 
	 *	in directory with virtual hosts (for purpose of apache)
	 *
	 *	@param bool  $create	if true, function create symlinks, otherwise remove them
	 *	@return bool			return TRUE on success, FALSE on failure
	 */

	function create_or_remove_all_symlinks($create){

        $errors = array();
		
		if (false === $dom_names = $this->get_domain_names(null)) return false;
		
		foreach($dom_names as $v){
			if ($create){
				if (false === domain_create_symlinks($this->did, $v['name'], $errors)) {
                    ErrorHandler::add_error($errors);
                    return false;
                }
			}
			else{
				if (false === domain_remove_symlinks($v['name'], $errors)) {
                    ErrorHandler::add_error($errors);
                    return false;
                }
			}
		}
		return true;
	}


	/**
	 *	Method delete domain
	 *
	 *	@return bool			return TRUE on success, FALSE on failure
	 */
    function delete_domain(){
        global $data;

        $data->add_method('mark_domain_deleted');
        $data->add_method('reload_domains');

        $errors = array();
		if (false === $this->create_or_remove_all_symlinks(false)) return false;

		$opt['did'] = $this->did;
		if (false === $data->mark_domain_deleted($opt)) return false;

        $this->dom_names = array();
        
		/* notify SER to reload domains */
		if (false === $data->reload_domains(null, $errors)) {
            ErrorHandler::add_error($errors);
            return false;
        }
        
        return true;
    }

	/**
	 *	Method delete alias of the domain. 
	 *
	 *	@param string $alias	alias to delete
	 *	@return bool			return TRUE on success, FALSE on failure
	 */

	function delete_domain_alias($alias){
		global $data, $lang_str;

        $data->add_method('del_domain_alias');
        $data->add_method('reload_domains');

        $errors = array();
		if (false === $dom_names = $this->get_domain_names(null)) return false;

		if (count($dom_names) <= 1){
			$errors[] = $lang_str['can_not_del_last_dom_name'];
			return false;			
		}

		$opt['id'] = $this->did;
		$opt['name'] = $alias;

		if (false === domain_remove_symlinks($opt['name'], $errors)) {
            ErrorHandler::add_error($errors);
            return false;
        }

		if (false === $data->del_domain_alias($opt, $errors)) {
            ErrorHandler::add_error($errors);
            return false;
        }
        
        foreach($this->dom_names as $k=>$v){
            if ($v['name'] == $alias){ unset($this->dom_names[$k]); break; }
        }
        
		/* notify SER to reload domains */
		if (false === $data->reload_domains(null, $errors)) {
            ErrorHandler::add_error($errors);
            return false;
        }

        return true;
	}


	/**
	 *	Method enable or disable domain
	 *
	 *	@param bool  $enable	if true, function enable domain, otherwise disable it
	 *	@return bool			return TRUE on success, FALSE on failure
	 */

	function enable_domain($enable){
		global $data;

        $data->add_method('enable_domain');
        $data->add_method('reload_domains');

        $errors = array();
		FileJournal::clear();

		if ($enable){
			$opt['did'] = $this->did;
			$opt['disable'] = false;

			if (false === $this->create_or_remove_all_symlinks(true, $errors)) {
				FileJournal::rollback();
                ErrorHandler::add_error($errors);
				return false;
			}
		}
		else {
			$opt['did'] = $this->did;
			$opt['disable'] = true;

			if (false === $this->create_or_remove_all_symlinks(false, $errors)) {
				FileJournal::rollback();
                ErrorHandler::add_error($errors);
				return false;
			}
		}

		if (false === $data->enable_domain($opt)) {
			FileJournal::rollback();
			return false;
		}

		/* notify SER to reload domains */
		if (false === $data->reload_domains(null, $errors)) {
            ErrorHandler::add_error($errors);
            return false;
        }
        
        return true;
	}

}


?>
