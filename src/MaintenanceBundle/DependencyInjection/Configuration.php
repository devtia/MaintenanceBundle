<?php

/*
 * This file is part of the MaintenanceBundle package.
 *
 * Copyright (c) Jaime Martínez
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Jaime Martínez <jaime@devtia.com>
 */

namespace Devtia\MaintenanceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('maintenance');

        $rootNode
            ->children()
                ->booleanNode('enable_maintenance')->defaultFalse()->end()
                ->arrayNode('routes_prefixes')
                    ->treatNullLike(array())
                    ->arrayPrototype()
                        ->prototype('scalar')->end()
                        ->prototype('scalar')->treatNullLike('')->defaultValue('')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
