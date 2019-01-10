<?php
/******************************************************************************
 * Author: Petr Suchy (xsuchy09) <suchy@wamos.cz> <https://www.wamos.cz>
 * Project: Uctenkovka
 * Date: 9.1.19
 * Time: 16:12
 * Copyright: (c) Petr Suchy (xsuchy09) <suchy@wamos.cz> <http://www.wamos.cz>
 *****************************************************************************/

namespace xsuchy09\UctenkovkaTest;

use xsuchy09\Uctenkovka\Exception\ResponseException;
use xsuchy09\Uctenkovka\Response;

class ResponseTest extends \PHPUnit\Framework\TestCase
{

	const JSON_SUCCESS = '{
"receiptStatus": "NEW",
"playerAssignmentStatus": "BASIC_PLAYER_CREATED"
}';

	const JSON_FAILURE = '[
{
"field": "email",
"code": "string.blank",
"message": "Either e-mail or phone must be specified"
},
{
"field": "fik",
"code": "object.invalid",
"message": "Invalid FIK format"
}
]';


	/**
	 * @var Response
	 */
	protected $responseSuccess;

	/**
	 * @var Response
	 */
	protected $responseFailure;

	public function setUp()
	{
		$this->responseSuccess = new Response(201);
		$this->responseFailure = new Response(400);

		parent::setUp();
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Response::getHttpCode
	 */
	public function testGetHttpCode()
	{
		$this->assertEquals(201, $this->responseSuccess->getHttpCode());
		$this->assertEquals(400, $this->responseFailure->getHttpCode());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Response::isSuccess
	 */
	public function testIsSuccess()
	{
		$this->assertEquals(true, $this->responseSuccess->isSuccess());
		$this->assertEquals(false, $this->responseFailure->isSuccess());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Response
	 * @covers \xsuchy09\Uctenkovka\Response::setHttpCode
	 */
	public function testSetHttpCode()
	{
		$this->expectException(ResponseException::class);
		$this->expectExceptionCode(ResponseException::UNEXPECTED_RESPONSE);

		new Response(301);
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Response::setRawResponse
	 * @covers \xsuchy09\Uctenkovka\Response::getRawResponse
	 * @covers \xsuchy09\Uctenkovka\Response::setReceiptStatus
	 * @covers \xsuchy09\Uctenkovka\Response::getReceiptStatus
	 * @covers \xsuchy09\Uctenkovka\Response::setPlayerAssignmentStatus
	 * @covers \xsuchy09\Uctenkovka\Response::getPlayerAssignmentStatus
	 * @covers \xsuchy09\Uctenkovka\Response::setFailure
	 * @covers \xsuchy09\Uctenkovka\Response::getFailure
	 * @covers \xsuchy09\Uctenkovka\Response::getFailureMessages
	 */
	public function testSetRawResponse()
	{
		$this->responseSuccess->setRawResponse(self::JSON_SUCCESS);
		$this->assertEquals(self::JSON_SUCCESS, $this->responseSuccess->getRawResponse());
		$this->assertEquals(json_decode(self::JSON_SUCCESS)->receiptStatus, $this->responseSuccess->getReceiptStatus());
		$this->assertEquals(json_decode(self::JSON_SUCCESS)->playerAssignmentStatus, $this->responseSuccess->getPlayerAssignmentStatus());
		$this->assertNull($this->responseSuccess->getFailure());
		$this->assertNull($this->responseSuccess->getFailureMessages());

		$this->responseFailure->setRawResponse(self::JSON_FAILURE);
		$this->assertEquals(self::JSON_FAILURE, $this->responseFailure->getRawResponse());
		$this->assertNull($this->responseFailure->getReceiptStatus());
		$this->assertNull($this->responseFailure->getPlayerAssignmentStatus());
		$this->assertEquals(json_decode(self::JSON_FAILURE), $this->responseFailure->getFailure());
		$this->assertIsArray($this->responseFailure->getFailureMessages());
	}
}
