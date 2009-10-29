/**
 *  This file contain javascript functions used by attr_types
 *  
 *  This file require 'overlib/overlib.js' file to be loaded
 */ 

/**
 *  Constructor
 *  
 *  @param  string  varname     name of global variable containing created object
 *  @param  lang_str            object      object containing localised strings
 */ 
function Attr_types_ctl(varname, lang_str, url_rename){

    /* name of variable pointing to reference of this object */
    this.varname = varname;

    this.lang_str = lang_str;

    this.url_rename = url_rename;
}


/**
 *  handler called when form from cHeaderDialog is submited
 */ 
Attr_types_ctl.prototype.groupNameSubmit = function(){
    var form = document.getElementById('at_group_name_form');

    if (form.new_group_name.value.trim()=="") {
        alert(this.lang_str.err_at_new_grp_empty);
        form.new_group_name.focus();
        return;
    }

    cClick();
    document.location.href=this.url_rename+
            "&old_group_name="+encodeURIComponent(form.old_group_name.value)+
            "&new_group_name="+encodeURIComponent(form.new_group_name.value);
};


/**
 *  Open popup window for enter name of header
 */ 
Attr_types_ctl.prototype.groupNameDialog = function(grp_name){

    var content;
    
    content  = "<div class='popup'>";
    content += "<form name='at_group_name_form' id='at_group_name_form' action='javascript:"+this.varname+".groupNameSubmit();'>";

    content += "<div >";
    content += "<div class='popupFormCol'>";
    content += "<span class='popupFormItem'>";
    content += "<label for='new_group_name'>"+HTMLSpecialChars(this.lang_str.ff_new_group)+"</label> ";
    content += "<input type='text' name='new_group_name' value='"+HTMLSpecialChars(grp_name)+"' class='inpText' />";
    content += "<input type='hidden' name='old_group_name' value='"+HTMLSpecialChars(grp_name)+"' />";
    content += "</span>";
    content += "</div>";

    content += "<br class='swCleaner'/>";

    content += "<div >";
    content += "<div class='popupFormSubmitCol'>";
    content += "<span class='popupFormItem'>";
    content += "<input type='submit' value='Ok' class='inpSubmit' />";
    content += "</span>";
    content += "</div>";
    content += "<div class='popupFormSubmitCol'>";
    content += "<span class='popupFormItem'>";
    content += "<input type='reset' value='Cancel' class='inpSubmit' onclick='cClick();'/>";
    content += "</span>";
    content += "</div>";
    content += "</div>";

    content += "</form>";
    content += "</div>";

    overlib(content, STICKY, CAPTION, this.lang_str.title_group_rename+" '"+grp_name+"'", 
            CENTER, WIDTH, 248);

    var form = document.getElementById('at_group_name_form');
    form.new_group_name.focus();
    
    return;   
};

