<?php

namespace RubtsovAV\YandexWordstatParser\Proxy;

class Http extends AbstractProxy
{
	/**
	 * @return string
	 */
	public function getType()
	{
		return static::PROXY_TYPE_HTTP;
	}
}