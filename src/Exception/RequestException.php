<?php
/******************************************************************************
 * Author: Petr Suchy (xsuchy09) <suchy@wamos.cz> <https://www.wamos.cz>
 * Project: EET - Uctenkovka
 * Date: 9.1.19
 * Time: 8:57
 * Copyright: (c) Petr Suchy (xsuchy09) <suchy@wamos.cz> <http://www.wamos.cz>
 *****************************************************************************/

namespace xsuchy09\Uctenkovka\Exception;


use Exception;

/**
 * Class RequestException
 * @package xsuchy09\Uctenkovka\Exception
 */
class RequestException extends Exception
{
	const GET_METHOD_DISABLED = 1;
	const SET_METHOD_DISABLED = 2;
}