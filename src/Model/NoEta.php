<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Model;

class NoEta {
    public const FORMAT_TEXT = 'TEXT';
    public const FORMAT_HTML = 'HTML';

    public function __construct(string $format, string $content) {
        $this->format = $format;
        $this->content = $content;
    }

    /**
     * @var string
     */
    public $format;
    /**
     * @var string
     */
    public $content;
}