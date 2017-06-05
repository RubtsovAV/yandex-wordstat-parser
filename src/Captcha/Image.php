<?php

namespace RubtsovAV\YandexWordstatParser\Captcha;

use RubtsovAV\YandexWordstatParser\CaptchaInterface;

class Image implements CaptchaInterface
{
	/**
	 * @var string
	 */
	protected $base64Image;

	/**
	 * @var string
	 */
	protected $answer;

	/**
	 * @param string $base64Image
	 */
	public function __construct(string $base64Image)
	{
		$this->base64Image = $base64Image;
	}

	public function getCaptchaType()
	{
		return static::CAPTCHA_TYPE_IMAGE;
	}

	/**
	 * @return string
	 */
	public function getBase64Image()
	{
		return $this->base64Image;
	}

	/**
	 * @return string
	 */
	public function getBinaryImage()
	{
		return base64_decode($this->base64Image);
	}

	/**
	 * @param string
	 */
	public function setAnswer(string $answer)
	{
		$this->answer = $answer;
	}

	/**
	 * @return string
	 */
	public function getAnswer()
	{
		return $this->answer;
	}
}