<?php
/******************************************************************************
 * Author: Petr Suchy (xsuchy09) <suchy@wamos.cz> <https://www.wamos.cz>
 * Project: EET - 3rdPartyAPI
 * Date: 8.1.19
 * Time: 16:09
 * Copyright: (c) Petr Suchy (xsuchy09) <suchy@wamos.cz> <http://www.wamos.cz>
 *****************************************************************************/

namespace xsuchy09\EET3rdPartyAPI\Exception;


use Exception;

class EET3rdPartyAPIException extends Exception
{
	const UNKNOWN_MODE = 1;
	const CA_CERT_NOT_EXISTS = 2;
	const SSL_CERT_NOT_EXISTS = 3;
	const UNKNOWN_RESPONSE = 4;
}