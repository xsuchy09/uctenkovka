<?php
/******************************************************************************
 * Author: Petr Suchy (xsuchy09) <suchy@wamos.cz> <https://www.wamos.cz>
 * Project: EET - 3rdPartyAPI
 * Date: 8.1.19
 * Time: 17:08
 * Copyright: (c) Petr Suchy (xsuchy09) <suchy@wamos.cz> <http://www.wamos.cz>
 *****************************************************************************/

namespace xsuchy09\EET3rdPartyAPI;


class Response
{
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
	public $receiptStatus;

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
	public $playerAssignmentStatus;


	/**
	 * Failures when http code 400 (Bad Request) with descriptions of failures is returned.
	 *
	 * @var array
	 */
	public $failure;

	/**
	 * @return string
	 */
	public function getReceiptStatus(): string
	{
		return $this->receiptStatus;
	}

	/**
	 * @param string $receiptStatus
	 *
	 * @return Response
	 */
	public function setReceiptStatus(string $receiptStatus): Response
	{
		$this->receiptStatus = $receiptStatus;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPlayerAssignmentStatus(): string
	{
		return $this->playerAssignmentStatus;
	}

	/**
	 * @param string $playerAssignmentStatus
	 *
	 * @return Response
	 */
	public function setPlayerAssignmentStatus(string $playerAssignmentStatus): Response
	{
		$this->playerAssignmentStatus = $playerAssignmentStatus;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getFailure(): array
	{
		return $this->failure;
	}

	/**
	 * @param array $failure
	 *
	 * @return Response
	 */
	public function setFailure(array $failure): Response
	{
		$this->failure = $failure;
		return $this;
	}

}