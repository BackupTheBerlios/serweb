<?php
// $Id: mail.php,v 1.2 2005/07/21 16:25:17 kozlik Exp $

	$to      = "root@localhost";
	$to      = "karel@iptel.org";
	$subject = "php	testing mail";
	$body    = "GREAT! \n it working\n";
	
	
	if (mail($to, $subject, $body))
		echo "Mail sended succesfully\n";
	else
		echo "Error sending mail. Check your PHP and sendmail configuration.\n";
?>
