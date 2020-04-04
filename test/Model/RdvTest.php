<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Test\Model;

use Miklcct\Nwst\Model\Rdv;
use PHPUnit\Framework\TestCase;

class RdvTest extends TestCase {
    public function testParse() : void {
        $rdv = Rdv::parse('14-MAH-2');
        self::assertSame('14', $rdv->route);
        self::assertSame('MAH', $rdv->destination);
        self::assertSame(2, $rdv->variant);
    }

    public function test__toString() : void {
        self::assertSame('14-MAH-2', (string)(new Rdv('14', 'MAH', 2)));
    }
}