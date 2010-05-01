<?php
	
// USAGE EXAMPLE:
$address = $_SERVER['REMOTE_ADDR'];

if (ipicelandic($address) == false)
{
    echo "The IP address $address is not icelandic. A nuclear bomb has been dispatched and is now en route towards you.\n";
}
else
{
    echo "The IP address $address is icelandic. Yay!\n";
}

// You can now do what ever you want with the result.

	
?>