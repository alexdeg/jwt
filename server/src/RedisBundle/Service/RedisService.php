<?php

namespace RedisBundle\Service;

use Predis\Client;

/**
 * Заготовка сервиса для работы с редисом
 * Class RedisService
 * @package RedisBundle\Service
 */
class RedisService
{
	/** @var Client  */
	private $redis;
	/** @var string Имя приложения. Используется как пространство имен при работе с данными в редисе */
	private $applicationName;

	/** Дефолтное время хранения данных */
	const EXPIRE_DEFAULT_TIME = 60 * 60 * 7;

	public function __construct(Client $redis, $applicationName)
	{
		$this->redis = $redis;
		$this->applicationName = $applicationName;
	}

	private function getKey($key)
	{
		return $this->applicationName . ":" . $key;
	}

	public function keys($pattern = '*')
	{
		return $this->redis->keys($this->getKey($pattern));
	}

	public function set($key, $value, $expire = false)
	{
		$expire = $expire ? $expire : self::EXPIRE_DEFAULT_TIME;

		$this->redis->set($this->getKey($key), $value);
		$this->redis->expire($this->getKey($key), $expire);
	}

	public function get($key)
	{
		return $this->redis->get($this->getKey($key));
	}
}