<?php

namespace Wexample\SymfonyCharts\Helper;

use Wexample\SymfonyCharts\Entity\ChartPoint;

class ChartPointHelper
{
    /**
     * The points of one series, one per category of the axis and in its
     * order. A category the values do not hold gets `missing` — null by
     * default, a gap in the line; zero where nothing happened is a value.
     *
     * @param list<string> $categories the axis, worded and ordered
     * @param array<string, int|float|null> $values by category
     *
     * @return list<ChartPoint>
     */
    public static function series(
        string $chart,
        string $series,
        array $categories,
        array $values,
        ?string $label = null,
        ?string $color = null,
        int|float|null $missing = null,
    ): array {
        $points = [];

        foreach (array_values($categories) as $position => $category) {
            $value = array_key_exists($category, $values) ? $values[$category] : $missing;

            $points[] = new ChartPoint($chart, $series, $category)
                ->setPosition($position)
                ->setY(null === $value ? null : (float) $value)
                ->setSeriesLabel($label)
                ->setColor($color);
        }

        return $points;
    }

    /**
     * The same, from a list of values given in the order of the categories.
     *
     * @param list<string> $categories
     * @param list<int|float|null> $values
     *
     * @return list<ChartPoint>
     */
    public static function seriesFromList(
        string $chart,
        string $series,
        array $categories,
        array $values,
        ?string $label = null,
        ?string $color = null,
    ): array {
        $categories = array_values($categories);

        return self::series(
            $chart,
            $series,
            $categories,
            array_combine($categories, array_pad(array_slice(array_values($values), 0, count($categories)), count($categories), null)),
            $label,
            $color
        );
    }
}
