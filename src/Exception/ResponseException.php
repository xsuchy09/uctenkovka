<?php
/******************************************************************************
 * Author: Petr Suchy (xsuchy09) <suchy@wamos.cz> <https://www.wamos.cz>
 * Project: Uctenkovka
 * Date: 10.1.19
 * Time: 10:16
 * Copyright: (c) Petr Suchy (xsuchy09) <suchy@wamos.cz> <http://www.wamos.cz>
 *****************************************************************************/

namespace xsuchy09\Uctenkovka\Exception;


use Exception;

/**
 * Class ResponseException
 * @package xsuchy09\Uctenkovka\Exception
 */
class ResponseException extends Exception
{
	const UNEXPECTED_RESPONSE = 1;
}