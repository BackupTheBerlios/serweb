<?php
// $Id: mail.php,v 1.4 2006/11/02 10:02:26 kozlik Exp $

	$to      = "root@localhost";
	$subject = "php	testing mail";
	$body    = "GREAT! \n it working\n";
	
	
	if (mail($to, $subject, $body))
		echo "Mail sent succesfully\n";
	else
		echo "Error sending mail. Check your PHP and sendmail configuration.\n";
?>
