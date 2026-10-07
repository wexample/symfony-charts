<?php

namespace Wexample\SymfonyCharts\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Wexample\SymfonyCharts\Entity\ChartPoint;
use Wexample\SymfonyCharts\Helper\ChartPointHelper;

class ChartPointHelperTest extends TestCase
{
    public function testSeriesFillsTheAxis(): void
    {
        $points = ChartPointHelper::series('output', 'woven', ['jan', 'feb', 'mar'], ['mar' => 3, 'jan' => 1], 'Woven', 'mint', 0);

        $this->assertSame(['jan', 'feb', 'mar'], array_map(static fn (ChartPoint $point) => $point->getX(), $points));
        $this->assertSame([0, 1, 2], array_map(static fn (ChartPoint $point) => $point->getPosition(), $points));
        $this->assertSame([1.0, 0.0, 3.0], array_map(static fn (ChartPoint $point) => $point->getY(), $points));
        $this->assertSame('Woven', $points[0]->getSeriesLabel());
        $this->assertSame('mint', $points[0]->getColor());
    }

    public function testGapsStayGaps(): void
    {
        $points = ChartPointHelper::seriesFromList('output', 'actual', ['jan', 'feb', 'mar'], [5, null]);

        $this->assertSame([5.0, null, null], array_map(static fn (ChartPoint $point) => $point->getY(), $points));
    }

    public function testIdentityIsStable(): void
    {
        $this->assertTrue(
            new ChartPoint('output', 'woven', 'jan')->getId()->equals(ChartPoint::idFor('output', 'woven', 'jan'))
        );
        $this->assertFalse(ChartPoint::idFor('output', 'woven', 'jan')->equals(ChartPoint::idFor('output', 'woven', 'feb')));
    }
}
