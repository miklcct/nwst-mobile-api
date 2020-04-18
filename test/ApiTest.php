<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Test;

use Error;
use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriNormalizer;
use LogicException;
use Miklcct\Nwst\Api;
use Miklcct\Nwst\ApiException;
use Miklcct\Nwst\Model\Rdv;
use Miklcct\Nwst\Model\VariantInfo;
use Miklcct\Nwst\Parser\EtaListParser;
use Miklcct\Nwst\Parser\RouteInStopListParser;
use Miklcct\Nwst\Parser\RouteListParser;
use Miklcct\Nwst\Parser\StopListParser;
use Miklcct\Nwst\Parser\VariantListParser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use RuntimeException;
use function file_get_contents;

class ApiTest extends TestCase {
    public const APPID = '4c-6e11td5qt-3e';
    public const SYSCODE5 = '1586791099381a580eb5fa43382d21e63330c76a36da989353409';
    public const LANGUAGE = Api::ENGLISH;
    const BASE_URL = 'https://mobile.nwstbus.com.hk/api6_test/';

    public function setUp() : void {
        parent::setUp();
        $this->client = $this->createMock(ClientInterface::class);
        $this->iut = new Api(
            $this->client
            , self::APPID
            , self::SYSCODE5
            , self::LANGUAGE
            , self::BASE_URL
        );
    }

    public function testGetBaseUrl() : void {
        $this->assertSame(self::BASE_URL, $this->iut->getBaseUrl());
    }

    public function testGetBaseUrlNotSpecifiedInConstructor() : void {
        for ($i = 0; $i < 10; ++$i) {
            $api = new Api($this->client, self::APPID, self::SYSCODE5, self::LANGUAGE);
            for ($j = 0; $j < 10; ++$j) {
                $this->assertContains($api->getBaseUrl(), Api::SERVERS);
            }
        }
    }

    public function testGetRouteList() : void {
        $content = file_get_contents(__DIR__ . '/Parser/RouteList');
        $this->setRouteListApi(new FulfilledPromise(new Response(200, [], $content)));
        $this->assertEquals(
            (new RouteListParser())($content)
            , $this->iut->getRouteList()->wait()
        );
    }

    public function testGetRouteListFail() : void {
        $original = new Exception();
        $this->setRouteListApi(new RejectedPromise($original));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::HTTP_ERROR);
        try {
            $this->iut->getRouteList()->wait();
        } catch (ApiException $e) {
            self::assertSame($original, $e->getPrevious());
            throw $e;
        }
    }

    public function testGetRouteListEmpty() : void {
        $this->setRouteListApi(new FulfilledPromise(new Response(200, [], '')));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::EMPTY_BODY);
        $this->iut->getRouteList()->wait();
    }

    public function testGetRouteListParseError() : void {
        $this->setRouteListApi(new FulfilledPromise(new Response(200, [], 'Wocde@#!#')));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::PARSE_ERROR);
        $this->iut->getRouteList()->wait();
    }

    public function testGetVariantList() : void {
        $route_id = '14--Stanley_Fort_(Gate)_!_Ma_Hang';
        $content = file_get_contents(__DIR__ . '/Parser/VariantList');
        $this->setVariantListApi($route_id, new FulfilledPromise(new Response(200, [], $content)));
        $this->assertEquals(
            (new VariantListParser())($content)
            , $this->iut->getVariantList($route_id)->wait()
        );
    }

    public function testGetVariantListFail() : void {
        $route_id = '14--Stanley_Fort_(Gate)_!_Ma_Hang';
        $original = new RuntimeException();
        $this->setVariantListApi($route_id, new RejectedPromise($original));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::HTTP_ERROR);
        try {
            $this->iut->getVariantList($route_id)->wait();
        } catch (ApiException $e) {
            self::assertSame($original, $e->getPrevious());
            throw $e;
        }
    }

    public function testGetVariantEmpty() : void {
        $route_id = '14--Stanley_Fort_(Gate)_!_Ma_Hang';
        $this->setVariantListApi($route_id, new FulfilledPromise(new Response(200, [], '')));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::EMPTY_BODY);
        $this->iut->getVariantList($route_id)->wait();
    }

    public function testGetVariantParseError() : void {
        $route_id = '14--Stanley_Fort_(Gate)_!_Ma_Hang';
        $this->setVariantListApi($route_id, new FulfilledPromise(new Response(200, [], '@#$(*(!!@')));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::PARSE_ERROR);
        $this->iut->getVariantList($route_id)->wait();
    }

    public function testGetStopList() : void {
        $content = file_get_contents(__DIR__ . '/Parser/StopList');
        $variant_info = new VariantInfo('CTB', new Rdv('118', 'TOS', 1), 1, 30, 10179, 'O');
        $this->setStopListApi($variant_info, new FulfilledPromise(new Response(200, [], $content)));
        $this->assertEquals(
            (new StopListParser())($content)
            , $this->iut->getStopList($variant_info)->wait()
        );
    }

    public function testGetStopListFail() : void {
        $original = new Error();
        $variant_info = new VariantInfo('CTB', new Rdv('118', 'TOS', 2), 1, 30, 10180, 'O');
        $this->setStopListApi($variant_info, new RejectedPromise($original));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::HTTP_ERROR);
        try {
            $this->iut->getStopList($variant_info)->wait();
        } catch (ApiException $e) {
            self::assertSame($original, $e->getPrevious());
            throw $e;
        }
    }

    public function testGetStopEmpty() : void {
        $variant_info = new VariantInfo('CTB', new Rdv('102', 'MEF', 1), 1, 15, 10262, 'O');
        $this->setStopListApi($variant_info, new FulfilledPromise(new Response(200, [], '')));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::EMPTY_BODY);
        $this->iut->getStopList($variant_info)->wait();
    }

    public function testGetStopParseError() : void {
        $variant_info = new VariantInfo('CTB', new Rdv('102', 'MEF', 1), 1, 15, 10262, 'O');
        $this->setStopListApi($variant_info, new FulfilledPromise(new Response(200, [], 'ASF$@K#L$J@KLJASDFAFd')));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::PARSE_ERROR);
        $this->iut->getStopList($variant_info)->wait();
    }

    public function testGetRouteInStopList() : void {
        $stop = 1003;
        $content = file_get_contents(__DIR__ . '/Parser/RouteInStopList');
        $this->setRouteInStopListApi($stop, new FulfilledPromise(new Response(200, [], $content)));
        $this->assertEquals(
            (new RouteInStopListParser())($content)
            , $this->iut->getRouteInStopList($stop)->wait()
        );
    }

    public function testGetRouteInStopListFail() : void {
        $stop = 978;
        $original = new LogicException();
        $this->setRouteInStopListApi($stop, new RejectedPromise($original));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::HTTP_ERROR);
        try {
            $this->iut->getRouteInStopList($stop)->wait();
        } catch (ApiException $e) {
            self::assertSame($original, $e->getPrevious());
            throw $e;
        }
    }

    public function testGetRouteInStopEmpty() : void {
        $stop = 9999;
        $this->setRouteInStopListApi($stop, new FulfilledPromise(new Response(200, [], '')));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::EMPTY_BODY);
        $this->iut->getRouteInStopList($stop)->wait();
    }

    public function testGetRouteInStopParseError() : void {
        $stop = 9999;
        $this->setRouteInStopListApi($stop, new FulfilledPromise(new Response(200, [], 'garbage')));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::PARSE_ERROR);
        $this->iut->getRouteInStopList($stop)->wait();
    }

    public function testGetEtaList() : void {
        $route_number = '118';
        $sequence = 13;
        $stop_id = 1231;
        $rdv = new Rdv('118', 'TOS', 1);
        $content = file_get_contents(__DIR__ . '/Parser/EtaList');
        $this->setEtaListApi($route_number, $sequence, $stop_id, $rdv, new FulfilledPromise(new Response(200, [], $content)));
        $this->assertEquals(
            (new EtaListParser())($content)
            , $this->iut->getEtaList($route_number, $sequence, $stop_id, $rdv)->wait()
        );
    }

    public function testGetEtaFail() : void {
        $route_number = '118';
        $sequence = 13;
        $stop_id = 1231;
        $rdv = new Rdv('118', 'TOS', 1);
        $original = new Exception();
        $this->setEtaListApi($route_number, $sequence, $stop_id, $rdv, new RejectedPromise($original));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::HTTP_ERROR);
        try {
            $this->iut->getEtaList($route_number, $sequence, $stop_id, $rdv)->wait();
        } catch (ApiException $exception) {
            self::assertSame($original, $exception->getPrevious());
            throw $exception;
        }
    }

    public function testGetEtaEmpty() : void {
        $route_number = '118';
        $sequence = 13;
        $stop_id = 1231;
        $rdv = new Rdv('118', 'TOS', 1);
        $this->setEtaListApi($route_number, $sequence, $stop_id, $rdv, new FulfilledPromise(new Response(200, [], '')));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::EMPTY_BODY);
        $this->iut->getEtaList($route_number, $sequence, $stop_id, $rdv)->wait();
    }

    public function testGetEtaParseError() : void {
        $route_number = '118';
        $sequence = 13;
        $stop_id = 1231;
        $rdv = new Rdv('118', 'TOS', 1);
        $this->setEtaListApi($route_number, $sequence, $stop_id, $rdv, new FulfilledPromise(new Response(200, [], 'FUCK YOU')));
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(ApiException::PARSE_ERROR);
        $this->iut->getEtaList($route_number, $sequence, $stop_id, $rdv)->wait();
    }

    private static function compareUri(UriInterface $expected_uri) : callable {
        return static function (UriInterface $actual_uri) use ($expected_uri) : bool {
            return UriNormalizer::isEquivalent(
                $expected_uri
                , $actual_uri
                , UriNormalizer::PRESERVING_NORMALIZATIONS | UriNormalizer::SORT_QUERY_PARAMETERS
            );
        };
    }

    private function setRouteListApi(PromiseInterface $result) : void {
        $this->client->expects(self::once())->method('requestAsync')
            ->with(
                'GET'
                , self::callback(
                    self::compareUri(
                        Uri::withQueryValues(
                            new Uri(self::BASE_URL . 'getroutelist2.php')
                            , $this->getBaseQueryParameters()
                        )
                    )
                )
            )
            ->willReturn($result);
    }

    private function setVariantListApi(string $route_id, PromiseInterface $result) : void {
        $this->client->expects(self::once())->method('requestAsync')
            ->with(
                'GET'
                , self::callback(
                    self::compareUri(
                        Uri::withQueryValues(
                            new Uri(self::BASE_URL . 'getvariantlist.php')
                            , ['id' => $route_id] + $this->getBaseQueryParameters()
                        )
                    )
                )
            )
            ->willReturn($result);
    }

    private function setStopListApi(VariantInfo $variant_info, PromiseInterface $result) : void {
        $this->client->expects(self::once())->method('requestAsync')
            ->with(
                'GET'
                , self::callback(
                    self::compareUri(
                        Uri::withQueryValues(
                            new Uri(self::BASE_URL . 'ppstoplist.php')
                            , ['info' => '0|*|' . $variant_info->toString('||')] + $this->getBaseQueryParameters()
                        )
                    )
                )
            )
            ->willReturn($result);
    }

    private function setRouteInStopListApi(int $stop_id, PromiseInterface $result) : void {
        $this->client->expects(self::once())->method('requestAsync')
            ->with(
                'GET'
                , self::callback(
                    self::compareUri(
                        Uri::withQueryValues(
                            new Uri(self::BASE_URL . 'getrouteinstop_eta_extra.php')
                            , ['id' => $stop_id] + $this->getBaseQueryParameters()
                        )
                    )
                )
            )
            ->willReturn($result);
    }

    private function setEtaListApi(
        string $route_number
        , int $sequence
        , int $stop_id
        , Rdv $rdv
        , PromiseInterface $result
    ) : void {
        $this->client->expects(self::once())->method('requestAsync')
            ->with(
                'GET'
                , self::callback(
                    self::compareUri(
                        Uri::withQueryValues(
                            new Uri(self::BASE_URL . 'getEta.php')
                            , [
                                'service_no' => $route_number,
                                'stopseq' => $sequence,
                                'stopid' => $stop_id,
                                'rdv' => $rdv->__toString(),
                                'mode' => '3eta',
                            ] + $this->getBaseQueryParameters()
                        )
                    )
                )
            )
            ->willReturn($result);
    }

    private function getBaseQueryParameters() : array {
        return [
            'appid' => self::APPID,
            'syscode5' => self::SYSCODE5,
            'l' => self::LANGUAGE,
        ];
    }

    /**
     * @var Api
     */
    private $iut;

    /**
     * @var ClientInterface|MockObject
     */
    private $client;
}