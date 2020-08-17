<?php

declare(strict_types=1);

namespace Bungle\FrameworkBundle\DependencyInjection;

use Bungle\Framework\Form\BungleFormTypeGuesser;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Form\FormTypeGuesserChain;

class DisableFormGuesser implements CompilerPassInterface
{
    const TAG_TYPE_GUESSER = 'form.type_guesser';

    public function process(ContainerBuilder $container)
    {
        // Disable builtin form type guessers, because we defined
        // BungleFormTypeGuesser add label to the result of
        // 'form.type_guesser.doctrine.mongodb'.
        // If not disabled, depends type guessers order, builtin
        // guess used instead of ours.
        $ids = $container->findTaggedServiceIds(self::TAG_TYPE_GUESSER);
        foreach ($ids as $id => $v) {
            $def = $container->findDefinition($id);
            $def->clearTag(self::TAG_TYPE_GUESSER);
        }
        $chained = new Definition(FormTypeGuesserChain::class);
        $chained->addArgument(
            array_map(fn (string $id) => new Reference($id), array_keys($ids))
        );

        $ours = new Definition(BungleFormTypeGuesser::class);
        $ours->addArgument(new Reference('bungle.type_guesser.chained'));
        $ours->addArgument(new Reference('property_info'));
        $ours->addArgument(new Reference('Psr\Log\LoggerInterface'));
        $ours->addTag(self::TAG_TYPE_GUESSER);
        $container->addDefinitions([
            'bungle.type_guesser.chained' => $chained,
            'bungle.form.type_guesser' => $ours,
        ]);
    }
}
