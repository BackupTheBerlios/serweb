/*
 * $Id: ctd.js,v 1.2 2003/05/07 08:03:16 kozlik Exp $
 */

var ctd_win=null;

function open_ctd_win(target){
	if (ctd_win != null) ctd_win.close();
	ctd_win=window.open("ctd.php?target="+target+"&kvrk="+Date.parse(new Date()),"ctd_win","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,top=20,left=20,width=350,height=100");
	ctd_win.window.focus();
	return;
}

function open_ctd_win2(target, uri){
	if (ctd_win != null) ctd_win.close();
	ctd_win=window.open("ctd.php?target="+target+"&uri="+uri+"&kvrk="+Date.parse(new Date()),"ctd_win","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,top=20,left=20,width=350,height=100");
	ctd_win.window.focus();
	return;
}

