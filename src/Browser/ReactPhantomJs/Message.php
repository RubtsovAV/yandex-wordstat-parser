<?php

namespace RubtsovAV\YandexWordstatParser\Browser\ReactPhantomJs;

use RubtsovAV\YandexWordstatParser\Browser\ReactPhantomJs\Exception\InvalidMessageContentException;

class Message 
{
	const ENCODE_PREFIX = '[MESSAGE] ';
	const ENCODE_SUFFIX = PHP_EOL;

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var bool|string|int|float|array|null
	 */
	protected $content;

	public function __construct(string $type, $content = null)
	{
		$this->type = $type;

		if (!$this->isValidContent($content)) {
			throw new InvalidMessageContentException();
		}
		$this->content = $content;
	}

	public function isValidContent($content)
	{
		return in_array(gettype($content), [
			'boolean',
			'integer',
			'double',
			'string',
			'array',
			'NULL',
		]);
	}

	public function getType()
	{
		return $this->type;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function encode()
	{
		$data = [
			'type' => $this->getType(),
			'content' => $this->getContent(),
		];
		return static::ENCODE_PREFIX . json_encode($data) . static::ENCODE_SUFFIX;
	}

	public static function decode(string $line) 
	{
		$line = substr($line, strlen(static::ENCODE_PREFIX));
		$line = substr($line, 0, -strlen(static::ENCODE_SUFFIX));
		$data = json_decode($line, true);
		return new static($data['type'], $data['content']);
	}
}