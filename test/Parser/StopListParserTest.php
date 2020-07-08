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
                , 20
                , 1552
                , '001552D'
                , 22.317345382091
                , 114.16966019053001
                , 'Shantung Street, Nathan Road'
                , 'Sham Shui Po (Tonkin Street)'
                , 'O'
                , 6.1
                , 3.1
                , 3.1
                , 'KMB'
                , '118--Cheung_Sha_Wan_(Sham_Mong_Road)'
                , 'Grand Plaza, Langham Place, Mong Kok Station'
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
                , ''
            )
        ];
        foreach ($stops as $variant) {
            self::assertContainsEquals($variant, $result);
        }
    }

}