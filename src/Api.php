<?php
declare(strict_types=1);

namespace Miklcct\Nwst;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Uri;
use Miklcct\Nwst\Parser\RouteListParser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Throwable;
use function array_rand;
use function ltrim;

class Api {
    public const TRADITIONAL_CHINESE = 0;
    public const ENGLISH = 1;
    public const SIMPLIFIED_CHINESE = 2;

    public const SERVERS = [
        'https://mobile.nwstbus.com.hk/api6/',
        'https://mobile01.nwstbus.com.hk/api6/',
        'https://mobile02.nwstbus.com.hk/api6/',
        'https://mobile03.nwstbus.com.hk/api6/',
        'https://mobile04.nwstbus.com.hk/api6/',
        'https://mobile05.nwstbus.com.hk/api6/',
    ];

    public function __construct(
        ClientInterface $client
        , string $appid
        , string $syscode5
        , int $language = self::TRADITIONAL_CHINESE
        , ?string $baseUrl = NULL
    ) {
        $this->client = $client;
        $this->appid = $appid;
        $this->syscode5 = $syscode5;
        $this->language = $language;
        $this->baseUrl = $baseUrl;
    }

    public function getBaseUrl() : string {
        return $this->baseUrl ?? static::SERVERS[array_rand(static::SERVERS)];
    }

    /**
     * Get the route list
     *
     * @return PromiseInterface
     * @throws ApiException
     */
    public function getRouteList() : PromiseInterface {
        return $this->callApi($this->getUri('getroutelist2.php'))->then(
            static function (ResponseInterface $response) {
                try {
                    return (new RouteListParser())($response->getBody()->__toString());
                } catch (Throwable $e) {
                    throw new ApiException('Failed to parse route list.', ApiException::PARSE_ERROR, $e);
                }
            }
        );
    }

    public function getUri(string $endpoint, array $query = []) : UriInterface {
        $uri = new Uri($this->getBaseUrl());
        return Uri::withQueryValues(
            $uri->withPath($uri->getPath() . ltrim($endpoint, '/'))
            , $query + ['appid' => $this->appid, 'syscode5' => $this->syscode5, 'l' => $this->language]
        );
    }

    private function callApi(UriInterface $uri) : PromiseInterface {
        return $this->client->requestAsync('GET', $uri)->then(
            function (ResponseInterface $response) {
                if ($response->getBody()->getSize() === 0) {
                    throw new ApiException('The API returns an empty response.', ApiException::EMPTY_BODY);
                }
                return $response;
            }
            , function (Throwable $exception) {
                throw new ApiException('The API call failed', ApiException::HTTP_ERROR, $exception);
            }
        );
    }

    /**
     * @var string
     */
    private $appid;
    /**
     * @var string
     */
    private $syscode5;
    /**
     * @var int
     */
    private $language;
    /**
     * @var string|null
     */
    private $baseUrl;
    /**
     * @var ClientInterface
     */
    private $client;
}