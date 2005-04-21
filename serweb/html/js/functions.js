/*
 * $Id: functions.js,v 1.4 2005/04/21 15:09:46 kozlik Exp $
 */

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
	}
	
	/* compare form with saved state */
	function __compareElem() {
		//if there are no forms on the page
		if (!document.forms || !document.forms.length) return false;
		//if startElem wan not filled retrun false
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
