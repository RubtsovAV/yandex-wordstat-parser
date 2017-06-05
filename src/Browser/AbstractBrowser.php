<?php

namespace RubtsovAV\YandexWordstatParser\Browser;

use RubtsovAV\YandexWordstatParser\BrowserInterface;
use RubtsovAV\YandexWordstatParser\CaptchaInterface;
use RubtsovAV\YandexWordstatParser\ProxyInterface;
use RubtsovAV\YandexWordstatParser\Query;
use RubtsovAV\YandexWordstatParser\Result;
use RubtsovAV\YandexWordstatParser\YandexUser;
use RubtsovAV\YandexWordstatParser\Exception\BrowserException;

abstract class AbstractBrowser implements BrowserInterface
{
	const BASE_URI = 'https://wordstat.yandex.ru/#!/';

	/**
	 * @var callable
	 */
	protected $captchaSolver;

	/**
	 * @var string
	 */
	protected $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0';

	/**
	 * @var string
	 */
	protected $acceptLanguage = 'ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3';
	
	/**
	 * @var null|integer
	 */
	protected $timeout = 120;

	/**
	 * @var \RubtsovAV\YandexWordstatParser\ProxyInterface
	 */
	protected $proxy;

	/**
     * @param string $userAgent
     */
    public function setUserAgent(string $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $acceptLanguage
     */
    public function setAcceptLanguage(string $acceptLanguage)
    {
        $this->acceptLanguage = $acceptLanguage;
    }

    /**
     * @return string
     */
    public function getAcceptLanguage()
    {
        return $this->acceptLanguage;
    }

    /**
     * @param null|integer
     */
    public function setTimeout(int $timeout = null)
    {
    	$this->timeout = $timeout;
    }

    /**
     * @return null|integer
     */
    public function getTimeout()
    {
    	return $this->timeout;
    }

    /**
     * @param null|\RubtsovAV\YandexWordstatParser\ProxyInterface $proxy
     */
    public function setProxy(ProxyInterface $proxy = null)
    {
    	$this->proxy = $proxy;
    }

    /**
     * @return null|\RubtsovAV\YandexWordstatParser\ProxyInterface
     */
    public function getProxy()
    {
    	return $this->proxy;
    }

    /**
	 * Send the query
	 * 
	 * @param  \RubtsovAV\YandexWordstatParser\Query 	  $query
	 * @param  \RubtsovAV\YandexWordstatParser\YandexUser $yandexUser
	 * 
	 * @return \RubtsovAV\YandexWordstatParser\Result                      
	 */
	abstract public function send(Query $query, YandexUser $yandexUser);

	/**
	 * @param callable $captchaSolver
	 */
	public function setCaptchaSolver(callable $captchaSolver)
	{
		$this->captchaSolver = $captchaSolver;
	}

	/**
	 * @param callable|null
	 */
	public function getCaptchaSolver()
	{
		return $this->captchaSolver;
	}

	/**
	 * @param  \RubtsovAV\YandexWordstatParser\CaptchaInterface $сaptcha
	 *
	 * @throws \RubtsovAV\YandexWordstatParser\Exception\BrowserException
     *  When the captcha solver is not setted
	 * 
	 * @return bool True - captcha was successfully solved
	 */
	public function solveCaptcha(CaptchaInterface $сaptcha)
	{
		if (!$this->captchaSolver) {
			throw new BrowserException('The captcha solver is not setted');
		}
		$func = $this->captchaSolver;
		return $func($сaptcha);
	}
}