<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\Security;

use Bungle\FrameworkBundle\Security\RoleDefinition;
use PHPUnit\Framework\TestCase;

final class RoleDefinitionTest extends TestCase
{
    public function testNewActionRole(): void
    {
        self::assertEquals('ROLE_Foo_Bar', RoleDefinition::newActionRole('Foo', 'Bar'));
    }

    public function testParseActionRole(): void
    {
        self::assertEquals(['Foo', 'Bar'], RoleDefinition::parseActionRole('ROLE_Foo_Bar'));
    }
}
