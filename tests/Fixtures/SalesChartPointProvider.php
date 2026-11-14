<?php

declare(strict_types=1);

namespace Wexample\SymfonyCharts\Tests\Fixtures;

use Symfony\Component\HttpFoundation\Request;
use Wexample\SymfonyCharts\Interface\ChartPointProviderInterface;

/** A provider counting how many times it was built. */
class SalesChartPointProvider implements ChartPointProviderInterface
{
    public static int $built = 0;

    public function __construct()
    {
        ++self::$built;
    }

    public static function getChartName(): string
    {
        return 'sales';
    }

    public function getPoints(Request $request): iterable
    {
        return [];
    }
}
