<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Tests\DependencyInjection;

use Bungle\Framework\Ent\Code\CodeGenerator;
use Bungle\FrameworkBundle\DependencyInjection\RegisterCodeGeneratorPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterCodeGeneratorPassTest extends TestCase
{
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $container->register('g1')->addTag(RegisterCodeGeneratorPass::CODE_GEN_TAG);
        $container->register('g2')->addTag(RegisterCodeGeneratorPass::CODE_GEN_TAG);
        $container->register(CodeGenerator::class, CodeGenerator::class)->addArgument([]);

        (new RegisterCodeGeneratorPass())->process($container);
        $def = $container->getDefinition(CodeGenerator::class);
        self::assertEquals([[new Reference('g1'), new Reference('g2')]], $def->getArguments());
    }
}
