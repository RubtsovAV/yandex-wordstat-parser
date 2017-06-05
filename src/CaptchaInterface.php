<?php

namespace RubtsovAV\YandexWordstatParser;

interface CaptchaInterface
{
	const CAPTCHA_TYPE_IMAGE = 'image';
	
	/**
	 * Returns the captcha type
	 * 
	 * @return string
	 */
	public function getCaptchaType();
}