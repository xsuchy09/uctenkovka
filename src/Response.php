<?php
/******************************************************************************
 * Author: Petr Suchy (xsuchy09) <suchy@wamos.cz> <https://www.wamos.cz>
 * Project: EET - Uctenkovka
 * Date: 8.1.19
 * Time: 17:08
 * Copyright: (c) Petr Suchy (xsuchy09) <suchy@wamos.cz> <http://www.wamos.cz>
 *****************************************************************************/

namespace xsuchy09\Uctenkovka;

use LogicException;
use xsuchy09\Uctenkovka\Exception\ResponseException;

/**
 * Class Response
 * @package xsuchy09\Uctenkovka
 */
class Response
{

	/**
	 * @var bool
	 */
	protected $success;

	/**
	 * HTTP Code of response from Uctenkovka.
	 *
	 * @var int
	 */
	protected $httpCode;

	/**
	 * Response from Uctenkovka as it came (JSON string).
	 *
	 * @var string
	 */
	protected $rawResponse;

	/**
	 * Status of newly registered receipt.
	 *
	 * Examples:
	 *  NEW - Receipt has been accepted but not yet successfully verified in ADIS. Verification will be repeated.
	 *  REJECTED - Receipt could not be verified in ADIS (probably due to incorrect receipt data) and will not be registered into any lottery draw.
	 *  VERIFIED - Receipt has been successfully verified in ADIS and will be registered into a lottery draw if it passes additional checks of lottery rules.
	 *
	 * @var string
	 */
	protected $receiptStatus;

	/**
	 * Status of receipt assignment to player account.
	 *
	 * Examples:
	 *  BASIC_PLAYER_CREATED - The receipt has been assigned to a newly created basic player account, i.e. the given e-mail or phone does not belong to any existing players and a new player account connected with the specified e-mail or phone had to be created.
	 *  ADDED_TO_BASIC_PLAYER - The receipt has been assigned to an existing basic player account registered to the specified e-mail or phone.
	 *  ADDED_TO_FULL_PLAYER - The receipt has been assigned to an existing fully registered player account with the specified e- mail or phone.
	 *
	 * @var string
	 */
	protected $playerAssignmentStatus;


	/**
	 * Failures when http code 400 (Bad Request) with descriptions of failures is returned.
	 *
	 * @var array
	 */
	protected $failure;

	/**
	 * Response constructor.
	 *
	 * @codeCoverageIgnore
	 *
	 * @param int $httpCode
	 *
	 * @throws ResponseException
	 */
	public function __construct(int $httpCode)
	{
		$this->setHttpCode($httpCode);
	}

	/**
	 * @return int|null
	 */
	public function getHttpCode(): ?int
	{
		return $this->httpCode;
	}

	/**
	 * @param int $httpCode
	 *
	 * @return Response
	 * @throws ResponseException
	 */
	protected function setHttpCode(int $httpCode)
	{
		$this->httpCode = $httpCode;

		if ($this->httpCode === 201) {
			$this->setSuccess(true);
		} else if ($this->httpCode === 400) {
			$this->setSuccess(false);
		} else {
			throw new ResponseException(sprintf('Unexpected HTTP Code Response status "%d".', $this->httpCode), ResponseException::UNEXPECTED_RESPONSE);
		}

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isSuccess(): bool
	{
		return $this->success;
	}

	/**
	 * @param bool $success
	 *
	 * @return Response
	 */
	protected function setSuccess(bool $success): Response
	{
		$this->success = $success;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getRawResponse(): ?string
	{
		return $this->rawResponse;
	}

	/**
	 * @param string $rawResponse
	 *
	 * @return Response
	 */
	public function setRawResponse(string $rawResponse): Response
	{
		$this->rawResponse = $rawResponse;

		$response = json_decode($this->rawResponse);

		if ($this->isSuccess() === true) {
			$this->setReceiptStatus($response->receiptStatus);
			$this->setPlayerAssignmentStatus($response->playerAssignmentStatus);
		} else if ($this->isSuccess() === false) {
			$this->setFailure($response);
		}

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getReceiptStatus(): ?string
	{
		return $this->receiptStatus;
	}

	/**
	 * @param string $receiptStatus
	 *
	 * @return Response
	 */
	protected function setReceiptStatus(string $receiptStatus): Response
	{
		$this->receiptStatus = $receiptStatus;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPlayerAssignmentStatus(): ?string
	{
		return $this->playerAssignmentStatus;
	}

	/**
	 * @param string $playerAssignmentStatus
	 *
	 * @return Response
	 */
	protected function setPlayerAssignmentStatus(string $playerAssignmentStatus): Response
	{
		$this->playerAssignmentStatus = $playerAssignmentStatus;
		return $this;
	}

	/**
	 * @return array|null
	 */
	public function getFailure(): ?array
	{
		return $this->failure;
	}

	/**
	 * @param array $failure
	 *
	 * @return Response
	 */
	protected function setFailure(array $failure): Response
	{
		$this->failure = $failure;
		return $this;
	}

	/**
	 * @return array|null
	 */
	public function getFailureMessages(): ?array
	{
		$messages = null;
		if (true === is_array($this->getFailure())) {
			$messages = [];
			foreach ($this->getFailure() as $failure) {
				$messages[] = sprintf('Error at field "%s" with code "%s" and message "%s".', $failure->field, $failure->code, $failure->message);
			}
		}
		return $messages;
	}
}