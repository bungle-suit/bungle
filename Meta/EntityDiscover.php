<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Meta;

/**
 * Interface support returns all entity class of current system.
 */
interface EntityDiscover
{
    /**
     * Return all entity class names of current system.
     *
     * To get entities classes from Doctrine:
     *
     *   $classes = [];
     *   $metas = $entityManager->getMetadataFactory()->getAllMetadata();
     *   foreach ($metas as $meta) {
     *    $classes[] = $meta->getName();
     *   }
     *   // $classes now contains full path of entity classes.
     */
    public function getAllEntities(): array;
}
