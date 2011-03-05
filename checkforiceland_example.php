<?php
	
// USAGE EXAMPLE:
require_once('checkforiceland_function.php');

$address = $_SERVER['REMOTE_ADDR'];
//$address = $_GET['ip'];

if ( ! checkforiceland($address))
{
	echo "The IP address $address is not icelandic.";
}
else
{
	echo "The IP address $address is icelandic. Yay!";
}

// You can now do what ever you want with the result.

?>
