<?php

namespace Wexample\SymfonyCharts\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wexample\SymfonyCharts\Interface\ChartPointProviderInterface;
use Wexample\SymfonyHelpers\DependencyInjection\AbstractWexampleSymfonyExtension;

class WexampleSymfonyChartsExtension extends AbstractWexampleSymfonyExtension
{
    public function load(
        array $configs,
        ContainerBuilder $container
    ): void {
        $this->loadConfig(
            __DIR__,
            $container
        );

        // Any service of any bundle saying it provides a chart is found by
        // the endpoint, with nothing to declare.
        $container->registerForAutoconfiguration(ChartPointProviderInterface::class)
            ->addTag(ChartPointProviderInterface::TAG);
    }
}
