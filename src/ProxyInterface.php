<?php

namespace RubtsovAV\YandexWordstatParser;

interface ProxyInterface
{
	const PROXY_TYPE_HTTP = 'http';
	const PROXY_TYPE_SOCKS5 = 'socks5';

	/**
	 * @return string
	 */
	public function getType();

	/**
	 * @return string|null
	 */
	public function getHost();

	/**
	 * @return string|null
	 */
	public function getPort();

	/**
	 * @return string|null
	 */
	public function getUsername();

	/**
	 * @return string|null
	 */
	public function getPassword();

	/**
	 * Converting to an array
	 * 
	 * @return array
	 */
	public function toArray();
}