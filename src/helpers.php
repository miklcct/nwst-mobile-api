<?php
declare(strict_types=1);

namespace Miklcct\Nwst;

use InvalidArgumentException;
use function filter_var;
use function ltrim;
use const FILTER_VALIDATE_FLOAT;
use const FILTER_VALIDATE_INT;

function parse_int(string $string) : int {
    if ($string === '0') return 0;
    $result = filter_var(ltrim(trim($string), '0'), FILTER_VALIDATE_INT);
    if ($result === FALSE) {
        throw new InvalidArgumentException("$string is not a valid integer.");
    }
    return $result;
}

function parse_float(string $string) : float {
    $result = filter_var($string, FILTER_VALIDATE_FLOAT);
    if ($result === FALSE) {
        throw new InvalidArgumentException("$string is not a valid floating point number.");
    }
    return $result;
}

function parse_yes_no(string $string) : bool {
    switch ($string) {
    case 'Y':
        return TRUE;
    case 'N':
        return FALSE;
    default:
        throw new InvalidArgumentException("$string is not a valid yes/no option.");
    }
}