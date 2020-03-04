<?php

/*
 * This file is part of the Maintenance package.
 *
 * Copyright (c) Jaime Martínez
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Jaime Martínez <jaime@devtia.com>
 */

namespace Devtia\MaintenanceBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MaintenanceExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('devtia_maintenance.enable_maintenance', $config['enable_maintenance']);
        $container->setParameter('devtia_maintenance.routes_prefixes', $config['routes_prefixes']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('listeners.yml');
    }
}
