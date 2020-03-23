<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle;

use Bungle\FrameworkBundle\DependencyInjection\DisableFormGuesser;
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

        // TODO: remove RegisterListenersPass(), RegisterListenersPass() add by
        // FrameworkBundle
        $container->addCompilerPass(new RegisterListenersPass());
        $container->addCompilerPass(new DisableFormGuesser(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 100);
    }
}
