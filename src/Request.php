<?php
/******************************************************************************
 * Author: Petr Suchy (xsuchy09) <suchy@wamos.cz> <https://www.wamos.cz>
 * Project: EET - Uctenkovka
 * Date: 8.1.19
 * Time: 15:36
 * Copyright: (c) Petr Suchy (xsuchy09) <suchy@wamos.cz> <http://www.wamos.cz>
 *****************************************************************************/

namespace xsuchy09\Uctenkovka;


use DateTime;
use xsuchy09\Uctenkovka\Exception\RequestException;

/**
 * Class Request
 * @package xsuchy09\Uctenkovka
 */
class Request
{

	/**
	 * Player's e-mail address, used to identify the player in Účtenkovka. If specified, phone parameter is ignored.
	 *
	 * Examples:
	 *  jan.novak@example.org
	 *
	 * @var string
	 */
	protected $email = '';

	/**
	 * Player's phone number, used to identify the player in Účtenkovka only if email parameter is not specified.
	 * Only phone numbers without international prefix or with the Czech international prefix (+420) are accepted, whitespaces are ignored.
	 *
	 * Examples:
	 *  +420 777 123 456
	 *  777 123 456
	 *  777123456
	 *
	 * @var string
	 */
	protected $phone = '';

	/**
	 * Player's explicit consent to the processing of their personal data. If not true, the receipt registration is rejected.
	 *
	 * Examples:
	 *  true
	 *  false
	 *
	 * @var bool
	 */
	protected $basicConsent = true;

	/**
	 * FIK code. Only the first 3 parts (18 characters) of FIK are required, or it must be specified in full form.
	 * This regular expression applies for validation of specified values:
	 * ^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-4[0-9a-fA-F]{3}(-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}-[0-9a-fA-F]{2})?$
	 *
	 * Examples:
	 *  B3A09B52-7C87-4014
	 *  b3a09b52-7c87-4014
	 *  b3a09b52-7c87-4014-a496-4c7a53cf9125-03
	 *
	 * @var string
	 */
	protected $fik = '';

	/**
	 * BKP code. Only the first 2 parts (17 characters) of BKP are required, or it must be specified in full form.
	 * This regular expression applies for validation of specified values:
	 * ^[0-9a-fA-F]{8}-[0-9a-fA-F]{8}(-[0-9a-fA-F]{8}-[0-9a-fA-F]{8}-[0-9a-fA-F]{8})?$
	 *
	 * Examples:
	 *  01234567-89abcdef
	 *  01234567-89ABCDEF
	 *  01234567-89abcdef-01234567-89abcdef-01234567
	 *
	 * @var string
	 */
	protected $bkp = '';

	/**
	 * Can be reproduced from $dateTime - Date of sale in ISO-8601 format.
	 *
	 * Examples:
	 *  2018-03-17
	 *
	 * @var string
	 */
	protected $date = '';

	/**
	 * Can be reproduced from $dateTime - Time of sale in ISO-8601 format, seconds are optional.
	 *
	 * Examples:
	 *  16:41
	 *  08:13:55
	 *
	 * @var string
	 */
	protected $time = '';

	/**
	 * Total amount in hellers.
	 *
	 * Examples:
	 *  100 (for 1 CZK)
	 *  4570 (for 45.70 CZK)
	 *
	 * @var int
	 */
	protected $amount = 0;

	/**
	 * Sale regime, false for regular regime (default), true for simplified regime.
	 *
	 * Examples:
	 *  true
	 *  false
	 *
	 * @var bool
	 */
	protected $simpleMode = false;

	/**
	 * For auto reproducing of $date and $time.
	 *
	 * @var DateTime
	 */
	protected $dateTime;

	/**
	 * Request constructor.
	 */
	public function __construct(?array $data = null)
	{
		if ($data !== null) {
			foreach ($data as $key => $value) {
				if (true === property_exists(this, $key)) {
					$this->$key = $value;
				}
			}
		}
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 *
	 * @return Request
	 */
	public function setEmail(string $email): Request
	{
		$this->email = $email;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPhone(): string
	{
		return $this->phone;
	}

	/**
	 * @param string $phone
	 *
	 * @return Request
	 */
	public function setPhone(string $phone): Request
	{
		$this->phone = $phone;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isBasicConsent(): bool
	{
		return $this->basicConsent;
	}

	/**
	 * @param bool $basicConsent
	 *
	 * @return Request
	 */
	public function setBasicConsent(bool $basicConsent): Request
	{
		$this->basicConsent = $basicConsent;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFik(): string
	{
		return $this->fik;
	}

	/**
	 * @param string $fik
	 *
	 * @return Request
	 */
	public function setFik(string $fik): Request
	{
		if (preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-4[0-9a-fA-F]{3}(-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}-[0-9a-fA-F]{2})?$/', $fik))
		$this->fik = $fik;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBkp(): string
	{
		return $this->bkp;
	}

	/**
	 * @param string $bkp
	 *
	 * @return Request
	 */
	public function setBkp(string $bkp): Request
	{
		$this->bkp = $bkp;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDate(): string
	{
		return $this->date;
	}

	/**
	 * @param string $date
	 *
	 * @return Request
	 */
	public function setDate(string $date): Request
	{
		$this->date = $date;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTime(): string
	{
		return $this->time;
	}

	/**
	 * @param string $time
	 *
	 * @return Request
	 */
	public function setTime(string $time): Request
	{
		$this->time = $time;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAmount(): int
	{
		return $this->amount;
	}

	/**
	 * @param int $amount
	 *
	 * @return Request
	 */
	public function setAmount(int $amount): Request
	{
		$this->amount = $amount;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isSimpleMode(): bool
	{
		return $this->simpleMode;
	}

	/**
	 * @param bool $simpleMode
	 *
	 * @return Request
	 */
	public function setSimpleMode(bool $simpleMode): Request
	{
		$this->simpleMode = $simpleMode;
		return $this;
	}

	/**
	 * @return DateTime|null
	 */
	public function getDateTime(): ?DateTime
	{
		return $this->dateTime;
	}

	/**
	 * @param DateTime $dateTime
	 *
	 * @return Request
	 */
	public function setDateTime(DateTime $dateTime): Request
	{
		$this->dateTime = $dateTime;
		$this->setDate($this->dateTime->format('Y-m-d'));
		$this->setTime($this->dateTime->format('H:i'));
		return $this;
	}

	/**
	 * Get json for request.
	 *
	 * @return string
	 */
	public function getJson(): string
	{
		$data = [
			'email' => $this->getEmail(),
			'phone' => $this->getPhone(),
			'basicConsent' => $this->isBasicConsent(),
			'fik' => $this->getFik(),
			'bkp' => $this->getBkp(),
			'date' => $this->getDate(),
			'time' => $this->getTime(),
			'amount' => $this->getAmount(),
			'simpleMode' => $this->isSimpleMode()
		];
		return json_encode($data);
	}
}