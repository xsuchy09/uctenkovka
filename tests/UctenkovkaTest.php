<?php
/******************************************************************************
 * Author: Petr Suchy (xsuchy09) <suchy@wamos.cz> <https://www.wamos.cz>
 * Project: Uctenkovka
 * Date: 10.1.19
 * Time: 9:47
 * Copyright: (c) Petr Suchy (xsuchy09) <suchy@wamos.cz> <http://www.wamos.cz>
 *****************************************************************************/

namespace xsuchy09\UctenkovkaTest;

use xsuchy09\Uctenkovka\Exception\UctenkovkaException;
use xsuchy09\Uctenkovka\Request;
use xsuchy09\Uctenkovka\Response;
use xsuchy09\Uctenkovka\Uctenkovka;

class UctenkovkaTest extends \PHPUnit\Framework\TestCase
{

	/**
	 * @var Uctenkovka
	 */
	protected $uctenkovka;

	/**
	 * @throws \xsuchy09\Uctenkovka\Exception\UctenkovkaException
	 */
	protected function setUp()
	{
		$this->uctenkovka = new Uctenkovka();

		parent::setUp();
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setMode
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getMode
	 *
	 * @throws UctenkovkaException
	 */
	public function testSetMode()
	{
		$mode = 'testing';
		$this->uctenkovka->setMode($mode);
		$this->assertEquals($mode, $this->uctenkovka->getMode());

		$mode = 'production';
		$this->uctenkovka->setMode($mode);
		$this->assertEquals($mode, $this->uctenkovka->getMode());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setMode
	 *
	 * @throws UctenkovkaException
	 */
	public function testSetModeException()
	{
		$mode = 'foo';

		$this->expectException(UctenkovkaException::class);
		$this->expectExceptionCode(UctenkovkaException::UNKNOWN_MODE);
		$this->uctenkovka->setMode($mode);
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslCA
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getSslCA
	 *
	 * @throws UctenkovkaException
	 */
	public function testSetSslCA()
	{
		$this->assertEquals(Uctenkovka::DEFAULT_SSL_CA, $this->uctenkovka->getSslCA());

		$path = __DIR__ . '/../src/certs/cacert-2018-12-05.pem';
		$this->uctenkovka->setSslCA($path);
		$this->assertEquals($path, $this->uctenkovka->getSslCA());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslCA
	 *
	 * @throws UctenkovkaException
	 */
	public function testSetSslCAException()
	{
		$this->expectException(UctenkovkaException::class);
		$this->expectExceptionCode(UctenkovkaException::CA_CERT_NOT_EXISTS);
		$this->uctenkovka->setSslCA('./' . uniqid(mt_rand()));
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getSslCert
	 */
	public function testGetSslCert()
	{
		$this->assertNull($this->uctenkovka->getSslCert());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslCert
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getSslCert
	 *
	 * @throws UctenkovkaException
	 */
	public function testSetSslCert()
	{
		$path = __DIR__ . '/../src/certs/cacert-2018-12-05.pem';
		$this->uctenkovka->setSslCert($path);
		$this->assertEquals($path, $this->uctenkovka->getSslCert());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslCert
	 *
	 * @throws UctenkovkaException
	 */
	public function testSetSslCertException()
	{
		$this->expectException(UctenkovkaException::class);
		$this->expectExceptionCode(UctenkovkaException::SSL_CERT_NOT_EXISTS);
		$this->uctenkovka->setSslCert('./' . uniqid(mt_rand()));
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslCertPassword
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getSslCertPassword
	 */
	public function testSetSslCertPassword()
	{
		$password = uniqid(mt_rand());
		$this->uctenkovka->setSslCertPassword($password);
		$this->assertEquals($password, $this->uctenkovka->getSslCertPassword());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getResponse
	 */
	public function testGetResponse()
	{
		$this->assertNull($this->uctenkovka->getResponse());

	}

	/*
	public function testSend()
	{
		$request = new Request(RequestTest::REQUEST_DATA);
		$this->uctenkovka->setSslCert(__DIR__ . '/../src/certs/test.cert');
		$this->uctenkovka->send($request);
	}
	*/
}
