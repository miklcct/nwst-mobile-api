<?php
declare(strict_types=1);

namespace Miklcct\Nwst;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Uri;
use Miklcct\Nwst\Model\Eta;
use Miklcct\Nwst\Model\NoEta;
use Miklcct\Nwst\Model\Rdv;
use Miklcct\Nwst\Model\Route;
use Miklcct\Nwst\Model\RouteStop;
use Miklcct\Nwst\Model\StopInfo;
use Miklcct\Nwst\Model\Variant;
use Miklcct\Nwst\Model\VariantInfo;
use Miklcct\Nwst\Parser\EtaListParser;
use Miklcct\Nwst\Parser\ParserInterface;
use Miklcct\Nwst\Parser\RouteInStopListParser;
use Miklcct\Nwst\Parser\RouteListParser;
use Miklcct\Nwst\Parser\StopListParser;
use Miklcct\Nwst\Parser\VariantListParser;
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
     * @return PromiseInterface|PromiseInterface<Route[]>
     */
    public function getRouteList() : PromiseInterface {
        return $this->callApi($this->getUri('getroutelist2.php'), new RouteListParser());
    }

    /**
     * Get the variant list
     *
     * @param string $route_id e.g. '1--Felix_Villas'
     * @return PromiseInterface|PromiseInterface<Variant[]>
     */
    public function getVariantList(string $route_id) : PromiseInterface {
        return $this->callApi($this->getUri('getvariantlist.php', ['id' => $route_id]), new VariantListParser());
    }

    /**
     * Get the stop list of a variant
     *
     * @param VariantInfo $variant_info
     * @return PromiseInterface|PromiseInterface<RouteStop[]>
     */
    public function getStopList(VariantInfo $variant_info) : PromiseInterface {
        return $this->callApi(
            $this->getUri('ppstoplist.php', ['info' => '0|*|' . $variant_info->toString('||')])
            ,
            new StopListParser()
        );
    }

    /**
     * Get the stop information with routes serving the stop
     *
     * @param int $stop_id
     * @return PromiseInterface|PromiseInterface<StopInfo>
     */
    public function getRouteInStopList(int $stop_id) : PromiseInterface {
        return $this->callApi(
            $this->getUri('getrouteinstop_eta_extra.php', ['id' => $stop_id])
            ,
            new RouteInStopListParser()
        );
    }

    /**
     * Get ETAs
     *
     * @param string $route_number The route number, e.g. "970X"
     * @param int $sequence The stop sequence inside the RDV
     * @param int $stop_id The stop id to be queried, must match the stop sequence above
     * @param Rdv $rdv
     * @param string $bound 'I' for inbound, 'O' for outbound, must match the RDV
     * @return PromiseInterface|PromiseInterface<Eta[]|NoEta>
     */
    public function getEtaList(string $route_number, int $sequence, int $stop_id, Rdv $rdv, string $bound) : PromiseInterface {
        return $this->callApi(
            $this->getUri(
                'getEta.php'
                , [
                    'mode' => '3eta',
                    'service_no' => $route_number,
                    'stopseq' => $sequence,
                    'stopid' => $stop_id,
                    'rdv' => $rdv->__toString(),
                    'bound' => $bound,
                ]
            )
            , new EtaListParser()
        );
    }

    public function getUri(string $endpoint, array $query = []) : UriInterface {
        $uri = new Uri($this->getBaseUrl());
        return Uri::withQueryValues(
            $uri->withPath($uri->getPath() . ltrim($endpoint, '/'))
            ,
            $query + ['appid' => $this->appid, 'syscode5' => $this->syscode5, 'l' => $this->language]
        );
    }

    private function callApi(UriInterface $uri, ParserInterface $parser) : PromiseInterface {
        return $this->client->requestAsync('GET', $uri)->then(
            function (ResponseInterface $response) use ($parser) {
                $body = $response->getBody();
                if ($body->getSize() === 0) {
                    throw new ApiException('The API returns an empty response.', ApiException::EMPTY_BODY);
                }
                try {
                    return $parser($body->__toString());
                } catch (Throwable $e) {
                    throw new ApiException('Failed to parse the API result.', ApiException::PARSE_ERROR, $e);
                }
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