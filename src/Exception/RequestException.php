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
	const PROPERTY_NOT_EXISTS = 1;
	const METHOD_NOT_EXISTS = 2;
	const FIK_NOT_VALID = 3;
	const BKP_NOT_VALID = 4;
	const DATETIME_IN_PREPARE = 5;
}