<?php
// $Id: mail.php,v 1.3 2005/08/25 07:50:29 kozlik Exp $

	$to      = "root@localhost";
	$subject = "php	testing mail";
	$body    = "GREAT! \n it working\n";
	
	
	if (mail($to, $subject, $body))
		echo "Mail sended succesfully\n";
	else
		echo "Error sending mail. Check your PHP and sendmail configuration.\n";
?>
