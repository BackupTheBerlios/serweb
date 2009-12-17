/**
 *  This file contain javascript functions used by apu_aliases
 *  
 *  This file require 'functions.js' file to be loaded
 *   
 */ 


function Aliases_ctl(varname){

    /* name of variable pointing to reference of this object */
    this.varname = varname;

    this.timeout = null;    

    this.uname = "";
    this.did   = null;
    
    this.onAliasChangeUrl = null;
    this.aliasSuggestUrl = null;
    this.aliasGenerateUrl = null;
    
    this.info_el_u = null;
    this.info_el_nu = null;
    this.suggest_el = null;

    this.suggest_domain = null;
    
    this.lang_str = new Object();
    this.lang_str.no_suggestions = "Sorry, no suggestions!";
}

/**
 *  init function
 *      
 */ 
Aliases_ctl.prototype.init = function(form_name){

    var forms = document.forms;
    
    for (var i=0; i<forms.length; i++){
        if (forms[i].name == form_name) {
            this.form = forms[i];
            break;
        }
    }

    this.info_el_u = document.getElementById('al_usage_info_used');
    this.info_el_nu = document.getElementById('al_usage_info_not_used');
    this.suggest_el = document.getElementById('aliasSuggestionsPlace');

	this.form.al_username.setAttribute("autocomplete","off");
}


Aliases_ctl.prototype.onAliasChange = function(){

    var url;
    var uname_el = this.form.al_username;
    var did_el = this.form.al_domain;

    var uname = uname_el.value;
    var did =   did_el.options[did_el.selectedIndex].value;

    // return if nothing changed
    if (this.uname == uname && this.did == did) return;

    this.uname = uname; 
    this.did = did;

    if (uname == ""){
        if (this.info_el_u)  this.info_el_u.style.display="none";
        if (this.info_el_nu) this.info_el_nu.style.display="none";
        return;
    }


    url = this.onAliasChangeUrl;
    url += "&al_uname="+escape(uname);
    url += "&al_did="+escape(did);


	var oThis = this;
	this._asyncajax = function () {
        ajax_async_request(url, null,   
                           oThis.onAliasChange_callback.bindObj(oThis));
	};


    if (this.timeout != null) 
        window.clearTimeout(this.timeout);
        
	this.timeout = window.setTimeout(this._asyncajax, 500);

}



Aliases_ctl.prototype.onAliasChange_callback = function(http_request){

    if (http_request.readyState == 4) { 

        var response = eval('(' + http_request.responseText + ')');;

        if (response.uri_used) {
            if (this.info_el_u)  this.info_el_u.style.display="";
            if (this.info_el_nu) this.info_el_nu.style.display="none";
        }
        else{
            if (this.info_el_u)  this.info_el_u.style.display="none";
            if (this.info_el_nu) this.info_el_nu.style.display="";
        }
    }
}

Aliases_ctl.prototype.useSuggestion = function(uname){

    var uname_el = this.form.al_username;
    uname_el.value = uname;

    // close popup window if any is open
    if (over) cClick();

    this.onAliasChange();
}

Aliases_ctl.prototype.aliasSuggest = function(){

    var url;
    var uname_el = this.form.al_username;
    var did_el = this.form.al_domain;

    var uname = uname_el.value;
    var did =   did_el.options[did_el.selectedIndex].value;

    if (uname == ""){
        return;
    }

    this.suggest_domain = did_el.options[did_el.selectedIndex].text;

    url = this.aliasSuggestUrl;
    url += "&al_uname="+escape(uname);
    url += "&al_did="+escape(did);

    ajax_async_request(url, null,   
                       this.aliasSuggest_callback.bindObj(this));
}


Aliases_ctl.prototype.aliasSuggest_callback = function(http_request){

    if (http_request.readyState == 4) { 

        var response = eval('(' + http_request.responseText + ')');

        var html_suggest="";
        if (response.suggested_uris && this.suggest_el) {
            
            if (response.suggested_uris.length == 0){
                html_suggest = "<div class='aliasSuggestPopupNS'>"+this.lang_str.no_suggestions+"</div>";
            }
            else{
                html_suggest = "<div class='aliasSuggestPopup'><table>";
                for (var i=0; i<response.suggested_uris.length; i++){
                    html_suggest += "<tr><td><a href='javascript:"+this.varname+".useSuggestion(\""+response.suggested_uris[i]+"\");'>" +
                                    response.suggested_uris[i] + "@" + this.suggest_domain +
                                    "</a></td></tr>";
                }
                html_suggest += "</table></div>";
            }
            
            var pos = getAbsolutePosition(this.suggest_el)
            
            overlib(html_suggest, STICKY, FIXX, pos[0], FIXY, pos[1], 
                    CAPTION, "&nbsp;" );
        }
    }
}

Aliases_ctl.prototype.aliasGenerate = function(){

    var url;
    var did_el = this.form.al_domain;

    var did =   did_el.options[did_el.selectedIndex].value;

    url = this.aliasGenerateUrl;
    url += "&al_did="+escape(did);

    ajax_async_request(url, null,   
                       this.aliasGenerate_callback.bindObj(this));
}


Aliases_ctl.prototype.aliasGenerate_callback = function(http_request){

    if (http_request.readyState == 4) { 

        var response = eval('(' + http_request.responseText + ')');
        var uname_el = this.form.al_username;

        if (response.uri_uname) {
            uname_el.value = response.uri_uname;
            
            this.onAliasChange()
        }
    }
}
