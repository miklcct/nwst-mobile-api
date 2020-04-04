<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Model;

use function explode;
use const FILTER_VALIDATE_INT;

class Rdv {
    public function __construct(string $route, string $destination, int $variant) {
        $this->route = $route;
        $this->destination = $destination;
        $this->variant = $variant;
    }

    public static function parse(string $rdv) : self {
        $segments = explode('-', $rdv);
        $segments[2] = filter_var($segments[2], FILTER_VALIDATE_INT);
        return new self(...$segments);
    }

    public function __toString() : string {
        return "{$this->route}-{$this->destination}-{$this->variant}";
    }

    /**
     * @var string
     */
    public $route;
    /**
     * @var string
     */
    public $destination;
    /**
     * @var int
     */
    public $variant;
}