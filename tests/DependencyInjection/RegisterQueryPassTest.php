<?php

declare(strict_types=1);

namespace DependencyInjection;

use Bungle\Framework\Inquiry\Query;
use Bungle\Framework\Inquiry\QueryFactory;
use Bungle\FrameworkBundle\DependencyInjection\RegisterQueryPass;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FooQuery extends Query
{
    public function __construct()
    {
        $em = Mockery::mock(EntityManagerInterface::class);
        parent::__construct($em, [], '');
    }
}

class RegisterQueryPassTest extends MockeryTestCase
{
    public function testProcess(): void
    {
        $container = new ContainerBuilder();
        $container->register(QueryFactory::class, QueryFactory::class);
        $query1 = new FooQuery();
        $container->register($query1::class, $query1::class)->addTag(
            QueryFactory::SERVICE_TAG
        );

        (new RegisterQueryPass())->process($container);
        /** @var QueryFactory $factory */
        $factory = $container->get(QueryFactory::class);
        self::assertInstanceOf($query1::class, $factory->getQuery($query1::class));
    }
}
