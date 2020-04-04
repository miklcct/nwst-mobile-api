<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Parser;

use Miklcct\Nwst\Model\Rdv;
use Miklcct\Nwst\Model\RouteStop;
use function array_filter;
use function array_map;
use function explode;
use function filter_var;
use const FILTER_VALIDATE_FLOAT;
use const FILTER_VALIDATE_INT;

class StopListParser {
    /**
     * Parse the return from the variant list API
     *
     * For the expected format, check the test sources
     *
     * @param string $file
     * @return RouteStop[]
     */
    public function __invoke(string $file) : array {
        return array_map(
            [$this, 'parseLine']
            , array_filter(explode('<br>', $file))
        );
    }

    protected function parseLine(string $line) : RouteStop {
        $segments = explode('||', $line);
        return new RouteStop(
            // $segments[0] is always 'X0'
            Rdv::parse($segments[1])
            , filter_var($segments[2], FILTER_VALIDATE_INT)
            , filter_var($segments[3], FILTER_VALIDATE_INT)
            , $segments[4]
            , filter_var($segments[5], FILTER_VALIDATE_FLOAT)
            , filter_var($segments[6], FILTER_VALIDATE_FLOAT)
            , $segments[7]
            , $segments[8]
            , $segments[9]
            , filter_var($segments[10], FILTER_VALIDATE_FLOAT)
            // $segments[11] is always 'Y'
            , filter_var($segments[12], FILTER_VALIDATE_FLOAT)
            , filter_var($segments[13], FILTER_VALIDATE_FLOAT)
            // $segments[14] is always 'N'
            // $segments[15] and $segments[16] are the same as $segments[5] and $segments[6]
            , $segments[16] ?: NULL
            , $segments[17]
        );
    }

}