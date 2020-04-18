<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Parser;

interface ParserInterface {
    public function __invoke(string $content);
}