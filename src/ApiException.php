<?php
declare(strict_types=1);

namespace Miklcct\Nwst;

use Exception;

class ApiException extends Exception {
    public const EMPTY_BODY = 1;
    public const PARSE_ERROR = 2;
    public const HTTP_ERROR = 3;
}