<?php
/******************************************************************************
 * Author: Petr Suchy (xsuchy09) <suchy@wamos.cz> <https://www.wamos.cz>
 * Project: Uctenkovka
 * Date: 9.1.19
 * Time: 10:21
 * Copyright: (c) Petr Suchy (xsuchy09) <suchy@wamos.cz> <http://www.wamos.cz>
 *****************************************************************************/

namespace xsuchy09\UctenkovkaTest;

use DateTime;
use xsuchy09\Uctenkovka\Exception\RequestException;
use xsuchy09\Uctenkovka\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{

	const REQUEST_DATA = [
		'email' => 'test@example.com',
		'phone' => '777777777',
		'basicConsent' => true,
		'fik' => 'B3A09B52-7C87-4014',
		'bkp' => '01234567-89abcdef',
		'date' => '2018-03-17',
		'time' => '16:41',
		'amount' => 4570,
		'simpleMode' => false
	];

	/**
	 * @var Request;
	 */
	protected $request;

	/**
	 * Setup.
	 * @throws RequestException
	 */
	protected function setUp()
	{
		$this->request = new Request();
		parent::setUp();
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Request::prepare
	 * @covers \xsuchy09\Uctenkovka\Request::getEmail
	 * @covers \xsuchy09\Uctenkovka\Request::setEmail
	 * @covers \xsuchy09\Uctenkovka\Request::getPhone
	 * @covers \xsuchy09\Uctenkovka\Request::setPhone
	 * @covers \xsuchy09\Uctenkovka\Request::isBasicConsent
	 * @covers \xsuchy09\Uctenkovka\Request::setBasicConsent
	 * @covers \xsuchy09\Uctenkovka\Request::getFik
	 * @covers \xsuchy09\Uctenkovka\Request::setFik
	 * @covers \xsuchy09\Uctenkovka\Request::getBkp
	 * @covers \xsuchy09\Uctenkovka\Request::setBkp
	 * @covers \xsuchy09\Uctenkovka\Request::getDate
	 * @covers \xsuchy09\Uctenkovka\Request::setDate
	 * @covers \xsuchy09\Uctenkovka\Request::getTime
	 * @covers \xsuchy09\Uctenkovka\Request::setTime
	 * @covers \xsuchy09\Uctenkovka\Request::getAmount
	 * @covers \xsuchy09\Uctenkovka\Request::setAmount
	 * @covers \xsuchy09\Uctenkovka\Request::isSimpleMode
	 * @covers \xsuchy09\Uctenkovka\Request::setSimpleMode
	 * @covers \xsuchy09\Uctenkovka\Request::getJson
	 *
	 * @throws \xsuchy09\Uctenkovka\Exception\RequestException
	 */
	public function testPrepare()
	{
		$this->request->prepare(self::REQUEST_DATA);
		$this->assertEquals(self::REQUEST_DATA['email'], $this->request->getEmail());
		$this->assertEquals(self::REQUEST_DATA['phone'], $this->request->getPhone());
		$this->assertEquals(self::REQUEST_DATA['basicConsent'], $this->request->isBasicConsent());
		$this->assertEquals(self::REQUEST_DATA['fik'], $this->request->getFik());
		$this->assertEquals(self::REQUEST_DATA['bkp'], $this->request->getBkp());
		$this->assertEquals(self::REQUEST_DATA['date'], $this->request->getDate());
		$this->assertEquals(self::REQUEST_DATA['time'], $this->request->getTime());
		$this->assertEquals(self::REQUEST_DATA['amount'], $this->request->getAmount());
		$this->assertEquals(self::REQUEST_DATA['simpleMode'], $this->request->isSimpleMode());
		$this->assertEquals(json_encode(self::REQUEST_DATA), $this->request->getJson());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Request::prepare
	 *
	 * @throws RequestException
	 */
	public function testPrepareExceptionProperty()
	{
		$data = [
			'foo' => 'boo'
		];
		$this->expectException(RequestException::class);
		$this->expectExceptionCode(RequestException::PROPERTY_NOT_EXISTS);
		$this->request->prepare($data);
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Request::setFik
	 *
	 * @throws RequestException
	 */
	public function testSetFikException()
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionCode(RequestException::FIK_NOT_VALID);
		$this->request->setFik('1234');
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Request::setBkp
	 *
	 * @throws RequestException
	 */
	public function testSetBkpException()
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionCode(RequestException::BKP_NOT_VALID);
		$this->request->setBkp('1234');
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Request::setDateTime
	 * @covers \xsuchy09\Uctenkovka\Request::setDate
	 * @covers \xsuchy09\Uctenkovka\Request::getDate
	 * @covers \xsuchy09\Uctenkovka\Request::setTime
	 * @covers \xsuchy09\Uctenkovka\Request::getTime
	 * @throws RequestException
	 * @throws \Exception
	 */
	public function testSetDateTime()
	{
		$dateTime = DateTime::createFromFormat('Y-m-d H:i', '2018-03-17 16:41');
		$this->request->setDateTime($dateTime);
		$this->assertEquals($dateTime->format('Y-m-d'), $this->request->getDate());
		$this->assertEquals($dateTime->format('H:i'), $this->request->getTime());

		$dateTime = new DateTime();
		$this->request->setDateTime(null);
		$this->assertEquals($dateTime->format('Y-m-d'), $this->request->getDate());
		$this->assertEquals($dateTime->format('H:i'), $this->request->getTime());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Request::setDate
	 *
	 * @throws RequestException
	 */
	public function testSetDateException()
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionCode(RequestException::DATE_NOT_VALID);
		$this->request->setDate('foo');
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Request::setTime
	 *
	 * @throws RequestException
	 */
	public function testSetTimeException()
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionCode(RequestException::TIME_NOT_VALID);
		$this->request->setTime('foo');
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Request::setTime
	 * @covers \xsuchy09\Uctenkovka\Request::getTime
	 * @throws RequestException
	 */
	public function testSetTime()
	{
		$time = '16:41';
		$this->request->setTime($time);
		$this->assertEquals($time, $this->request->getTime());

		$time = '16:41:58';
		$this->request->setTime($time);
		$this->assertEquals($time, $this->request->getTime());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Request::reset
	 *
	 * @throws RequestException
	 */
	public function testReset()
	{
		$this->request->prepare(self::REQUEST_DATA);
		$this->request->reset();

		$request2 = new Request();
		$this->assertEquals($request2, $this->request);
	}
}
