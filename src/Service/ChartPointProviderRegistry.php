<?php

namespace Wexample\SymfonyCharts\Service;

use Wexample\SymfonyCharts\Interface\ChartPointProviderInterface;

/**
 * Every chart provider of the application, by the name of its chart.
 */
class ChartPointProviderRegistry
{
    /** @var array<string, ChartPointProviderInterface> */
    private readonly array $providers;

    /**
     * @param iterable<ChartPointProviderInterface> $providers
     */
    public function __construct(iterable $providers)
    {
        $byChart = [];
        foreach ($providers as $provider) {
            $byChart[$provider::getChartName()] = $provider;
        }

        $this->providers = $byChart;
    }

    public function get(string $chart): ?ChartPointProviderInterface
    {
        return $this->providers[$chart] ?? null;
    }
}
