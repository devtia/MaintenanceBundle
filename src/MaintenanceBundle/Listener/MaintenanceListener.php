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

namespace Devtia\MaintenanceBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class MaintenanceListener implements EventSubscriberInterface
{
    private $enableMaintenance;

    private $routesPrefixes;

    private $container;

    public function __construct(bool $enableMaintenance, array $routesPrefixes, ContainerInterface $container)
    {
        $this->enableMaintenance = $enableMaintenance;
        $this->routesPrefixes = $routesPrefixes;
        $this->container = $container;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => ['onKernelRequest', 2500],
        ];
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$this->enableMaintenance) {
            return;
        }

        if (!$this->isValidRoute($event)) {
            return;
        }

        $parameterTemplate = $this->getParameterTemplate($event);

        $twig = $this->container->get('twig');

        $twigTemplate = '@Maintenance/maintenance.html.twig';
        $customTemplate = sprintf('%s/Resources/Devtia/MaintenanceBundle/views/maintenance.html.twig', $this->container->getParameter('kernel.root_dir'));
        if (file_exists($customTemplate)) {
            $twigTemplate = $customTemplate;
        }

        if (file_exists($parameterTemplate)) {
            $twigTemplate = $parameterTemplate;
        }

        $event->setResponse(new Response($twig->render($twigTemplate)));
    }

    private function isValidRoute(GetResponseEvent $event): bool
    {
        if (0 == count($this->routesPrefixes)) {
            return true;
        }

        $requestUri = $event->getRequest()->getRequestUri();
        foreach ($this->routesPrefixes as $routePrefix) {
            $routeRegex = $this->generateRouteRegex($routePrefix);
            if (preg_match($routeRegex, $requestUri)) {
                return true;
            }
        }

        return false;
    }

    private function getParameterTemplate(GetResponseEvent $event): string
    {
        $requestUri = $event->getRequest()->getRequestUri();
        foreach ($this->routesPrefixes as $routePrefix) {
            $routeRegex = $this->generateRouteRegex($routePrefix);
            if (preg_match($routeRegex, $requestUri)) {
                if (array_key_exists(1, $routePrefix)) {
                    return $routePrefix[1];
                }

                return '';
            }
        }

        return '';
    }

    private function generateRouteRegex(string $routePrefix): string
    {
        return '/'.$routePrefix[0].'.*/';
    }
}
