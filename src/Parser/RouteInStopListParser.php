<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Parser;

use Miklcct\Nwst\Model\Rdv;
use Miklcct\Nwst\Model\Route;
use Miklcct\Nwst\Model\RouteInStop;
use Miklcct\Nwst\Model\StopInfo;
use function array_filter;
use function array_slice;
use function explode;
use function Miklcct\Nwst\parse_float;
use function Miklcct\Nwst\parse_int;

class RouteInStopListParser {
    /**
     * Parse the return from the route in stop list API
     *
     * For the expected format, check the test sources
     *
     * @param string $file
     * @return Route[]
     */
    public function __invoke(string $file) : StopInfo {
        $lines = array_filter(explode('|*|<br>', $file));
        $in_service = [];
        $not_in_service = [];
        $split = FALSE;
        $first_line_items = explode('||', $lines[0]);
        foreach (array_slice($lines, 1) as $line) {
            if (explode('||', $line)[0] === 'NN') {
                $split = TRUE;
            } else {
                $object = $this->parseLine($line);
                if ($split) {
                    $not_in_service[] = $object;
                } else {
                    $in_service[] = $object;
                }
            }
        }
        return new StopInfo($first_line_items[0], $first_line_items[1], $in_service, $not_in_service);
    }

    protected function parseLine(string $line) : RouteInStop {
        $segments = explode('||', $line);
        return new RouteInStop(
            $segments[0]
            , $segments[1]
            , $segments[2]
            , parse_float($segments[3])
            , parse_int($segments[4])
            , $segments[5]
            , $segments[6]
            , parse_int($segments[7])
            , parse_int($segments[8])
            , $segments[9]
            // $segments[10] is always empty
            , parse_int($segments[11])
            , $segments[12]
            , parse_int($segments[13])
            , Rdv::parse($segments[14])
            , parse_int($segments[15])
            , parse_int($segments[16])
            , $segments[17]
            // $segments[18] is always 'G'
            , parse_int($segments[19])
            // $segments[20] is always equal to $segments[13]
            // $segments[21] is always 0 for circular, empty for non-circular
            // $segments[22] is always 1 for circular, 0 for non-circular
            // $segments[23] is always '08:00|^|000000|^|M|^|N|^|N'
            // $segments[24] is always '|^|595959|^|M|^|N|^|N'
            // $segments[25] is always '|^|595959|^|2020-04-18 12:43:30|^|N|^| |^|000000|^| |^| |^|595959|^|, '
            // with the date/time replaced by the query time
        );
    }
}