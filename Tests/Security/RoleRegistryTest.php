<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Security;

use PHPUnit\Framework\TestCase;
use Bungle\FrameworkBundle\Security\RoleRegistry;
use Bungle\FrameworkBundle\Security\RoleDefinition;

final class RoleRegistryTest extends TestCase
{
    public function testAdd(): RoleRegistry
    {
        $reg = new RoleRegistry;
        self::assertEmpty($reg->defs);
        $reg->add($r1 = new RoleDefinition('ROLE_1_1', '', ''));
        $reg->add($r2 = new RoleDefinition('ROLE_1_2', '', ''));
        self::assertEquals([$r1, $r2], $reg->defs);
        return $reg;
    }

    /**
     * @depends testAdd
     */
    public function testAddCheckDupName(RoleRegistry $reg): void
    {
        $this->expectException(\AssertionError::class);
        $reg->add(new RoleDefinition('ROLE_1_1', 'a', 'b'));
    }

    /**
     * @depends testAdd
     */
    public function testAdds(RoleRegistry $reg): void
    {
        $a = 1;
        $expRoles = $reg->defs;
        $reg->adds([
        $r3 = new RoleDefinition('ROLE_2_1', '', ''),
        $r4 = new RoleDefinition('ROLE_2_2', '', ''),
        ]);
    
        self::assertEquals(array_merge($expRoles, [$r3, $r4]), $reg->defs);
    }
}
