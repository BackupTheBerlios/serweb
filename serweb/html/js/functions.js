/**
 *  Various javascript functions used on most of pages
 * 
 *  $Id: functions.js,v 1.17 2009/10/29 13:01:03 kozlik Exp $
 */


/**
 *	Execute function in diferent scope
 */ 
Function.prototype.bindObj = function(object) {
	var __method = this;
	return function() {
		return __method.apply(object, arguments);
	}
} 

/**
 *	Trim methods for a string
 */ 
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}

String.prototype.ltrim = function() {
	return this.replace(/^\s+/,"");
}

String.prototype.rtrim = function() {
	return this.replace(/\s+$/,"");
}

/* confirm click to <a href=""> */

function linkConfirmation(theLink, message){
    var is_confirmed = confirm(message);
    if (is_confirmed) {
        theLink.href;
    }
    return is_confirmed;
}

/* deprecated */
function confirmDelete(theLink, message){
    return linkConfirmation(theLink, message);
}

/* show window with stun applet */

var stun_win=null;

function stun_applet_win(script, width, height){
	if (stun_win != null) stun_win.close();
		stun_win=window.open(script, "stun_win", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,top=20,left=20,width=" + width + ",height=" + height);
		stun_win.window.focus();
		return;
}

/* show wizard window */

var wizard_win=null;

function open_wizard_win(url){
	var width = 930; 
	var height = 550;
	var x = (screen.width - width) / 2;
	var y = (screen.height - height) / 2;

	if (wizard_win != null) wizard_win.close();
		wizard_win=window.open(url, "wizard_win", "toolbar=no,location=no,directories=no,status=yes,menubar=yes,scrollbars=yes,resizable=yes,top="+ x +",left="+ y +",width=" + width + ",height=" + height);
		wizard_win.window.focus();
		return;
}

/**
 *	Send a synchronic http request 
 *	
 *	Send http request with method POST and 'post_data' in its body.
 *	If param 'post_data' is not present, method GET is used instead POST 
 *
 *  @param	string	url			URL of the request
 *  @param	string	post_data 	data sent in the request
 *  @return http_request		result of the requst
 */  
 
function ajax_sync_request(url, post_data){
	var http_request;

	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		http_request = new XMLHttpRequest();
	} else if (window.ActiveXObject) { // IE
		http_request = new ActiveXObject('Microsoft.XMLHTTP');
	} else return null;
	

	if (post_data){
		http_request.open('POST', url, false);
		http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		http_request.send(post_data);
	}
	else{
		http_request.open('GET', url, false);
		http_request.send(null);
	}

	return http_request;
}

/**
 *	Send a asynchronic http request 
 *	
 *	Send http request with method POST and 'post_data' in its body.
 *	If param 'post_data' is not present, method GET is used instead POST 
 *
 *  @param	string		url			URL of the request
 *  @param	string		post_data 	data sent in the request
 *  @param	function	callback 	function called when httP request state change
 *  @return http_request			result of the requst
 */  
 
function ajax_async_request(url, post_data, callback){
	var http_request;

	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		http_request = new XMLHttpRequest();
	} else if (window.ActiveXObject) { // IE
		http_request = new ActiveXObject('Microsoft.XMLHTTP');
	} else return null;
	

	if (post_data){
		http_request.open('POST', url, true);
		http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		http_request.onreadystatechange = function() { callback(http_request); };
 		http_request.send(post_data);
	}
	else{
		http_request.open('GET', url, true);
		http_request.onreadystatechange = function() { callback(http_request); };
		http_request.send(null);
	}

	return http_request;
}

/**
 *	Add new css class to html element
 * 
 *	@param	object	el  
 *	@param	string	className
 */
function addClassNameToEl(el, className){
	el.className += " " + className;
}

/**
 *	Remove the css class to html element
 * 
 *	@param	object	el  
 *	@param	string	className
 */
function remClassNameFromEl(el, className){
	var newClassName = "";
	var classNames = el.className.split(' ');
		
	for (var i=0; i<classNames.length; i++){
		if (classNames[i] != className) newClassName += " "+classNames[i];
	}

	el.className = newClassName;
}

/* toggle visibility of an element */

function toggle_visibility(el){
	if (el.style.display=="none" || el.style.display==""){
		el.style.display = "block";
	}
	else{
		el.style.display = "none";
	}
}

/**
 *      Return the item of radio button with given value
 */ 
function get_radio_by_value(el, val){

    for (var i=0; i<el.length; i++){
        if (el.item(i).value == val){
            return el.item(i);
        }
    }
    return null;
}

/**
 *  Get element identified by its tag name and class name
 *      
 *  @param  Element parentEl     parrent element of the tree inside which the search is performed
 *  @param  string  tagName      name of tag to search
 *  @param  string  className    name of class
 *  @return Element 
 */ 
function get_element_by_className(parentEl, tagName, className){

    var elements = parentEl.getElementsByTagName(tagName);
    var classNames;
    
    for (var i=0; i<elements.length; i++){
        classNames = elements[i].className.split(' ');
        for (var j=0; j<classNames.length; j++){
            if (classNames[j] == className) return elements[i];
        }
    }

    return null;
}

/**
 *      Set selectedIndex of select by value of option
 */ 
function set_select_by_value(el, val){

    for (var i=0; i<el.options.length; i++){
        if (el.options[i].value == val){
            el.selectedIndex=i;
            return true;
        }
    }
    return false;
}

/**
 *      Enable/disable a link
 *      
 *  THis function expect the &lt;a&gt; element wrapped within &lt;span&gt; element. 
 *  Reference to the &lt;span&gt; should be parameter of the function. Function
 *  also expect the link is initialy enabled. 
 *      
 *  @param  bool                en      enable/disable
 *  @param  Element parentEl    parrent &lt;span&gt; element which wrap the &lt;a&gt; element
 */ 
function enable_link(en, parentEl){
    
    var linkEls = parentEl.getElementsByTagName('a');
    
    if (en){ //enable
        if (linkEls.length > 0) return; // links already enabled

        // restore content of the wraping <span> element
        parentEl.innerHTML = parentEl.linkUserData;
        // delete the stored data
        parentEl.linkUserData = null;
    }
    else{    //disable
        if (linkEls.length == 0) return; // no links enabled
        
        // save content of wraping <span> element
        parentEl.linkUserData = parentEl.innerHTML;
        // and replace content of the <span> element with another one
        parentEl.innerHTML = '<span class="disabledLink">'+linkEls[0].innerHTML+'</span>';
    }
}

/**
 *  This function is same as HTMLSpecialChars function from PHP
 */ 
function HTMLSpecialChars(str){
    str = str.replace("&", "&amp;", "g");
    str = str.replace("<", "&lt;", "g");
    str = str.replace(">", "&gt;", "g");
    str = str.replace("\"", "&quot;", "g");
    str = str.replace("'", "&#039;", "g");
    return str;
}


/**
 *  Parse host part from sip uri
 *    
 *  @param  string  uri     sip uri
 *  @return string          hostpart or FALSE on invalid uri 
 */
function parse_host_from_sip_uri(uri){


    if      (uri.substr(0,4).toLowerCase() == 'sip:')  uri = uri.substr(4); //strip initial 'sip:'
    else if (uri.substr(0,5).toLowerCase() == 'sips:') uri = uri.substr(5); //strip initial 'sips:'
    else    return false; //not valid uri
    
    var ipv6 = 0;
    var hostpos = uri.indexOf('@');
    var hostlen = null;

    if ( hostpos < 0 ) hostpos = 0;
    else               hostpos++; 

    for (var i=hostpos; (i < uri.length) && (hostlen == null); i++){
        switch (uri.substr(i, 1)){
        case '[':  ipv6++; break;
        case ']':  ipv6--; break;
        case ':':
                   if (!ipv6){ //colon is not part of ipv6 address
                        hostlen = i-1;  //colon is separator of host and port
                        break;
                   } 
                   break;
        case ';':
                   hostlen = i-1;  //semicolon is start of uri parameters
                   break;
        }
    }

    if (hostlen == null) hostlen = uri.length;

    // hostlen now do not contain real lenght of host part, 
    // but the position of its end, so calculate the length:
    
    hostlen = hostlen - hostpos + 1;
    
    return uri.substr(hostpos, hostlen);
}


/**
 *  Parse port from sip uri
 *    
 *  @param  string  uri     sip uri
 *  @return int             port number or FALSE on invalid uri or NULL when no port in the uri  
 */
function parse_port_from_sip_uri(uri){

    if      (uri.substr(0,4).toLowerCase() == 'sip:')  uri = uri.substr(4); //strip initial 'sip:'
    else if (uri.substr(0,5).toLowerCase() == 'sips:') uri = uri.substr(5); //strip initial 'sips:'
    else    return false; //not valid uri
    
    var ipv6 = 0;
    var portpos = null;
    var ch;

    /* start parsing after '@' to avoid some special characters in user part */
    var startpos = uri.indexOf('@');
    if ( startpos < 0 ) startpos = 0;
    else                startpos++; 

    for (var i=startpos; (i < uri.length) && (portpos == null); i++){
        ch = uri.substr(i, 1);

        switch (ch){
        case '[':  ipv6++; break;
        case ']':  ipv6--; break;
        case ':':
                   if (!ipv6){ //colon is not part of ipv6 address
                        portpos = i;  //position of port inside address string
                        break;
                   } 
                   break;
        case ';':
                   return null;  //start of uri parameters -> no port in the uri
                   break;
        }
    }

    if (portpos == null) return null;   //no port in the uri

    portpos++; //move after the colon
    var portlen = 0;

    for (var i=portpos; i < uri.length; i++){
        ch = uri.substr(i, 1);

        if (ch<'0' || ch>'9') break;
        portlen++;
    }

    if (portlen == 0) return false; //no port in uri, but it contains colon -> invalid uri

    var port = Number(uri.substr(portpos, portlen));
    if (port == Number.NaN)     return false; //should never happen, but to be sure...
   
    return port;
}


/**
 *  Register handler (fn) of event (evt) on object (obj) - browser independent
 */
add_event = function(obj, evt, fn){
    if (obj.addEventListener) //w3c model
        obj.addEventListener(evt, fn, false);
    else if (obj.attachEvent) //MS model
        obj.attachEvent('on'+evt, fn);
    else //other
        obj['on'+evt] = fn;
}

/**
 *  Unregister handler (fn) of event (evt) on object (obj) - browser independent
 */
remove_event = function(obj, evt, fn){
       if (obj.removeEventListener) //w3c model
               obj.removeEventListener(evt, fn, false);
       else if (obj.detachEvent) //MS model
               obj.detachEvent('on'+evt, fn);
    else //other
        obj['on'+evt] = null;
}




/***********************************************************
 * Functions for display varning message if form is changed
 ***********************************************************/

	/* main function - add it to onclick event of all <a> tags */
		
	function __link_confirmation_if_changed(theLink, message){
		if (this.changed || this.compareElem()) return linkConfirmation(theLink, message);
		return true;
	}
	
	/* deprecated */	
	function __reset_changed(){
		this.changed=false;
	}
	
	/* deprecated */	
	function __set_changed(){
		this.changed=true;
	}
	
	/* save states of form - call it on page load */
	function __getForm() {
		//if there are no forms on the page
		if (!document.forms || !document.forms.length) return false;
	
		var f = this.whichForm;
		var l = f.length;
		for (var i = 0; i < l; i++) {
			if (f.elements[i].type == 'text' || f.elements[i].type == 'textarea' || f.elements[i].type == 'select-one') {
				this.startElem[i] = f.elements[i].value;
			}
			if (f.elements[i].type == 'checkbox' || f.elements[i].type == 'radio') {
				this.startElem[i] = f.elements[i].checked;
			}
		}
		return true;
	}
	
	/* compare form with saved state */
	function __compareElem() {
		//if there are no forms on the page
		if (!document.forms || !document.forms.length) return false;
		//if startElem was not filled return false
		if (!this.startElem.length) return false;
	
		var f = this.whichForm;
		var l = f.length;
		
		for (var i = 0; i < l; i++) {
			if ((f.elements[i].type == 'text' || f.elements[i].type == 'textarea' || f.elements[i].type == 'select-one') && this.startElem[i] != f.elements[i].value) {
				return true;
			}
			if ((f.elements[i].type == 'checkbox' || f.elements[i].type == 'radio') && this.startElem[i] != f.elements[i].checked) {
				return true;
			}
		}
		
		return false;
	}

	function FormCheck(){
		var changed;
		var startElem;		//array to which are stored states of all elements of form
		var whichForm;
		
		this.changed = false;
		this.startElem = new Array();
		this.whichForm = document.forms[0];
		
		this.reset_changed = __reset_changed;
		this.set_changed = __set_changed;

		this.link_confirmation_if_changed = __link_confirmation_if_changed;
		
		this.getForm = __getForm;
		this.compareElem = __compareElem;
		
		this.getForm();
	}
	
/******************************************************/
