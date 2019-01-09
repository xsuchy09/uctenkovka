<?php
/******************************************************************************
 * Author: Petr Suchy (xsuchy09) <suchy@wamos.cz> <https://www.wamos.cz>
 * Project: Uctenkovka
 * Date: 9.1.19
 * Time: 16:12
 * Copyright: (c) Petr Suchy (xsuchy09) <suchy@wamos.cz> <http://www.wamos.cz>
 *****************************************************************************/

namespace xsuchy09\UctenkovkaTest;

use xsuchy09\Uctenkovka\Response;

class ResponseTest extends \PHPUnit\Framework\TestCase
{

	/**
	 * @var Response
	 */
	protected $response;

	public function setUp()
	{
		$this->response = new Response();

		parent::setUp();
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Response::setReceiptStatus
	 * @covers \xsuchy09\Uctenkovka\Response::getReceiptStatus
	 */
	public function testReceiptStatus()
	{
		$statuses = [
			'NEW',
			'REJECTED',
			'VERIFIED'
		];
		$status = mt_rand(0, count($statuses) - 1);
		$this->response->setReceiptStatus($status);
		$this->assertEquals($status, $this->response->getReceiptStatus());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Response::setPlayerAssignmentStatus
	 * @covers \xsuchy09\Uctenkovka\Response::getPlayerAssignmentStatus
	 */
	public function testPlayerAssignmentStatus()
	{
		$statuses = [
			'BASIC_PLAYER_CREATED',
	        'ADDED_TO_BASIC_PLAYER',
			'ADDED_TO_FULL_PLAYER'
		];
		$status = mt_rand(0, count($statuses) - 1);
		$this->response->setPlayerAssignmentStatus($status);
		$this->assertEquals($status, $this->response->getPlayerAssignmentStatus());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Response::setFailure
	 * @covers \xsuchy09\Uctenkovka\Response::getFailure
	 */
	public function testFailure()
	{
		$failureJson = '[
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
		$failure = json_decode($failureJson);
		$this->response->setFailure($failure);
		$this->assertEquals($failure, $this->response->getFailure());
	}
}
