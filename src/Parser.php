<?php

namespace RubtsovAV\YandexWordstatParser;
use RubtsovAV\YandexWordstatParser\Browser\Guzzle6 as Browser;

class Parser
{
    /**
     * The browser, which be parsing wordstat
     *
     * @var BrowserInterface
     */
    protected $browser;

    /**
     * The Yandex user for parsing wordstat
     *
     * @var YandexUser
     */
    protected $yandexUser;

    /**
     * @param BrowserInterface $browser The browser, which be parsing wordstat
     * @param YandexUser $yandexUser The Yandex user for parsing wordstat
     */
    public function __construct(BrowserInterface $browser, YandexUser $yandexUser)
    {
        $this->browser = $browser;
        $this->yandexUser = $yandexUser;
    }

    /**
     * @return BrowserInterface
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * @return YandexUser
     */
    public function getYandexUser()
    {
        return $this->yandexUser;
    }

    /**
     * @param  Query $query
     *
     * @return Result
     */
    public function query(Query $query, $page = 'words')
    {
        if ($this->browser instanceof Browser) {
            return $this->browser->send($query, $this->yandexUser, $page);
        }
        return $this->browser->send($query, $this->yandexUser);
    }
}