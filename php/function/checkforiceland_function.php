<?php
/*
	checkforiceland - Version 2.9 (2010-10-01)
	
	Copyright (C) 2010  Gunnar Guðvarðarson, Gabríel A. Pétursson

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:

		1. Redistributions of source code must retain the above copyright
		   notice, this list of conditions and the following disclaimer.
		2. Redistributions in binary form must reproduce the above copyright
		   notice, this list of conditions and the following disclaimer in the
		   documentation and/or other materials provided with the distribution.
		3. The name of the author may not be used to endorse or promote
		   products derived from this software without specific prior written
		   permission.

	THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
	IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
	OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN
	NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
	SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
	TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
	PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
	LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
	NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/*
In order to take advantage of the caching feature, the database has to
be configured properly and the following table created:

CREATE TABLE IF NOT EXISTS `checkforiceland_cache` (
	`ip`        VARBINARY(16) NOT NULL,
	`icelandic` TINYINT(1)    NOT NULL,
	`when`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
	
	PRIMARY KEY (`ip`),
	KEY `when` (`when`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/

function checkforiceland($ip)
{
	// Database configurations.
	// Please be aware that if checkforiceland is unable to connect to the,
	// database, it will fail silently and fallback to DNS querying each
	// request.
	$database_hostname = 'localhost';
	$database_username = 'username';
	$database_password = 'password';
	$database_database = 'database';
	
	$inbinary = inet_pton($ip);
	
	$mysqli = @new mysqli($database_hostname, $database_username, $database_password, $database_database);
	
	if ($mysqli->connect_errno == 0)
	{
		if ($stmt = $mysqli->prepare("DELETE FROM `checkforiceland_cache` WHERE `when` <= TIMESTAMP(NOW(), '-48:00:00')"))
		{
			$stmt->execute();
			$stmt->close();
		}
		
		if ($stmt = $mysqli->prepare("SELECT `icelandic` FROM `checkforiceland_cache` WHERE `ip` = ?"))
		{
			$stmt->bind_param('s', $inbinary);
			$stmt->execute();
			$stmt->bind_result($isl);
			$success = $stmt->fetch();
			$stmt->close();
			
			if ($success == true)
			{
				if ($isl > 0)
					return true;
				else
					return false;
			}
		}
	}
	
	$is_icelandic    = false;
	$is_ipv6         = strpos($ip, ':') !== false;
	$is_ipv6_ipv4map = true;
	
	if ($is_ipv6)
	{
		for ($i = 0; $i < 10; $i++)
		{
			if ($inbinary[$i] != 0x00)
			{
				$is_ipv6_ipv4map = false;
			}
		}
		
		if (ord($inbinary[10]) != 0xff OR ord($inbinary[11]) != 0xff)
		{
			$is_ipv6_ipv4map = false;
		}
	}
	
	if ($is_ipv6 AND $is_ipv6_ipv4map == false)
	{
		$result = '';
		
		for ($i = 15; $i >= 0; $i--)
		{
			$piece = ord($inbinary[$i]);
			
			if ($result != '')
			{
				$result .= '.';
			}
			
			$fst = bin2hex(chr($piece & 0x0F));
			$snd = bin2hex(chr($piece >> 4));
			
			$result .= $fst[1];
			$result .= '.';
			$result .= $snd[1];
		}
		
		$is_icelandic = checkdnsrr($result.'.iceland.rix.is.', 'AAAA');
	}
	else
	{
		$ip_ = $ip;
		
		if ($is_ipv6 AND $is_ipv6_ipv4map)
		{
			$ip_ = inet_ntop(substr($inbinary, -4));
		}
		
		$parts = explode('.', $ip_, 4);
		$parts = array_reverse($parts);
		
		$is_icelandic = checkdnsrr(implode('.', $parts).'.iceland.rix.is.', 'A');
	}
	
	if ($mysqli->connect_errno == 0)
	{
		$int_is_icelandic = $is_icelandic ? 1 : 0;
		
		if ($stmt = $mysqli->prepare('INSERT INTO `checkforiceland_cache` (`ip`, `icelandic`) VALUES (?, ?)'))
		{
			$stmt->bind_param('si', $inbinary, $int_is_icelandic);
			$stmt->execute();
			$stmt->close();
		}
		
		$mysqli->close();
	}
	
	return $is_icelandic;
}
