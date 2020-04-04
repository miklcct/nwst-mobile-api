<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Test\Model;

use Miklcct\Nwst\Model\Rdv;
use Miklcct\Nwst\Model\VariantInfo;
use PHPUnit\Framework\TestCase;

class VariantIdentifierTest extends TestCase {
    public function testParse() : void {
        $variant = VariantInfo::parse('NWFB***14-GRP-3***1***22***10410***I', '***');
        self::assertSame('NWFB', $variant->company);
        self::assertEquals(Rdv::parse('14-GRP-3'), $variant->rdv);
        self::assertSame(1, $variant->from_stop);
        self::assertSame(22, $variant->to_stop);
        self::assertSame(10410, $variant->id);
        self::assertSame('I', $variant->bound);
    }

    public function test__toString() : void {
        $variant = new VariantInfo('14', Rdv::parse('14-GRP-3'), 1, 22, 10410, 'I');
        self::assertSame($variant->toString('||'), $variant->__toString());
    }

    public function testToString() : void {
        $variant = new VariantInfo('NWFB', Rdv::parse('14-GRP-3'), 1, 22, 10410, 'I');
        self::assertSame('NWFB||14-GRP-3||1||22||10410||I', $variant->toString('||'));
    }
}