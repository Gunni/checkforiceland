<?php

// Usage example of the function
require_once('function/checkforiceland_function.php');

// Get the address by any means possible
$address = $_SERVER['REMOTE_ADDR'];

// Check what it is...
if (checkforiceland($address))
{
	printf("Function: The IP address %s is icelandic.<br>\n", $address);
}
else
{
	printf("Function: The IP address %s is not icelandic.<br>\n", $address);
}

// usage example of the class
require_once('class/checkforiceland_class.php');

// instance the class
//$cfi = new checkforiceland();
$cfi = new checkforiceland($address);

// re set the ip if needed
$cfi->setIP('::FFFF:2E16:693C');

// Check what it is...
if ($cfi->getIcelandic())
{
	printf("Class: The IP address %s is icelandic.<br>\n", $cfi->getIP());
}
else
{
	printf("Class: The IP address %s is not icelandic.<br>\n", $cfi->getIP());
}
