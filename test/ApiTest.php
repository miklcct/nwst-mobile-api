<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Test;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriNormalizer;
use Miklcct\Nwst\Api;
use Miklcct\Nwst\ApiException;
use Miklcct\Nwst\Parser\RouteListParser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
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

    private static function compareUri(UriInterface $expected_uri) : callable {
        return static function (UriInterface $actual_uri) use ($expected_uri) : bool {
            return UriNormalizer::isEquivalent($expected_uri, $actual_uri);
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
                            , ['appid' => self::APPID, 'syscode5' => self::SYSCODE5, 'l' => self::LANGUAGE]
                        )
                    )
                )
            )
            ->willReturn($result);
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