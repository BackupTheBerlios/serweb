/*
 * $Id: instant_message.js,v 1.1 2005/08/24 08:55:04 kozlik Exp $
 */


function im_countit(f, max_len, err_msg){
	var im_len = f.im_instant_message.value.length;

	if (im_len > max_len){
		f.im_instant_message.value = f.im_instant_message.value.substr(0, max_len);
		alert(err_msg+' '+max_len);
		return 0;
	}
	else
		f.im_num_chars.value = max_len - im_len;
}

function im_display_window(url){
	var left=window.screen.width-350;
	wait_win=window.open(url, "wait_win", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,top=50,width=300,height=130,left="+left);
	wait_win.window.focus();
}

function im_close_window(){
	wait_win=window.open('',"wait_win","width=1,height=1,top=0,left=0");	//get reference to window
	wait_win.close();						//close the window
}
