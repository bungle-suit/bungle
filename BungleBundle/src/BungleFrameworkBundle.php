<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle;

use Bungle\Framework\Ent\Code\GeneratorInterface;
use Bungle\Framework\Ent\IDName\HighIDNameTranslatorInterface;
use Bungle\Framework\StateMachine\STT\STTInterface;
use Bungle\FrameworkBundle\DependencyInjection\DisableFormGuesser;
use Bungle\FrameworkBundle\DependencyInjection\HighIDNameTranslatorPass;
use Bungle\FrameworkBundle\DependencyInjection\RegisterCodeGeneratorPass;
use Bungle\FrameworkBundle\DependencyInjection\RegisterSTTPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BungleFrameworkBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterSTTPass());

        $container->registerForAutoconfiguration(GeneratorInterface::class)
            ->addTag(RegisterCodeGeneratorPass::CODE_GEN_TAG);

        $container->registerForAutoconfiguration(HighIDNameTranslatorInterface::class)
            ->addTag(HighIDNameTranslatorPass::IDName_TAG);

        $container->registerForAutoconfiguration(STTInterface::class)
            ->addTag(RegisterSTTPass::STT_TAG);

        // TODO: remove RegisterListenersPass(), RegisterListenersPass() add by
        // FrameworkBundle
        $container->addCompilerPass(new RegisterListenersPass());
        $container->addCompilerPass(new HighIDNameTranslatorPass());
        $container->addCompilerPass(new DisableFormGuesser(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 100);
        $container->addCompilerPass(new RegisterCodeGeneratorPass());
    }
}
