<?php
/******************************************************************************
 * Author: Petr Suchy (xsuchy09) <suchy@wamos.cz> <https://www.wamos.cz>
 * Project: EET - Uctenkovka
 * Date: 8.1.19
 * Time: 16:09
 * Copyright: (c) Petr Suchy (xsuchy09) <suchy@wamos.cz> <http://www.wamos.cz>
 *****************************************************************************/

namespace xsuchy09\Uctenkovka\Exception;

use Exception;

/**
 * Class UctenkovkaException
 * @package xsuchy09\Uctenkovka\Exception
 */
class UctenkovkaException extends Exception
{
	const UNKNOWN_MODE = 1;
	const CURL_ERROR = 2;
	const CA_CERT_NOT_EXISTS = 3;
	const SSL_CERT_NOT_EXISTS = 4;
	const SSL_KEY_NOT_EXISTS = 5;
	const SSL_CERT_NOT_SET = 6;
}