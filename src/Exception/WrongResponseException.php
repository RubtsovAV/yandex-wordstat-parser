<?php

namespace RubtsovAV\YandexWordstatParser\Exception;

class WrongResponseException extends BrowserException
{
	protected $responseContent;

	public function __construct(
		string $responseContent, 
		string $message = '', 
		int $code = 0, 
		\Exception $previous = null
	){
		$this->responseContent = $responseContent;
		parent::__construct($message, $code, $previous);
	}

	public function getResponseContent()
	{
		return $this->responseContent;
	}
}