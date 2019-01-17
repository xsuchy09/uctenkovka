<?php
/******************************************************************************
 * Author: Petr Suchy (xsuchy09) <suchy@wamos.cz> <https://www.wamos.cz>
 * Project: EET - Uctenkovka
 * Date: 8.1.19
 * Time: 15:29
 * Copyright: (c) Petr Suchy (xsuchy09) <suchy@wamos.cz> <http://www.wamos.cz>
 *****************************************************************************/

namespace xsuchy09\Uctenkovka;


use stdClass;
use xsuchy09\Uctenkovka\Exception\UctenkovkaException;

/**
 * Class Uctenkovka
 * @package xsuchy09\Uctenkovka
 */
class Uctenkovka
{
	const MODE_TESTING = 'testing';
	const MODE_PRODUCTION = 'production';

	const URL_TESTING = 'https://extapi.uctenkovka-test.cz/receipts';
	const URL_PRODUCTION = 'https://extapi.uctenkovka.cz/receipts';

	/**
	 * Production or testing mode. Default testing.
	 *
	 * Examples:
	 *  testing
	 *  production
	 *
	 * @var string
	 */
	protected $mode = 'testing';

	/**
	 * Goal url. Default testing.
	 *
	 * @var string
	 */
	protected $url = self::URL_TESTING;

	/**
	 * Path to file. Default cacert in certs.
	 *
	 * @var string
	 */
	protected $sslCA;

	/**
	 * Path to file.
	 *
	 * @var string
	 */
	protected $sslCert;

	/**
	 * Password for $sslCert.
	 *
	 * @var string
	 */
	protected $sslCertPassword;

	/**
	 * @var string
	 */
	protected $sslKey;

	/**
	 * @var string
	 */
	protected $sslKeyPassword;

	/**
	 * Response with receiptStatus and playerAssignmentStatus.
	 *
	 * @var Response
	 */
	protected $response;


	/**
	 * EET3rdPartyAPI constructor.
	 *
	 * @codeCoverageIgnore
	 *
	 * @param string|null $mode
	 *
	 * @throws UctenkovkaException
	 */
	public function __construct(?string $mode = null)
	{
		if ($mode !== null) {
			$this->setMode($mode);
		}
	}

	/**
	 * @return string
	 */
	public function getMode(): string
	{
		return $this->mode;
	}

	/**
	 * @param string $mode
	 *
	 * @return Uctenkovka
	 *
	 * @throws UctenkovkaException
	 */
	public function setMode(string $mode): Uctenkovka
	{
		$constantName = sprintf('URL_%s', mb_strtoupper($mode));
		$constant = sprintf('%s::%s', Uctenkovka::class, $constantName);
		if (false === defined($constant)) {
			throw new UctenkovkaException('Unknown mode. Use "production" or "testing".', UctenkovkaException::UNKNOWN_MODE);
		}
		$this->mode = $mode;
		$this->url = constant($constant);

		return $this;
	}

	/**
	 * Get CURL options.
	 *
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function getCurlOptions(Request $request): array
	{
		$options = [
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_HEADER => false,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => true,
			//CURLOPT_SSL_VERIFYSTATUS => true, //Curl error: 91 - No OCSP response received

			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_TIMEOUT => 10,

			CURLOPT_POSTFIELDS => $request->getJson(),

			CURLOPT_HTTPHEADER => [
				'Content-Type: application/json',
				'Content-Length: ' . mb_strlen($request->getJson())
			]
		];

		if ($this->getSslCA() !== null) {
			$options[CURLOPT_CAINFO] = $this->getSslCA();
		}
		if ($this->getSslCert() !== null) {
			//$options[CURLOPT_SSLCERTTYPE] = 'PEM';
			$options[CURLOPT_SSLCERT] = $this->getSslCert();
			if ($this->getSslCertPassword() !== null) {
				$options[CURLOPT_SSLCERTPASSWD] = $this->getSslCertPassword();
			}
		}
		if ($this->getSslKey() !== null) {
			$options[CURLOPT_SSLKEY] = $this->getSslKey();
			if ($this->getSslKeyPassword() !== null) {
				$options[CURLOPT_SSLKEYPASSWD] = $this->getSslKeyPassword();
			}
		}

		return $options;
	}


	/**
	 * @return string|null
	 */
	public function getSslCA(): ?string
	{
		return $this->sslCA;
	}

	/**
	 * @param string $sslCA
	 *
	 * @return Uctenkovka
	 * @throws UctenkovkaException
	 */
	public function setSslCA(string $sslCA): Uctenkovka
	{
		if (false === file_exists($sslCA)) {
			throw new UctenkovkaException('CA certificate does not exists.', UctenkovkaException::CA_CERT_NOT_EXISTS);
		}
		$this->sslCA = $sslCA;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getSslCert(): ?string
	{
		return $this->sslCert;
	}

	/**
	 * @param string $sslCert
	 *
	 * @return Uctenkovka
	 * @throws UctenkovkaException
	 */
	public function setSslCert(string $sslCert): Uctenkovka
	{
		if (false === file_exists($sslCert)) {
			throw new UctenkovkaException('SSL certificate does not exists.', UctenkovkaException::SSL_CERT_NOT_EXISTS);
		}
		$this->sslCert = $sslCert;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getSslCertPassword(): ?string
	{
		return $this->sslCertPassword;
	}

	/**
	 * @param string $sslCertPassword
	 *
	 * @return Uctenkovka
	 */
	public function setSslCertPassword(string $sslCertPassword): Uctenkovka
	{
		$this->sslCertPassword = $sslCertPassword;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getSslKey(): ?string
	{
		return $this->sslKey;
	}

	/**
	 * @param string $sslKey
	 *
	 * @return Uctenkovka
	 * @throws UctenkovkaException
	 */
	public function setSslKey(string $sslKey): Uctenkovka
	{
		if (false === file_exists($sslKey)) {
			throw new UctenkovkaException('SSL key does not exists.', UctenkovkaException::SSL_KEY_NOT_EXISTS);
		}
		$this->sslKey = $sslKey;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getSslKeyPassword(): ?string
	{
		return $this->sslKeyPassword;
	}

	/**
	 * @param string $sslKeyPassword
	 *
	 * @return Uctenkovka
	 */
	public function setSslKeyPassword(string $sslKeyPassword): Uctenkovka
	{
		$this->sslKeyPassword = $sslKeyPassword;
		return $this;
	}

	/**
	 * @return Response|null
	 */
	public function getResponse(): ?Response
	{
		return $this->response;
	}

	/**
	 * Send Request to Uctenkovka API. Return just bool. If you want more information just use getResponse method after send call.
	 *
	 * @param Request    $request
	 * @param array|null $userOptions
	 *
	 * @return bool
	 * @throws UctenkovkaException
	 * @throws Exception\ResponseException
	 */
	public function send(Request $request, ?array $userOptions = null): bool
	{
		if ($this->getSslCert() === null) {
			throw new UctenkovkaException('SSL Certificate is not set. Use method Uctenkovka::setSslCert first.', UctenkovkaException::SSL_CERT_NOT_SET);
		}

		$this->response = null; // unset response

		$ch = curl_init($this->url);
		$options = $this->getCurlOptions($request);
		if ($userOptions !== null) {
			$options = $userOptions + $options;
		}
		curl_setopt_array($ch, $options);
		$data = curl_exec($ch);

		if ($data === false) {
			throw new UctenkovkaException(sprintf('Curl error: %s - %s', curl_errno($ch), curl_error($ch)), UctenkovkaException::CURL_ERROR);
		}

		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$this->response = new Response($httpCode);
		$this->response->setRawResponse($data);

		return $this->response->isSuccess();
	}
}