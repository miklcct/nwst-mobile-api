<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Test\Parser;

use DateTimeImmutable;
use Miklcct\Nwst\Model\Eta;
use Miklcct\Nwst\Model\NoEta;
use Miklcct\Nwst\Model\Rdv;
use Miklcct\Nwst\Parser\EtaListParser;
use PHPUnit\Framework\TestCase;
use function file_get_contents;

class EtaListParserTest extends TestCase {
    public function testEta() : void {
        $result = (new EtaListParser())(file_get_contents(__DIR__ . '/EtaList'));
        $etas = [
            new Eta(
                '117'
                , 2
                , '001547C'
                , '117--Sham_Shui_Po_(Yen_Chow_Street)'
                , 'O'
                , 3690
                , new DateTimeImmutable('2020-04-18 13:48:59')
                , '#000000'
                , new DateTimeImmutable('2020-04-18 13:41:17')
                , TRUE
                , 'Citybus'
                , ''
                , 'Distance: 3.6km'
                , '#000000'
                , Rdv::parse('117-YCS-1')
                , 'E'
                , 'Sham Shui Po (Yen Chow Street)'
            ),
            new Eta(
                '117'
                , 2
                , '001547C'
                , '117--Sham_Shui_Po_(Yen_Chow_Street)'
                , 'O'
                , NULL
                , new DateTimeImmutable('2020-04-18 13:52:41')
                , '#707070'
                , new DateTimeImmutable('2020-04-18 13:41:17')
                , FALSE
                , 'Citybus'
                , ''
                , ''
                , '#000000'
                , Rdv::parse('117-YCS-1')
                , 'S'
                , 'Sham Shui Po (Yen Chow Street)'
            ),
            new Eta(
                '117'
                , 2
                , '001547C'
                , '117--Sham_Shui_Po_(Yen_Chow_Street)'
                , 'O'
                , NULL
                , new DateTimeImmutable('2020-04-18 14:36:45')
                , '#000000'
                , new DateTimeImmutable('2020-04-18 13:41:17')
                , FALSE
                , 'Citybus'
                , 'KMB cycle'
                , ''
                , '#000000'
                , Rdv::parse('117-YCS-1')
                , 'S'
                , 'Sham Shui Po (Yen Chow Street)'
            ),
            new Eta(
                '905'
                , 2
                , '001447B'
                , '905--Wan_Chai_North'
                , 'I'
                , NULL
                , new DateTimeImmutable('2020-04-18 14:02:01')
                , '#000000'
                , new DateTimeImmutable('2020-04-18 13:45:33')
                , FALSE
                , 'KMB'
                , ''
                , '14:02 Scheduled '
                , '#000000'
                , Rdv::parse('905-WCF-1')
                , 'E'
                , ' '
            ),
            new Eta(
                '970X'
                , 2
                , '001447A'
                , '970X--Aberdeen'
                , 'I'
                , 1269
                , new DateTimeImmutable('2020-04-18 13:52:48')
                , '#000000'
                , new DateTimeImmutable('2020-04-18 13:46:33')
                , TRUE
                , ''
                , ''
                , 'Distance: 1.2km, Slightly congested (proceeding to Camp Street )'
                , '#FF6306'
                , Rdv::parse('970X-ABE-1')
                , 'E'
                , 'Aberdeen'
            ),
        ];
        foreach ($etas as $eta) {
            self::assertContainsEquals($eta, $result);
        }
    }

    public function testNoEta() : void {
        self::assertEquals(
            new NoEta(
                'HTML'
                , /** @lang HTML */ <<< 'EOF'
Estimated Time of Arrival is currently not available. Please refer to timetable.<br><br>Service hours of this route please refer to <a href="appload://timetable_tab">timetable</a>
EOF
            )
            , (new EtaListParser())(file_get_contents(__DIR__ . '/NoEta'))
        );
    }
}