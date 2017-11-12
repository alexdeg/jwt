<?php

namespace AppBundle\Helper;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Класс для возврата ответа. Можно использовать как для апи, так и для взаимодействия между функциями
 * @package AppBundle\Entity
 */
class Response implements \JsonSerializable
{
	/** @var bool */
	private $error = false;
	private $errorMessage = null;
	private $warning = false;
	private $warningMessage = null;
	private $data = [];
	private $code = 200;

	/**
	 * @return bool
	 */
	public function getWarning(): bool
	{
		return $this->warning;
	}

	/**
	 * @return null
	 */
	public function getWarningMessage()
	{
		return $this->warningMessage;
	}

	/**
	 * @param null $warningMessage
	 * @return Response
	 */
	public function setWarning($warningMessage = null)
	{
		$this->warning = true;
		$this->warningMessage = $warningMessage;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * @return null|string
	 */
	public function getMessage()
	{
		return $this->errorMessage;
	}

	/**
	 * @param null $errorMessage
	 * @return Response
	 */
	public function setError($errorMessage, $code = 400)
	{
		$this->error = true;
		$this->errorMessage = $errorMessage;
		$this->setCode($code);

		return $this;
	}

	/**
	 * @param $field
	 * @param $value
	 * @return self
	 */
	public function addData($field, $value)
	{
		$this->data[$field] = $value;

		return $this;
	}

	/**
	 * @param $key
	 * @return mixed|null
	 */
	public function getValue($key)
	{
		if (isset($this->data[$key])) {
			return $this->data[$key];
		}
		return null;
	}

	/**
	 * @return int
	 */
	public function getCode(): int
	{
		return $this->code;
	}

	/**
	 * @param int $code
	 * @return Response
	 */
	public function setCode(int $code)
	{
		$this->code = $code;

		return $this;
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	function jsonSerialize()
	{
		$r = [
			'error' => $this->getError(),
			'warning' => $this->getWarning(),
		];

		if ($this->getMessage() !== null) {
			$r['error_message'] = $this->getMessage();
		}
		// переезжаем на camelCase
		if ($this->getWarningMessage()) {
			$r['warningMessage'] = $this->getWarningMessage();
		}

		foreach ($this->data as $key => $value) {
			$r[$key] = $value;
		}

		return $r;
	}

	/**
	 * @return JsonResponse
	 */
	public function response() : JsonResponse
	{
		return new JsonResponse($this, $this->getCode());
	}
}