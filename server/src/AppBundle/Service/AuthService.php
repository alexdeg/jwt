<?php

namespace AppBundle\Service;


use AppBundle\Entity\User;
use AppBundle\Model\Jwt;
use Doctrine\ORM\EntityManagerInterface;
use RedisBundle\Service\RedisService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AuthService
 * @package AppBundle\Service
 */
class AuthService
{
	/** @var EntityManagerInterface  */
	private $em;
	/** @var UserPasswordEncoderInterface */
	private $encoder;
	/** @var RedisService */
	private $redis;
	private $secret;

	public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, RedisService $redis, $secret)
	{
		$this->em = $em;
		$this->encoder = $encoder;
		$this->redis = $redis;
		$this->secret = $secret;
	}

	/**
	 * Регистрация в системе
	 * Пока отключена
	 * @param $login
	 * @param $password
	 */
	public function register($login, $password)
	{
		$user = new User();
		$user->setLogin($login);
		$encoded = $this->encoder->encodePassword($user, $password);

		$user->setPassword($encoded);

//		$this->em->persist($user);
//		$this->em->flush();
	}

	/**
	 * @param $login
	 * @param $pass
	 * @return string
	 * @throws \Exception
	 */
	public function login($login, $pass)
	{
		/** @var User $user */
		$user = $this->em->getRepository('AppBundle:User')->findOneBy(['login' => $login]);

		if (!$user) {
			throw new \Exception("Пользователь не найден");
		}

		if ($this->encoder->isPasswordValid($user, $pass)) {
			$authKey = $this->generateRandomString(30);
			$user->setAuthKey($authKey);

			$this->redis->set("authKey:" . $user->getLogin(), $authKey);

			return $this->JwtGenerate($user);
		}

		throw new \Exception("Неверный пароль");
	}

	public function JwtGenerate(User $user)
	{
		$jwt = new Jwt($user);
		return $jwt->generate();
	}

	private function generateRandomString($length) {
		return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
}