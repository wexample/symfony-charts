<?php

namespace Wexample\SymfonyCharts\Service;

use Wexample\SymfonyCharts\Interface\ChartPointProviderInterface;

/**
 * Every chart provider of the application, by the name of its chart.
 */
class ChartPointProviderRegistry
{
    /** @var array<string, ChartPointProviderInterface> */
    private array $providers = [];

    /**
     * @param iterable<ChartPointProviderInterface> $providers
     */
    public function __construct(iterable $providers)
    {
        foreach ($providers as $provider) {
            $this->providers[$provider::getChartName()] = $provider;
        }
    }

    public function get(string $chart): ?ChartPointProviderInterface
    {
        return $this->providers[$chart] ?? null;
    }
}
