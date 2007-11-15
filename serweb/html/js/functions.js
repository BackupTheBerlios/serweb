/**
 *  Various javascript functions used on most of pages
 * 
 *  $Id: functions.js,v 1.11 2007/11/15 09:50:48 kozlik Exp $
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
