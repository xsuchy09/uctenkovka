<?php
/******************************************************************************
 * Author: Petr Suchy (xsuchy09) <suchy@wamos.cz> <https://www.wamos.cz>
 * Project: EET - 3rdPartyAPI
 * Date: 8.1.19
 * Time: 15:29
 * Copyright: (c) Petr Suchy (xsuchy09) <suchy@wamos.cz> <http://www.wamos.cz>
 *****************************************************************************/

namespace xsuchy09\EET3rdPartyAPI;


use stdClass;
use xsuchy09\EET3rdPartyAPI\Exception\EET3rdPartyAPIException;

class EET3rdPartyAPI
{
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
	protected $sslCA = __DIR__ . '/certs/cacert-2018-12-05.pem';

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
	 * Raw response from API.
	 *
	 * @var stdClass|array|null
	 */
	protected $responseRaw;

	/**
	 * Response with receiptStatus and playerAssignmentStatus.
	 *
	 * @var Response|null
	 */
	protected $response;


	/**
	 * EET3rdPartyAPI constructor.
	 *
	 * @param string|null $mode
	 *
	 * @throws EET3rdPartyAPIException
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
	 * @return EET3rdPartyAPI
	 *
	 * @throws EET3rdPartyAPIException
	 */
	public function setMode(string $mode): EET3rdPartyAPI
	{
		$constantName = sprintf('URL_%s', mb_strtoupper($mode));
		$constant = sprintf('%s::%s', EET3rdPartyAPI::class, $constantName);
		if (false === defined($constant)) {
			throw new EET3rdPartyAPIException('Unknown mode. Use "production" or "testing".', EET3rdPartyAPIException::UNKNOWN_MODE);
		}
		$this->mode = $mode;
		$this->url = constant($constant);

		return $this;
	}

	/**
	 * Send Request to Uctenkovka API. Return just bool. If you want more information just use getResponse method after send call.
	 *
	 * @param Request    $request
	 * @param array|null $userOptions
	 *
	 * @return bool
	 * @throws EET3rdPartyAPIException
	 */
	public function send(Request $request, ?array $userOptions = null): bool
	{
		$this->response = null; // unset response

		$ch = curl_init($this->url);
		$options = $this->getCurlOptions($request);
		if ($userOptions !== null) {
			$options = $userOptions + $options;
		}
		curl_setopt_array($ch, $options);
		$data = curl_exec($ch);

		if ($data === false) {
			throw new EET3rdPartyAPIException('Curl error: %s - %s', curl_errno($ch), curl_error($ch));
		}

		$this->setResponseRaw(json_decode($data));

		$this->response = new Response();

		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($httpCode === 201) {
			$this->response = new Response();
			$this->response->setReceiptStatus($this->getResponseRaw()->receiptStatus)
				->setPlayerAssignmentStatus($this->getResponseRaw()->playerAssignmentStatus);

			return true;
		} else if ($httpCode === 400) {
			$this->response->setFailure($this->getResponseRaw());

			return false;
		} else {
			throw new EET3rdPartyAPIException('Unknown HTTP Code Response status.', EET3rdPartyAPIException::UNKNOWN_RESPONSE);
		}
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
			CURLOPT_SSL_VERIFYSTATUS => true,

			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_TIMEOUT => 10,

			CURLOPT_POSTFIELDS => json_encode($request),

			CURLOPT_CAINFO => $this->getSslCA(),

			CURLOPT_SSLCERT => $this->getSslCert(),
			CURLOPT_SSLCERTPASSWD => $this->getSslCertPassword(),
			CURLOPT_SSLCERTTYPE => 'PEM'
		];

		return $options;
	}


	/**
	 * @return string
	 */
	public function getSslCA(): string
	{
		return $this->sslCA;
	}

	/**
	 * @param string $sslCA
	 *
	 * @return EET3rdPartyAPI
	 * @throws EET3rdPartyAPIException
	 */
	public function setSslCA(string $sslCA): EET3rdPartyAPI
	{
		if (false === file_exists($sslCA)) {
			throw new EET3rdPartyAPIException('CA certificate does not exists.', EET3rdPartyAPIException::CA_CERT_NOT_EXISTS);
		}
		$this->sslCA = $sslCA;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSslCert(): string
	{
		return $this->sslCert;
	}

	/**
	 * @param string $sslCert
	 *
	 * @return EET3rdPartyAPI
	 * @throws EET3rdPartyAPIException
	 */
	public function setSslCert(string $sslCert): EET3rdPartyAPI
	{
		if (false === file_exists($sslCert)) {
			throw new EET3rdPartyAPIException('SSL certificate does not exists.', EET3rdPartyAPIException::SSL_CERT_NOT_EXISTS);
		}
		$this->sslCert = $sslCert;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSslCertPassword(): string
	{
		return $this->sslCertPassword;
	}

	/**
	 * @param string $sslCertPassword
	 *
	 * @return EET3rdPartyAPI
	 */
	public function setSslCertPassword(string $sslCertPassword): EET3rdPartyAPI
	{
		$this->sslCertPassword = $sslCertPassword;
		return $this;
	}

	/**
	 * @return array|stdClass|null
	 */
	public function getResponseRaw()
	{
		return $this->responseRaw;
	}

	/**
	 * @param array|stdClass|null $responseRaw
	 */
	public function setResponseRaw($responseRaw): void
	{
		$this->responseRaw = $responseRaw;
	}

	/**
	 * @return Response
	 */
	public function getResponse(): Response
	{
		return $this->response;
	}

	/**
	 * @param Response $response
	 */
	public function setResponse(Response $response): void
	{
		$this->response = $response;
	}
}