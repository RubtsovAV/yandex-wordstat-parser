<?php

namespace RubtsovAV\YandexWordstatParser\Captcha;

use RubtsovAV\YandexWordstatParser\CaptchaInterface;

class Image implements CaptchaInterface
{
	/**
	 * @var string
	 */
	protected $imageUri;

	/**
	 * @var string
	 */
	protected $answer;

	/**
	 * @param string $imageUri
	 */
	public function __construct(string $imageUri)
	{
		$this->imageUri = $imageUri;
	}

	public function getCaptchaType()
	{
		return static::CAPTCHA_TYPE_IMAGE;
	}

	/**
	 * @return string
	 */
	public function getImageUri()
	{
		return $this->imageUri;
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