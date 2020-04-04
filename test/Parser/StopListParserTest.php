<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Test\Parser;

use Miklcct\Nwst\Model\Rdv;
use Miklcct\Nwst\Model\RouteStop;
use Miklcct\Nwst\Parser\StopListParser;
use PHPUnit\Framework\TestCase;
use function file_get_contents;

class StopListParserTest extends TestCase {
    public function test() : void {
        $result = (new StopListParser())(file_get_contents(__DIR__ . '/StopList'));
        /** @var RouteStop[] $stops */
        $stops = [
            new RouteStop(
                Rdv::parse('118-TOS-1')
                , 5
                , 1227
                , '001227B'
                , 22.264980642091
                , 114.24170198053001
                , 'Lok Hin Terrace, Chai Wan Road'
                , 'Sham Shui Po (Tonkin Street)'
                , 'O'
                , 10.4
                , 5.2
                , 5.2
                , 'KMB'
                , '118--Cheung_Sha_Wan_(Sham_Mong_Road)'
            )
            , new RouteStop(
                Rdv::parse('118-TOS-1')
                , 30
                , 2594
                , '002594A'
                , 22.331135302091
                , 114.14903921053
                , 'Cheung Sha Wan (Sham Mong Road)'
                , 'Sham Shui Po (Tonkin Street)'
                , 'E'
                , NULL
                , NULL
                , NULL
                , 'KMB'
                , '118--Cheung_Sha_Wan_(Sham_Mong_Road)'
            )
        ];
        foreach ($stops as $variant) {
            self::assertContainsEquals($variant, $result);
        }
    }

}