/*
 * $Id: whitelist.js,v 1.1 2005/08/24 12:17:31 kozlik Exp $
 */
 
function select_all_options(sel_element){
	for (var i=0; i<sel_element.options.length ;i++){
		sel_element.options[i].selected=true;
	}
}

function update_option(sel_el, new_val, old_val){
	/* find index of element */
	var index=-1;
	for(var i=0; i<sel_el.length; i++){
		if (sel_el.options[i].value == old_val){
			index=i;
			break;
		}
	}
	
	/* can't update option, index not found */
	if (index<0) return false;
	
	/* update option */
	sel_el.options[index].value = new_val;
	sel_el.options[index].text = new_val;
	
	return true;
}

function wlist_add(sel_el, txt_el, hidden_el){
	/* not value entered */
	if (txt_el.value==''){
		hidden_el.value='';	/* be sure that there is nothing */
		return;
	}

	/* if exists validating function, validate url */
	if (window.wlist_validate){
		if (!window.wlist_validate(sel_el, txt_el, hidden_el)){
			txt_el.focus();
			return;
		}
	}
	
	/* if we editing item */
	if (hidden_el.value != ''){
		/* try update */
		if (update_option(sel_el, txt_el.value, hidden_el.value)){
			txt_el.value='';
			hidden_el.value='';
			return;
		}
		/* if update fails, simply add option */
	}
	
	var opt = new Option(txt_el.value, txt_el.value);
	sel_el.options.add(opt, sel_el.length);
	
	txt_el.value='';
	hidden_el.value='';
}

function wlist_edit(sel_el, txt_el, hidden_el){
	if (sel_el.selectedIndex < 0) return; /* nothing selected */
	hidden_el.value = sel_el.options[sel_el.selectedIndex].value;
	txt_el.value = sel_el.options[sel_el.selectedIndex].text;
}

function wlist_drop(sel_el, txt_el, hidden_el){
	if (sel_el.selectedIndex < 0) return; /* nothing selected */
	sel_el.remove(sel_el.selectedIndex);
}

