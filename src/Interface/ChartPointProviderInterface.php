<?php

namespace Wexample\SymfonyCharts\Interface;

use Symfony\Component\HttpFoundation\Request;
use Wexample\SymfonyCharts\Entity\ChartPoint;

/**
 * What an application implements to serve a chart: a name, and the points of
 * that chart for a request, whose locale words the categories. Autoconfigured:
 * implementing it is enough for
 * `GET /api/chart-point/list?chart=<name>` to answer.
 */
interface ChartPointProviderInterface
{
    public const string TAG = 'wexample_symfony_charts.chart_point_provider';

    public static function getChartName(): string;

    /**
     * @return iterable<ChartPoint>
     */
    public function getPoints(Request $request): iterable;
}
