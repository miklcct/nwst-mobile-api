<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Model;

use function explode;
use function filter_var;
use function implode;
use const FILTER_VALIDATE_INT;

class VariantInfo {
    public function __construct(
        string $company
        , Rdv $rdv
        , int $from
        , int $to
        , int $id
        , string $bound
    ) {
        $this->company = $company;
        $this->rdv = $rdv;
        $this->from_stop = $from;
        $this->to_stop = $to;
        $this->id = $id;
        $this->bound = $bound;
    }

    public static function parse(string $variant, string $delimiter) : self {
        $segments = explode($delimiter, $variant);
        $segments[1] = Rdv::parse($segments[1]);
        foreach ([2, 3, 4] as $index) {
            $segments[$index] = filter_var($segments[$index], FILTER_VALIDATE_INT);
        }
        return new self(...$segments);
    }

    public function __toString() : string {
        return $this->toString('||');
    }

    public function toString(string $delimiter) : string {
        return implode(
            $delimiter
            , [
                $this->company,
                $this->rdv->__toString(),
                (string)$this->from_stop,
                (string)$this->to_stop,
                (string)$this->id,
                $this->bound,
            ]
        );
    }

    /**
     * @var string
     */
    public $company;
    /**
     * @var Rdv
     */
    public $rdv;
    /**
     * @var int The starting stop shown in the variant
     */
    public $from_stop;
    /**
     * @var int The ending stop shown in the variant
     */
    public $to_stop;
    /**
     * @var int A unique number
     */
    public $id;
    /**
     * @var string
     */
    public $bound;
}