<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Test;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Miklcct\Nwst\Api;
use Miklcct\Nwst\ApiFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function json_encode;

class ApiFactoryTest extends TestCase {
    protected function setUp() : void {
        parent::setUp();
        $this->client = $this->createMock(ClientInterface::class);
    }

    public function testWithSecretProvided() : void {
        $iut = $this->getMockBuilder(ApiFactory::class)
            ->setConstructorArgs([$this->client])
            ->onlyMethods(['getSecret'])
            ->getMock();
        $iut->expects(self::never())->method('getSecret');
        $api = $iut(Api::SIMPLIFIED_CHINESE, '12345', '67890');
        self::assertInstanceOf(Api::class, $api);
    }

    public function testWithoutSecretProvided() : void {
        $iut = $this->getMockBuilder(ApiFactory::class)
            ->setConstructorArgs([$this->client])
            ->onlyMethods(['getSecret'])
            ->getMock();
        $iut->expects(self::once())->method('getSecret')->willReturn((object)['syscode5' => 'yyyyy', 'appid' => 'xxxxx']);
        $api = $iut(Api::SIMPLIFIED_CHINESE);
        self::assertInstanceOf(Api::class, $api);
    }

    public function testGetSecret() {
        $result = (object)['syscode' => 'ggggg', 'appid' => 'fffff'];
        $this->client->expects($this->once())->method('request')->with('GET', ApiFactory::SECRET_URL)
            ->willReturn(
                new Response(
                    200
                    , ['Content-Type' => 'application/json']
                    , json_encode($result)
                )
            );
        $this->assertEquals($result, (new ApiFactory($this->client))->getSecret());
    }

    /**
     * @var ClientInterface|MockObject
     */
    private $client;
}