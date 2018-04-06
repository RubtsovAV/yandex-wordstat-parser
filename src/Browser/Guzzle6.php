<?php

namespace RubtsovAV\YandexWordstatParser\Browser;

use RubtsovAV\YandexWordstatParser\Query;
use RubtsovAV\YandexWordstatParser\Result;
use RubtsovAV\YandexWordstatParser\YandexUser;
use RubtsovAV\YandexWordstatParser\Captcha\Image as CaptchaImage;
use RubtsovAV\YandexWordstatParser\Exception\WrongResponseException;
use RubtsovAV\YandexWordstatParser\Exception\BrowserException;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Exception\GuzzleException;
use Kurbits\JavaScript\NodeRunner;

class Guzzle6 extends AbstractBrowser
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct(bool $ignoreSslErrors = false)
    {
        // Ignoring an SSL error for the traffic sniffer
        $this->client = new Client([
            'verify' => !$ignoreSslErrors
        ]);
    }

    /**
     * Send query by the user yandex
     *
     * @param  \RubtsovAV\YandexWordstatParser\Query $query
     * @param  \RubtsovAV\YandexWordstatParser\YandexUser $yandexUser
     *
     * @return \RubtsovAV\YandexWordstatParser\Result
     */
    public function send(Query $query, YandexUser $yandexUser, $page = 'words')
    {
        $requestOptions = $this->createRequestOptions($query, $yandexUser, $page);

        try {
            while (true) {
                $response = $this->client->request(
                    'POST',
                    'https://wordstat.yandex.ru/stat/' . $page,
                    $requestOptions
                );

                $responseData = json_decode((string)$response->getBody(), true);

                if (isset($responseData['need_login'])) {
                    $this->login($yandexUser);
                    $requestOptions = $this->createRequestOptions($query, $yandexUser, $page);
                    continue;
                }

                if (isset($responseData['captcha'])) {
                    $captchaUri = 'http:' . $responseData['captcha']['url'];
                    $captchaKey = $responseData['captcha']['key'];

                    $captcha = new CaptchaImage($captchaUri);
                    if (!$this->solveCaptcha($captcha)) {
                        throw new BrowserException('solve captcha failed');
                    }

                    $requestOptions['form_params']['captcha_key'] = $captchaKey;
                    $requestOptions['form_params']['captcha_value'] = $captcha->getAnswer();
                    continue;
                }

                if (isset($responseData['data'])) {
                    return $this->createResult($responseData, $yandexUser, $page);
                }

                break;
            }
        } catch (GuzzleException $ex) {
            throw new BrowserException(
                $ex->getMessage(),
                $ex->getCode(),
                $ex
            );
        }

        throw new WrongResponseException(
            (string)$response->getBody(),
            'unknown response',
            $responseData->getStatusCode()
        );
    }

    protected function createRequestOptions(
        Query $query,
        YandexUser $yandexUser,
        $page = 'words'
    )
    {
        $requestOptions = $this->getBaseRequestOptions($yandexUser);

        $requestOptions['form_params'] = [
            'db' => '',
            'filter' => 'all',
            'map' => 'world',
            'page' => $query->getPageNumber(),
            'page_type' => $page,
            'period' => 'monthly',
            'regions' => '',
            'sort' => 'cnt',
            'type' => 'list',
            'words' => $query->getWords(),
        ];

        if ($query->getRegions()) {
            $regions = implode(',', $query->getRegions());
            $requestOptions['form_params']['regions'] = $regions;
        }

        $requestOptions['headers'] += [
            'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
            'X-Requested-With' => 'XMLHttpRequest',
            'Referer' => 'https://wordstat.yandex.ru/',
        ];

        return $requestOptions;
    }

    protected function getBaseRequestOptions(YandexUser $yandexUser)
    {
        $requestOptions = [];

        $requestOptions['headers'] = [
            'User-Agent' => $this->getUserAgent(),
        ];

        if ($storagePath = $yandexUser->getStoragePath()) {
            $storagePath .= '/guzzle6';
            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0777, true);
            }
            $filename = $storagePath . '/cookies.json';
            $requestOptions['cookies'] = new FileCookieJar($filename, true);
        }

        if ($proxy = $this->getProxy()) {
            $requestOptions['proxy'] = $proxy->toString();
        }

        if ($requestTimeout = $this->getRequestTimeout()) {
            $requestOptions['timeout'] = $requestTimeout;
            $requestOptions['connect_timeout'] = $requestTimeout;
        }

        return $requestOptions;
    }

    protected function login(YandexUser $yandexUser)
    {
        $requestOptions = $this->getBaseRequestOptions($yandexUser);

        $requestOptions['form_params'] = [
            'login' => $yandexUser->getLogin(),
            'passwd' => $yandexUser->getPassword(),
            'timestamp' => time(),
        ];

        $response = $this->client->request(
            'POST',
            'https://passport.yandex.ru/passport?mode=auth&from=&retpath=https%3A%2F%2Fwordstat.yandex.ru%2F&twoweeks=yes',
            $requestOptions
        );

        $responseBody = $response->getBody() . '';
        if (strpos($responseBody, 'control__input_name_history-answer')) {
            throw new BrowserException("the yandex user is banned");
        }
    }

    protected function createResult(array $data, $page = 'words')
    {
        $data = $this->decodeData($data);

        if ($page != 'words') {
            return $data;
        }

        $impressions = 0;
        $includingPhrases = [];
        $phrasesAssociations = [];
        $lastUpdate = 0;
        $nextPageExists = (bool)$data['hasNextPage'];

        foreach ($data['includingPhrases']['items'] as $phrase) {
            $includingPhrases[] = [
                'words' => $phrase['phrase'],
                'impressions' => (int)preg_replace('#[^0-9]#', '', $phrase['number']),
            ];
        }

        if (count($includingPhrases) > 0) {
            $impressions = $includingPhrases[0]['impressions'];
        }

        foreach ($data['phrasesAssociations']['items'] as $phrase) {
            $phrasesAssociations[] = [
                'words' => $phrase['phrase'],
                'impressions' => (int)preg_replace('#[^0-9]#', '', $phrase['number']),
            ];
        }

        preg_match(
            '#(?<day>\d+)\.(?<month>\d+)\.(?<year>\d+)$#',
            $data['lastUpdate'],
            $match
        );

        $sourceTz = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $lastUpdate = mktime(
            0, 0, 0,
            $match['month'],
            $match['day'],
            $match['year']
        );
        date_default_timezone_set($sourceTz);

        return new Result(
            $impressions,
            $includingPhrases,
            $phrasesAssociations,
            $lastUpdate,
            $nextPageExists
        );
    }

    protected function decodeData(array $data)
    {
        $nodejs = new NodeRunner();
        $nodejs->setSource(file_get_contents(__DIR__ . '/Guzzle6/decode.js'));
        try {
            $decoded = $nodejs->call('decode', $data, $this->getUserAgent());
        } catch (\Exception $ex) {
            throw new BrowserException('The result decode failed', null, $ex);
        }

        $decoded = @json_decode(urldecode($decoded), true);
        if (!$decoded) {
            throw new BrowserException('The result is an invalid json');
        }
        if (!isset($decoded['content'])) {
            return $decoded;
        }

        return $decoded['content'];
    }
}