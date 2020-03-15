<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DisableFormGuesser implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // Disable builtin form type guessers, because we defined
        // BungleFormTypeGuesser add label to the result of
        // 'form.type_guesser.doctrine.mongodb'.
        // If not disabled, depends type guessers order, builtin
        // guess used instead of ours.
        $srvs = [
        /* 'form.type_guesser.validator', */
        'form.type_guesser.doctrine.mongodb',
        /* 'form.type_guesser.doctrine' */
        ];
        foreach ($srvs as $srvName) {
            if ($container->hasDefinition($srvName)) {
                $srv = $container->findDefinition($srvName);
                $srv->clearTag('form.type_guesser');
            }
        }
    }
}
