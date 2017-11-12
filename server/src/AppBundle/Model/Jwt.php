<?php

namespace AppBundle\Model;


use AppBundle\Entity\User;

class Jwt
{
	/** @var User */
	private $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Возвращает JWT токен
	 * @return string
	 */
	public function generate(): string
	{
		$header = ['typ' => 'JWT', 'alg' => 'HS256'];
		$payload = ['login' => $this->user->getLogin()];

		$segments = [
			$this->urlsafeB64Encode(json_encode($header)),
			$this->urlsafeB64Encode(json_encode($payload)),
		];

		$signature = hash_hmac('sha256', implode('.', $segments), $this->user->getAuthKey(), true);
		$segments[] = $this->urlsafeB64Encode($signature);

		return implode('.', $segments);
	}

	private function urlSafeB64Encode($data)
	{
		$b64 = base64_encode($data);
		$b64 = str_replace(
			['+', '/', '\r', '\n', '='],
			['-', '_'],
			$b64
		);

		return $b64;
	}

	public function isValid($jwt):bool
	{
		return $jwt === $this->generate();
	}
}