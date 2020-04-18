<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Parser;

use Miklcct\Nwst\Model\Route;
use function explode;
use function Miklcct\Nwst\parse_int;
use function Miklcct\Nwst\parse_yes_no;

class RouteListParser implements ParserInterface {
    /**
     * Parse the return from the route list API
     *
     * For the expected format, check the test sources
     *
     * @param string $file
     * @return Route[]
     */
    public function __invoke(string $file) : array {
        return array_map(
            [$this, 'parseLine']
            , array_filter(explode('|*|<br>', $file))
        );
    }

    protected function parseLine(string $line) : Route {
        $segments = explode('||', $line);
        foreach ([3, 6, 8, 12, 13] as $index) {
            $segments[$index] = parse_int($segments[$index]);
        }
        if ($segments[11] === '') {
            $segments[11] = NULL;
        }
        $segments[14] = parse_yes_no($segments[14]);
        return new Route(...$segments);
    }

}