/*
 * $Id: functions.js,v 1.2 2004/09/21 13:34:05 kozlik Exp $
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
