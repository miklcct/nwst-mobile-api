<?php
declare(strict_types=1);

namespace Miklcct\Nwst;

use ErrorException;
use InvalidArgumentException;
use function error_reporting;
use function filter_var;
use function ltrim;
use function set_error_handler;
use const E_ALL;
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

/**
 * Return NULL if $test is the same as $compare, otherwise $test
 *
 * @param mixed $test
 * @param mixed $compare
 * @return mixed
 */
function nullif($test, $compare) {
    return $test === $compare ? NULL : $test;
}

function enable_error_exceptions(callable $callback) {
    try {
        $old_error_handler = set_error_handler(
            function (int $severity, string $message, string $file, int $line) {
                if (error_reporting() & $severity) {
                    throw new ErrorException($message, 0, $severity, $file, $line);
                }
            }
        );
        $old_error_reporting = error_reporting(E_ALL);
        return $callback();
    } finally {
        error_reporting($old_error_reporting);
        set_error_handler($old_error_handler);
    }
}