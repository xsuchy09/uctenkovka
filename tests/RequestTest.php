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

	/**
	 * @var Request;
	 */
	protected $request;

	/**
	 * Setup.
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
	 * @covers \xsuchy09\Uctenkovka\Request::getDateTime
	 * @covers \xsuchy09\Uctenkovka\Request::getJson
	 *
	 * @throws \xsuchy09\Uctenkovka\Exception\RequestException
	 */
	public function testPrepare()
	{
		$data = [
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
		$this->request->prepare($data);
		$this->assertEquals($data['email'], $this->request->getEmail());
		$this->assertEquals($data['phone'], $this->request->getPhone());
		$this->assertEquals($data['basicConsent'], $this->request->isBasicConsent());
		$this->assertEquals($data['fik'], $this->request->getFik());
		$this->assertEquals($data['bkp'], $this->request->getBkp());
		$this->assertEquals($data['date'], $this->request->getDate());
		$this->assertEquals($data['time'], $this->request->getTime());
		$this->assertEquals($data['amount'], $this->request->getAmount());
		$this->assertEquals($data['simpleMode'], $this->request->isSimpleMode());
		$this->assertEquals($data['date'], $this->request->getDateTime()->format('Y-m-d'));
		$this->assertEquals($data['time'], $this->request->getDateTime()->format('H:i'));
		$this->assertEquals(json_encode($data), $this->request->getJson());
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
	 * @covers \xsuchy09\Uctenkovka\Request::prepare
	 *
	 * @throws RequestException
	 */
	public function testPrepareExceptionDateTime()
	{
		$data = [
			'dateTime' => new DateTime()
		];
		$this->expectException(RequestException::class);
		$this->expectExceptionCode(RequestException::DATETIME_IN_PREPARE);
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
	 * @covers \xsuchy09\Uctenkovka\Request::getDateTime
	 * @covers \xsuchy09\Uctenkovka\Request::setTime
	 * @covers \xsuchy09\Uctenkovka\Request::getTime
	 */
	public function testSetDateTime()
	{
		$dateTime = new DateTime();
		$this->request->setDateTime($dateTime);
		$this->assertEquals($dateTime, $this->request->getDateTime());
		$this->assertEquals($dateTime->format('Y-m-d'), $this->request->getDate());
		$this->assertEquals($dateTime->format('H:i'), $this->request->getTime());

		$date = '2018-03-17';
		$this->request->setDate($date);
		$this->assertEquals($date, $this->request->getDate());
		$this->assertEquals($date, $this->request->getDateTime()->format('Y-m-d'));

		$date = '2018-03-18';
		$this->request->setDateTime(null);
		$this->request->setDate($date);
		$this->assertEquals($date, $this->request->getDate());
		$this->assertEquals($date, $this->request->getDateTime()->format('Y-m-d'));
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Request::getDateTime
	 * @covers \xsuchy09\Uctenkovka\Request::setTime
	 * @covers \xsuchy09\Uctenkovka\Request::getTime
	 */
	public function testSetTime()
	{
		$time = '16:41';
		$this->request->setTime($time);
		$this->assertEquals($time, $this->request->getTime());
		$this->assertEquals($time, $this->request->getDateTime()->format('H:i'));

		$time = '16:41:58';
		$this->request->setTime($time);
		$this->assertEquals($time, $this->request->getTime());
		$this->assertEquals($time, $this->request->getDateTime()->format('H:i:s'));

		$time = '16:41:59';
		$this->request->setDateTime(null);
		$this->request->setTime($time);
		$this->assertEquals($time, $this->request->getTime());
		$this->assertEquals($time, $this->request->getDateTime()->format('H:i:s'));
	}


	public function testReset()
	{
		$data = [
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
		$this->request->prepare($data);
		$this->request->reset();

		$request2 = new Request();
		$this->assertEquals($request2, $this->request);
	}
}
