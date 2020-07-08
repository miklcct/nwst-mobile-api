<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Parser;

use Miklcct\Nwst\Model\Rdv;
use Miklcct\Nwst\Model\RouteStop;
use function array_filter;
use function array_map;
use function explode;
use function Miklcct\Nwst\enable_error_exceptions;
use function Miklcct\Nwst\parse_float;
use function Miklcct\Nwst\parse_int;

class StopListParser implements ParserInterface {
    /**
     * Parse the return from the variant list API
     *
     * For the expected format, check the test sources
     *
     * @param string $file
     * @return RouteStop[]
     */
    public function __invoke(string $file) : array {
        return enable_error_exceptions(
            function () use ($file) {
                return array_map(
                    [$this, 'parseLine']
                    , array_filter(explode('<br>', $file))
                );
            }
        );
    }

    protected function parseLine(string $line) : RouteStop {
        $segments = explode('||', $line);
        return new RouteStop(
            // $segments[0] is always 'X0'
            Rdv::parse($segments[1])
            , parse_int($segments[2])
            , parse_int($segments[3])
            , $segments[4]
            , parse_float($segments[5])
            , parse_float($segments[6])
            , $segments[7]
            , $segments[8]
            , $segments[9]
            , parse_float($segments[10]) ?: NULL
            // $segments[11] is always 'Y'
            , parse_float($segments[12]) ?: NULL
            , parse_float($segments[13]) ?: NULL
            // $segments[14] is always 'N'
            // $segments[15] and $segments[16] are the same as $segments[5] and $segments[6]
            , $segments[17] ?: NULL
            , $segments[18]
            , $segments[19]
        );
    }

}