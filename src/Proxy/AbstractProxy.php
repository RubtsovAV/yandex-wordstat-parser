<?php

namespace RubtsovAV\YandexWordstatParser\Proxy;

use RubtsovAV\YandexWordstatParser\ProxyInterface;

abstract class AbstractProxy implements ProxyInterface
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var integer
     */
    protected $port;

    /**
     * @var string|null
     */
    protected $username;

    /**
     * @var string|null
     */
    protected $password;

    /**
     * @param string $host
     * @param int $port
     * @param string|null $username
     * @param string|null $password
     */
    public function __construct(
        string $host,
        int $port,
        string $username = null,
        string $password = null
    )
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return integer
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Converting to an array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => $this->getType(),
            'host' => $this->getHost(),
            'port' => $this->getPort(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
        ];
    }

    /**
     * Converting to a string
     *
     * @return string
     */
    public function toString()
    {
        $proxy = strtolower($this->getType()) . '://';
        $proxyHost = $this->getHost() . ':' . $this->getPort();

        if ($user = $this->getUsername()) {
            if ($password = $this->getPassword()) {
                $user .= ':' . $password;
            }
            $proxy .= $user . '@' . $proxyHost;
        } else {
            $proxy .= $proxyHost;
        }
        return $proxy;
    }
}