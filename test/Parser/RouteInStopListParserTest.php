<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Test\Parser;

use Miklcct\Nwst\Model\Rdv;
use Miklcct\Nwst\Model\RouteInStop;
use Miklcct\Nwst\Parser\RouteInStopListParser;
use PHPUnit\Framework\TestCase;
use function file_get_contents;

class RouteInStopListParserTest extends TestCase {
    public function test() : void {
        $result = (new RouteInStopListParser())(file_get_contents(__DIR__ . '/RouteInStopList'));
        self::assertSame('Sands Street, Belcher\'s Street', $result->name);
        self::assertSame('001003A', $result->standId);
        self::assertCount(4, $result->routesInService);
        self::assertCount(3, $result->routesNotInService);
        foreach (
            [
                new RouteInStop(
                    'CTB'
                    , '5X'
                    , 'Kennedy Town'
                    , 4.7
                    , 2
                    , '001003A'
                    , '5X--Kennedy_Town'
                    , 2
                    , 1
                    , 'Causeway Bay (Whitfield Road)'
                    , 10009
                    , 'I'
                    , 18
                    , Rdv::parse('5X-KET-2')
                    , 1
                    , 21
                    , 'Departure omit Ice House St'
                    , 2
                )
                , new RouteInStop(
                    'NWFB'
                    , '101'
                    , 'Kennedy Town'
                    , 4.8
                    , 2
                    , '001003B'
                    , '101--Kennedy_Town'
                    , 4
                    , 1
                    , 'Kwun Tong (Yue Man Square)'
                    , 10499
                    , 'I'
                    , 32
                    , Rdv::parse('101-KET-1')
                    , 1
                    , 35
                    , 'Normal Routeing'
                    , 1
                )
            ] as $item
        ) {
            self::assertContainsEquals($item, $result->routesInService, '');
        }
        self::assertContainsEquals(
            new RouteInStop(
                'CTB'
                , '47P'
                , 'Wong Chuk Hang (Nam Long Shan)'
                , 5.5
                , 1
                , '001003A'
                , '47P--Wong_Chuk_Hang'
                , 2
                , 2
                , 'Kennedy Town (Belcher Bay)'
                , 10258
                , 'O'
                , 3
                , Rdv::parse('47P-NLS-1')
                , 1
                , 32
                , 'Normal Routeing'
                , 1
            )
            , $result->routesNotInService
        );
    }
}