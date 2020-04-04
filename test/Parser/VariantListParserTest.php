<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Test\Parser;

use Miklcct\Nwst\Model\Rdv;
use Miklcct\Nwst\Model\RouteVariant;
use Miklcct\Nwst\Model\VariantInfo;
use Miklcct\Nwst\Parser\VariantListParser;
use PHPUnit\Framework\TestCase;
use function file_get_contents;

class VariantListParserTest extends TestCase {
    public function test() : void {
        $result = (new VariantListParser())(file_get_contents(__DIR__ . '/VariantList'));
        /** @var RouteVariant[] $variants */
        $variants = [
            new RouteVariant(
                1
                , RouteVariant::COLOUR_GREEN
                , Rdv::parse('14-MAH-2')
                , 'Departure via Stanley Village, Stanley Fort to Stanley Plaza'
                , VariantInfo::parse('NWFB***14-MAH-2***1***30***10405***O', '***')
            )
            , new RouteVariant(
                2
                , RouteVariant::COLOUR_RED
                , Rdv::parse('14-SFG-1')
                , 'Departure via Stanley Village to Stanley Fort'
                , VariantInfo::parse('NWFB***14-SFG-1***1***24***10406***O', '***')
            )
        ];
        foreach ($variants as $variant) {
            self::assertContainsEquals($variant, $result);
        }
    }
}