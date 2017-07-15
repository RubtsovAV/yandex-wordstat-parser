<?php

namespace RubtsovAV\YandexWordstatParser;

class Result
{
	/**
	 * Number of impressions per month for the requested words
	 * 
	 * @var int
	 */
	protected $impressions = 0;

	/**
	 * Other searches containing the requested words
	 * 
	 * @var array
	 */
	protected $includingPhrases = [];

	/**
	 * Requests, similar to the requested words
	 * 
	 * @var array
	 */
	protected $phrasesAssociations = [];

	/**
	 * Last updated of the wordstat page in UTC timestamp
	 * 
	 * @var int 
	 */
	protected $lastUpdate = 0;

	/**
	 * True when the next result page exists
	 * 
	 * @var bool
	 */
	protected $nextPageExists = false;

	/**
	 * @param array $content
	 */
	public function __construct(
		int $impressions = 0, 
		array $includingPhrases = [],
		array $phrasesAssociations = [],
		int $lastUpdate = 0,
		bool $nextPageExists = false
	){
		$this->impressions = $impressions;
		$this->includingPhrases = $includingPhrases;
		$this->phrasesAssociations = $phrasesAssociations;
		$this->lastUpdate = $lastUpdate;
		$this->nextPageExists = $nextPageExists;
	}

	/**
	 * Returns number of impressions per month for the requested words
	 * 
	 * @var int 
	 */
	public function getImpressions()
	{
		return $this->impressions;
	}

	/**
	 * Return other searches containing the requested words
	 * 
	 * @var array
	 */
	public function getIncludingPhrases()
	{
		return $this->includingPhrases;
	}

	/**
	 * Return requests, similar to the requested words
	 * 
	 * @var array
	 */
	public function getPhrasesAssociations()
	{
		return $this->phrasesAssociations;
	}

	/**
	 * Returns last updated of the wordstat page in UTC timestamp
	 * 
	 * @return integer 
	 */
	public function getLastUpdate()
	{
		return $this->lastUpdate;
	}

	/**
	 * Return TRUE when the next result page exists
	 * 
	 * @return bool 
	 */
	public function getNextPageExists()
	{
		return $this->nextPageExists;
	}

	/**
	 * Converting to an array
	 * 
	 * @return array
	 */
	public function toArray()
	{
		return [
			'impressions' => $this->getImpressions(),
			'includingPhrases' => $this->getIncludingPhrases(),
			'phrasesAssociations' => $this->getPhrasesAssociations(),
			'lastUpdate' => $this->getLastUpdate(),
			'nextPageExists' => $this->getNextPageExists(),
		];
	}

	/**
	 * Create instance from associative array
	 * Example: 
	 * [
	 *     'impressions' => 1000,
	 *     'includingPhrases' => [
	 *         [
	 *             'words' => example phrase', 
	 *             'impressions' => 101
	 *         ],
	 *         [
	 *             'words' => example phrase 2', 
	 *             'impressions' => 50
	 *         ],
	 *     ],
	 *     'phrasesAssociations' => [
	 *         [
	 *             'words' => example phrase', 
	 *             'impressions' => 101
	 *         ],
	 *         [
	 *             'words' => example phrase 2', 
	 *             'impressions' => 50
	 *         ],
	 *     ],
	 *     'lastUpdate' => 1496264400,
	 *     'nextPageExists' => true,
	 * ]
	 * 
	 * @param  array  $data
	 * 
	 * @return Result
	 */
	public static function fromArray(array $data) 
	{
		return new static(
			$data['impressions'] ?? 0,
			$data['includingPhrases'] ?? [],
			$data['phrasesAssociations'] ?? [],
			$data['lastUpdate'] ?? 0,
			$data['nextPageExists'] ?? false
		);
	}
}