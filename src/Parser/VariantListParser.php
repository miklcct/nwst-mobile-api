<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Parser;

use Miklcct\Nwst\Model\Rdv;
use Miklcct\Nwst\Model\Variant;
use Miklcct\Nwst\Model\VariantIdentifier;
use function array_filter;
use function array_map;
use function explode;

class VariantListParser {
    /**
     * Parse the return from the variant list API
     *
     * For the expected format, check the test sources
     *
     * @param string $file
     * @return Variant[]
     */
    public function __invoke(string $file) : array {
        return array_map(
            [$this, 'parseLine']
            , array_filter(explode('||<br>', $file))
        );
    }

    protected function parseLine(string $line) : Variant {
        $segments = explode('||', $line);
        $segments[0] = (int)$segments[0];
        $segments[2] = Rdv::parse($segments[2]);
        $segments[4] = VariantIdentifier::parse($segments[4], '***');
        return new Variant(...$segments);
    }
}