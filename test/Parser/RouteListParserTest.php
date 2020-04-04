<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Test\Parser;

use Miklcct\Nwst\Model\Route;
use Miklcct\Nwst\Parser\RouteListParser;
use PHPUnit\Framework\TestCase;
use function file_get_contents;

class RouteListParserTest extends TestCase {
    public function test() : void {
        $result = (new RouteListParser())(file_get_contents(__DIR__ . '/RouteList'));
        /** @var Route[] $routes */
        $routes = [
            new Route(
                'CTB'
                , '1'
                , 'FEV'
                , 2
                , 'Happy Valley (Upper)'
                , 'Felix Villas'
                , 2
                , '1--Felix_Villas'
                , 10229
                , 'I'
                , ''
                , NULL
                , 1
                , 32
                , FALSE
            )
            , new Route(
                'NWFB'
                , '4X'
                , 'WFS'
                , 2
                , 'Central (Exchange Square)'
                , 'Wah Fu (South)'
                , 4
                , '4X--Wah_Fu_(South)'
                , 10391
                , 'I'
                , 'Express, Monday to Saturday only'
                , NULL
                , 15
                , 48
                , TRUE
            )
            , new Route(
                'NWFB'
                , '110'
                , 'MOR'
                , 0
                , 'Shau Kei Wan'
                , 'Tsim Sha Tsui (Mody Road)'
                , 4
                , '110-Shau_Kei_Wan-Tsim_Sha_Tsui_(Mody_Road)'
                , 10898
                , 'O'
                , 'Circular'
                , 'KMB'
                , 1
                , 22
                , FALSE
            )
        ];
        foreach ($routes as $route) {
            self::assertContainsEquals($route, $result);
        }
    }
}