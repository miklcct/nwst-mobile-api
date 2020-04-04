<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Test\Parser;

use Miklcct\Nwst\Model\Rdv;
use Miklcct\Nwst\Model\Variant;
use Miklcct\Nwst\Parser\VariantListParser;
use PHPUnit\Framework\TestCase;
use function file_get_contents;

class VariantListParserTest extends TestCase {
    public function test() : void {
        $result = (new VariantListParser())(file_get_contents(__DIR__ . '/VariantList'));
        /** @var Variant[] $variants */
        $variants = [
            new Variant(
                1
                , Variant::COLOUR_GREEN
                , new Rdv('14', 'MAH', 2)
                , 'Departure via Stanley Village, Stanley Fort to Stanley Plaza'
                , 'NWFB***14-MAH-2***1***30***10405***O'
            )
            , new Variant(
                2
                , Variant::COLOUR_RED
                , new Rdv('14', 'SFG', 1)
                , 'Departure via Stanley Village to Stanley Fort'
                , 'NWFB***14-SFG-1***1***24***10406***O'
            )
        ];
        foreach ($variants as $variant) {
            self::assertContainsEquals($variant, $result);
        }
    }
}