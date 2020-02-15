<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Bungle\FrameworkBundle\DependencyInjection\RegisterSTTPass;

final class BungleFrameworkBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterSTTPass());

        // TODO: remove RegisterListenersPass(), RegisterListenersPass() add by
        // FrameworkBundle
        $container->addCompilerPass(new RegisterListenersPass());
    }
}
