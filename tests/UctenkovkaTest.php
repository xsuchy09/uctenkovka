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
		$this->assertNull($this->uctenkovka->getSslCA());

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
		$path = __DIR__ . '/../src/certs/test_crt.pem';
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
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getSslKey
	 */
	public function testGetSslKey()
	{
		$this->assertNull($this->uctenkovka->getSslKey());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslKey
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getSslKey
	 *
	 * @throws UctenkovkaException
	 */
	public function testSetSslKey()
	{
		$path = __DIR__ . '/../src/certs/test_key.pem';
		$this->uctenkovka->setSslKey($path);
		$this->assertEquals($path, $this->uctenkovka->getSslKey());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslKey
	 *
	 * @throws UctenkovkaException
	 */
	public function testSetSslKeyException()
	{
		$this->expectException(UctenkovkaException::class);
		$this->expectExceptionCode(UctenkovkaException::SSL_KEY_NOT_EXISTS);
		$this->uctenkovka->setSslKey('./' . uniqid(mt_rand()));
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslKeyPassword
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getSslKeyPassword
	 */
	public function testSetSslKeyPassword()
	{
		$password = uniqid(mt_rand());
		$this->uctenkovka->setSslKeyPassword($password);
		$this->assertEquals($password, $this->uctenkovka->getSslKeyPassword());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getResponse
	 */
	public function testGetResponse()
	{
		$this->assertNull($this->uctenkovka->getResponse());

	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setMode
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslCert
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslKey
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::send
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getResponse
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getCurlOptions
	 *
	 * @throws UctenkovkaException
	 * @throws \xsuchy09\Uctenkovka\Exception\RequestException
	 * @throws \xsuchy09\Uctenkovka\Exception\ResponseException
	 */
	public function testSend()
	{
		$request = new Request(RequestTest::REQUEST_DATA);
		$request->setDateTime();

		$this->uctenkovka->setMode(Uctenkovka::MODE_TESTING);
		$this->uctenkovka->setSslCert(__DIR__ . '/../src/certs/test_crt.pem');
		$this->uctenkovka->setSslKey(__DIR__ . '/../src/certs/test_key.pem');
		$this->uctenkovka->send($request);

		$this->assertTrue($this->uctenkovka->getResponse()->isSuccess());
		$this->assertEquals(201, $this->uctenkovka->getResponse()->getHttpCode());
		$this->assertEquals('REJECTED', $this->uctenkovka->getResponse()->getReceiptStatus());
		$this->assertEquals('ADDED_TO_BASIC_PLAYER', $this->uctenkovka->getResponse()->getPlayerAssignmentStatus());
		$this->assertNull($this->uctenkovka->getResponse()->getFailure());
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::send
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getCurlOptions
	 *
	 * @throws UctenkovkaException
	 * @throws \xsuchy09\Uctenkovka\Exception\ResponseException
	 * @throws \xsuchy09\Uctenkovka\Exception\RequestException
	 */
	public function testSendException()
	{
		$request = new Request(RequestTest::REQUEST_DATA);
		$request->setDateTime();

		$this->expectException(UctenkovkaException::class);
		$this->expectExceptionCode(UctenkovkaException::SSL_CERT_NOT_SET);
		$this->uctenkovka->send($request);
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::send
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getCurlOptions
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslCA
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslCert
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslCertPassword
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslKey
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::setSslKeyPassword
	 *
	 * @throws UctenkovkaException
	 * @throws \xsuchy09\Uctenkovka\Exception\RequestException
	 * @throws \xsuchy09\Uctenkovka\Exception\ResponseException
	 */
	public function testSendUserOptions()
	{
		$request = new Request(RequestTest::REQUEST_DATA);
		$request->setDateTime();

		$userOptions = [
			CURLOPT_TIMEOUT => 15,
			CURLOPT_CONNECTTIMEOUT => 10
		];

		$this->uctenkovka->setMode(Uctenkovka::MODE_TESTING);
		$this->uctenkovka->setSslCA(__DIR__ . '/../src/certs/cacert-2018-12-05.pem');
		$this->uctenkovka->setSslCert(__DIR__ . '/../src/certs/test_crt.pem');
		$this->uctenkovka->setSslKey(__DIR__ . '/../src/certs/test_key.pem');
		$this->uctenkovka->setSslCertPassword(uniqid(mt_rand()));
		$this->uctenkovka->setSslKeyPassword(uniqid(mt_rand()));
		$this->uctenkovka->send($request, $userOptions);

		$this->assertFalse($this->uctenkovka->getResponse()->isSuccess());
		$this->assertEquals(400, $this->uctenkovka->getResponse()->getHttpCode());
		$this->assertIsArray($this->uctenkovka->getResponse()->getFailure());
		$this->assertEquals('object.duplicate', $this->uctenkovka->getResponse()->getFailure()[0]->code); // same receipt as higher
	}

	/**
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::send
	 * @covers \xsuchy09\Uctenkovka\Uctenkovka::getCurlOptions
	 *
	 * @throws UctenkovkaException
	 * @throws \xsuchy09\Uctenkovka\Exception\RequestException
	 * @throws \xsuchy09\Uctenkovka\Exception\ResponseException
	 */
	public function testSendExceptionCurl()
	{
		$request = new Request(RequestTest::REQUEST_DATA);
		$request->setDateTime();

		$userOptions = [
			CURLOPT_URL => uniqid(mt_rand())
		];

		$this->expectException(UctenkovkaException::class);
		$this->expectExceptionCode(UctenkovkaException::CURL_ERROR);

		$this->uctenkovka->setMode(Uctenkovka::MODE_TESTING);
		$this->uctenkovka->setSslCert(__DIR__ . '/../src/certs/test_crt.pem');
		$this->uctenkovka->setSslKey(__DIR__ . '/../src/certs/test_key.pem');
		$this->uctenkovka->send($request, $userOptions);
	}
}
