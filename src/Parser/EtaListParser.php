<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Parser;

use DateTimeImmutable;
use Miklcct\Nwst\Model\Eta;
use Miklcct\Nwst\Model\NoEta;
use Miklcct\Nwst\Model\Rdv;
use function array_filter;
use function array_map;
use function explode;
use function Miklcct\Nwst\nullif;
use function Miklcct\Nwst\parse_int;
use function Miklcct\Nwst\parse_yes_no;

class EtaListParser {
    /**
     * Parse the return from the ETA API
     *
     * For the expected format, check the test sources
     *
     * @param string $file
     * @return Eta[]|NoEta
     */
    public function __invoke(string $file) {
        $lines = array_filter(explode('|**|<br>', $file));
        $first_line = explode('||', $lines[0]);
        if ($first_line[0] !== '') {
            $subsegments = explode('|##|', $first_line[0]);
            return new NoEta($subsegments[1], $first_line[1]);
        }
        return array_map([$this, 'parseLine'], $lines);
    }

    protected function parseLine(string $line) : Eta {
        $subsegments = array_map(
            static function (string $segment) : array {
                return explode('||', $segment);
            }, explode('|*|', $line)
        );
        // FIXME: handle bus broken down
        // reference: https://www.hkitalk.net/HKiTalk2/forum.php?mod=redirect&goto=findpost&ptid=1111660&pid=3940188
        return new Eta(
            $subsegments[0][1]
            // $subsegments[0][2] to $subsegments[0][3] is always empty
            , parse_int($subsegments[0][4])
            , $subsegments[0][5]
            , explode('###', $subsegments[0][6])[0]
            // $subsegments[0][7] to $subsegments[0][10] are always empty
            , $subsegments[0][11]
            // $subsegments[0][12] is always '0000-00-00 00:00:00'
            , nullif(nullif(parse_int($subsegments[0][13]), 0), 1) // it is 0 for Citybus scheduled, 1 for KMB
            // $subsegments[0][14] is always 'FFFFFF'
            // $subsegments[0][15] is always ' '
            // $subsegments[0][16] is always '00:00|^| |^|FFFFFF|^|L|^|N|^|N'
            // $subsegments[0][17] is always ' |^|FFFFFF|^|L|^|N|^|N'
            // $subsegments[0][18] is always ' |^|FFFFFF|^|L|^|N|^|N'
            // $subsegments[0][19] is in the form of ' |#####|14--Stanley_Fort_(Gate)_!_Ma_Hang###002400B
            , new DateTimeImmutable($subsegments[1][0])
            , $subsegments[1][1]
            , new DateTimeImmutable($subsegments[1][2])
            , parse_yes_no($subsegments[1][3])
            , $subsegments[1][4]
            // $subsegments[1][5] is always #000000
            , $subsegments[1][6]
            , $subsegments[2][0]
            , $subsegments[2][1]
            , Rdv::parse($subsegments[3][0])
            // $subsegments[4][0] always consisting of whitespaces only
            , $subsegments[5][0]
            , $subsegments[6][0]
        );
    }
}