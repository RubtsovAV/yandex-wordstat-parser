<?php

namespace RubtsovAV\YandexWordstatParser;

class Query 
{
	/**
	 * The requested words
	 * 
	 * @var string
	 */
	protected $words;

	/**
	 * The requested regions
	 * 
	 * @var null|int[]
	 */
	protected $regions;

	/**
	 * The requested page number
	 * 
	 * @var int
	 */
	protected $pageNumber;

	/**
	 * @param string $words Words to be requested
	 * @param int 	 $page  Number of requested page
	 */
	public function __construct(string $words, int $pageNumber = 1, array $regions = null)
	{
		$this->words = $words;
		$this->pageNumber = $pageNumber;
		$this->regions = $regions;
	}

	/**
	 * Returns the requested words
	 * 
	 * @return string
	 */
	public function getWords()
	{
		return $this->words;
	}

	/**
	 * Returns the requested regions
	 * 
	 * @return null|int[]
	 */
	public function getRegions()
	{
		return $this->regions;
	}

	/**
	 * Returns the requested page number
	 * 
	 * @return int
	 */
	public function getPageNumber()
	{
		return $this->pageNumber;
	}

	/**
	 * Converting to an array
	 * 
	 * @return array
	 */
	public function toArray()
	{
		return [
			'words' => $this->getWords(),
			'regions' => $this->getRegions(),
			'pageNumber' => $this->getPageNumber(),
		];
	}
}