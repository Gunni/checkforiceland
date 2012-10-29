<?php

/*
	checkforiceland - Version 3.0 (2012-10-29)

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

class checkforiceland
{
	private $ip;
	private $icelandic;

	public function __construct($ip = "")
	{
		if ($ip != "")
			$this->setIP($ip);
	}

	public function getIcelandic()
	{
		return $this->icelandic;
	}

	public function getIP()
	{
		return $this->ip;
	}

	public function setIP($ip)
	{
		$this->ip = $ip;

		$ip_inBinary = inet_pton($this->ip);

		$this->icelandic = false;

		$ip_isIPv6 = strpos($this->ip, ':') !== false;

		$ip_is_ipv6_ipv4map = true;

		if ($ip_isIPv6)
		{
			for ($i = 0; $i < 10; $i++)
			{
				if ($ip_inBinary[$i] != 0x00)
				{
					$ip_is_ipv6_ipv4map = false;
				}
			}

			if (ord($ip_inBinary[10]) != 0xff OR ord($ip_inBinary[11]) != 0xff)
			{
				$ip_is_ipv6_ipv4map = false;
			}
		}

		if ($ip_isIPv6 AND $ip_is_ipv6_ipv4map == false)
		{
			$result = '';

			for ($i = 15; $i >= 0; $i--)
			{
				$piece = ord($ip_inBinary[$i]);

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

			$this->icelandic = checkdnsrr($result.'.iceland.rix.is.', 'AAAA');
		}
		else
		{
			$ip_ = $ip;

			if ($ip_isIPv6 AND $ip_is_ipv6_ipv4map)
			{
				$ip_ = inet_ntop(substr($ip_inBinary, -4));
			}

			$parts1 = explode('.', $ip_, 4);
			$parts2 = array_reverse($parts1);

			$this->icelandic = checkdnsrr(implode('.', $parts2).'.iceland.rix.is.', 'A');
		}
	}
}
