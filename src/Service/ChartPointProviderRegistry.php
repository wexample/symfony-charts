<?php

namespace Wexample\SymfonyCharts\Service;

use Psr\Container\ContainerInterface;
use Wexample\SymfonyCharts\Interface\ChartPointProviderInterface;

/**
 * Every chart provider of the application, by the name of its chart.
 *
 * A locator rather than the providers themselves: a request draws one chart,
 * and only its provider — with whatever it depends on — is built for it.
 */
class ChartPointProviderRegistry
{
    /**
     * @param ContainerInterface $providers the tagged providers, indexed by
     *                                      `getChartName()`
     */
    public function __construct(
        private readonly ContainerInterface $providers,
    ) {
    }

    public function get(string $chart): ?ChartPointProviderInterface
    {
        return $this->providers->has($chart) ? $this->providers->get($chart) : null;
    }
}
