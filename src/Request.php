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
use PHPUnit\Runner\Exception;
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
	 * Request constructor.
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct(?array $data = null)
	{
		if ($data !== null) {
			$this->prepare($data);
		}
	}

	/**
	 * Set more properties at once. Keys are properties names, values its values.
	 *
	 * @param array $data
	 *
	 * @return Request
	 * @throws RequestException
	 */
	public function prepare(array $data): Request
	{
		foreach ($data as $key => $value) {
			if (false === property_exists($this, $key)) {
				throw new RequestException(sprintf('Property "%s" not exists.', $key), RequestException::PROPERTY_NOT_EXISTS);
			}
			// @codeCoverageIgnoreStart
			$method = sprintf('set%s', ucfirst($key));
			if (false === method_exists($this, $method)) {
				throw new RequestException(sprintf('Method "%s" not exists.', $method), RequestException::METHOD_NOT_EXISTS);
			}
			// @codeCoverageIgnoreEnd
			$this->$method($value);
		}
		return $this;
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
	 * @throws RequestException
	 */
	public function setFik(string $fik): Request
	{
		if ($fik !== '' && preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-4[0-9a-fA-F]{3}(-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}-[0-9a-fA-F]{2})?$/', $fik) !== 1) {
			throw new RequestException('FIK is not valid.', RequestException::FIK_NOT_VALID);
		}
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
	 * @throws RequestException
	 */
	public function setBkp(string $bkp): Request
	{
		if ($bkp !== '' && preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{8}(-[0-9a-fA-F]{8}-[0-9a-fA-F]{8}-[0-9a-fA-F]{8})?$/', $bkp) !== 1) {
			throw new RequestException('BKP is not valid.', RequestException::BKP_NOT_VALID);
		}
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
	 * @param bool   $setDateTime If dateTime property should be set too or not.
	 *
	 * @return Request
	 * @throws RequestException
	 */
	public function setDate(string $date, bool $setDateTime = true): Request
	{
		$this->date = $date;

		if ($date !== '') {
			$tmpDate = DateTime::createFromFormat('Y-m-d', $this->date);
			if ($tmpDate === false) {
				throw new RequestException('Date is not in valid ISO-8601 format (Y-m-d).', RequestException::DATE_NOT_VALID);
			}
		}

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
	 * @param bool   $setDateTime If dateTime property should be set too or not.
	 *
	 * @return Request
	 */
	public function setTime(string $time, bool $setDateTime = true): Request
	{
		$this->time = $time;

		if ($time !== '') {
			if (mb_strlen($this->time) === 8) {
				$format = 'H:i:s';
			} else {
				$format = 'H:i';
			}
			$tmpDate = DateTime::createFromFormat($format, $this->time);
			if ($tmpDate === false) {
				throw new RequestException('Time is not in valid ISO-8601 format (H:i or H:i:s)', RequestException::TIME_NOT_VALID);
			}
		}

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
	 * @param DateTime|null $dateTime
	 *
	 * @return Request
	 * @throws RequestException
	 */
	public function setDateTime(?DateTime $dateTime = null): Request
	{
		if ($dateTime === null) {
			$dateTime = new DateTime();
		}
		$this->setDate($dateTime->format('Y-m-d'));
		$this->setTime($dateTime->format('H:i'));
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
		//$data = array_filter($data, function($value) { return $value !== ''; });
		return json_encode($data);
	}

	/**
	 * Reset data to default values.
	 *
	 * @return Request
	 * @throws RequestException
	 */
	public function reset(): Request
	{
		$data = [
			'email' => '',
			'phone' => '',
			'basicConsent' => true,
			'fik' => '',
			'bkp' => '',
			'date' => '',
			'time' => '',
			'amount' => 0,
			'simpleMode' => false
		];
		$this->prepare($data);

		return $this;
	}
}