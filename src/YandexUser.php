<?php

namespace RubtsovAV\YandexWordstatParser;

class YandexUser
{
	/**
	 * The Yandex user login
	 * 
	 * @var string
	 */
	protected $login;

	/**
	 * The Yandex user password
	 * 
	 * @var string
	 */
	protected $password;

	/**
	 * The path storage, where browser will be save all user data: cookies, local storage, etc.
	 * 
	 * @var string
	 */
	protected $storagePath;

	/**
	 * @param string $login    The Yandex user login
	 * @param string $password The Yandex user password
	 * @param string|null $storagePath The storage path
	 */
	public function __construct(string $login, string $password, string $storagePath = null)
	{
		$this->login = $login;
		$this->password = $password;
		$this->storagePath = $storagePath;
	}

	/**
	 * Returns the Yandex user login
	 * 
	 * @return string
	 */
	public function getLogin()
	{
		return $this->login;
	}

	/**
	 * Returns the Yandex user password
	 * 
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Returns the storage path 
	 * 
	 * @return string|null
	 */
	public function getStoragePath()
	{
		return $this->storagePath;
	}

	/**
	 * Converting to an array
	 * 
	 * @return array
	 */
	public function toArray()
	{
		return [
			'login' => $this->getLogin(),
			'password' => $this->getPassword(),
			'storagePath' => $this->getStoragePath(),
		];
	}
}