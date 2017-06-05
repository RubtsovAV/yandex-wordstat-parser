<?php

namespace RubtsovAV\YandexWordstatParser;

use RubtsovAV\YandexWordstatParser\CaptchaInterface;
use RubtsovAV\YandexWordstatParser\ProxyInterface;
use RubtsovAV\YandexWordstatParser\Query;
use RubtsovAV\YandexWordstatParser\Result;
use RubtsovAV\YandexWordstatParser\YandexUser;

interface BrowserInterface 
{
	/**
	 * Send the query
	 * 
	 * @param  \RubtsovAV\YandexWordstatParser\Query 	  $query
	 * @param  \RubtsovAV\YandexWordstatParser\YandexUser $yandexUser
	 * 
	 * @return \RubtsovAV\YandexWordstatParser\Result                      
	 */
	public function send(Query $query, YandexUser $yandexUser);

	/**
     * @param string $userAgent
     */
    public function setUserAgent(string $userAgent);

    /**
     * @return string
     */
    public function getUserAgent();

    /**
     * @param string $acceptLanguage
     */
    public function setAcceptLanguage(string $acceptLanguage);

    /**
     * @return string
     */
    public function getAcceptLanguage();

    /**
     * @param null|integer
     */
    public function setTimeout(int $timeout = null);

    /**
     * @return null|integer
     */
    public function getTimeout();

    /**
     * @param null|\RubtsovAV\YandexWordstatParser\ProxyInterface $proxy
     */
    public function setProxy(ProxyInterface $proxy = null);

    /**
     * @return null|\RubtsovAV\YandexWordstatParser\ProxyInterface
     */
    public function getProxy();

    /**
     * @param callable $captchaSolver
     */
    public function setCaptchaSolver(callable $captchaSolver);

    /**
     * @return callabl|null
     */
    public function getCaptchaSolver();

   /**
     * @param  \RubtsovAV\YandexWordstatParser\CaptchaInterface $сaptcha
     *
     * @throws \RubtsovAV\YandexWordstatParser\Exception\BrowserException
     *  When the captcha solver is not setted
     * 
     * @return bool True - captcha was successfully solved
     */
    public function solveCaptcha(CaptchaInterface $сaptcha);
}