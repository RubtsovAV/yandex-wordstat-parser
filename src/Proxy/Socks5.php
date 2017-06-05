<?php

namespace RubtsovAV\YandexWordstatParser\Proxy;

class Socks5 extends AbstractProxy
{
	/**
	 * @return string
	 */
	public function getType()
	{
		return static::PROXY_TYPE_SOCKS5;
	}
}