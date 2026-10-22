<?php

namespace Wexample\SymfonyCharts\Entity;

use Symfony\Component\Uid\Uuid;
use Wexample\Pseudocode\Attribute\PseudocodeExport;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;

/**
 * One value of one series of one chart: a chart is the list of its points.
 *
 * An entity without an ORM mapping — a point is computed when it is asked for,
 * from whatever the application stores — so that a chart travels the road
 * every list of the stack travels: normalizer, dto, generated `ChartPoint.ts`
 * and its repository, which the chart component reads. Doctrine skips a class
 * carrying no `#[ORM\Entity]`.
 *
 * `series` is a key the chart component knows a type by: any key for a line
 * or a bar, `load` and `capacity` for a capacity chart, `actual`, `forecast`,
 * `low` and `high` for a forecast.
 */
#[PseudocodeExport(inherited: true)]
class ChartPoint extends AbstractEntity
{
    /**
     * Fixed namespace the identity is hashed under, so the same point asked
     * twice is the same entity twice and the front reconciles it in place.
     */
    public const ID_NAMESPACE = '0d7c1b52-3f0e-4b8e-9a54-6a1f4c2e8b37';

    /** The chart it belongs to, as its provider names it. */
    protected string $chart;

    protected string $series;

    /** The series as the legend writes it, already translated. */
    protected ?string $seriesLabel = null;

    /** The category on the x axis, already worded: a month, a group. */
    protected string $x;

    /** Where the category stands on the axis: the order is not the label's. */
    protected int $position = 0;

    /** Null where the series has no value: a gap, not a zero. */
    protected ?float $y = null;

    /** A category of the design system palette: `mint`, `plum`… */
    protected ?string $color = null;

    public function __construct(
        string $chart,
        string $series,
        string $x,
    ) {
        parent::__construct();

        $this->chart = $chart;
        $this->series = $series;
        $this->x = $x;

        $this->setId(self::idFor($chart, $series, $x));
    }

    public static function idFor(
        string $chart,
        string $series,
        string $x,
    ): Uuid {
        return Uuid::v5(
            Uuid::fromString(self::ID_NAMESPACE),
            $chart."\0".$series."\0".$x
        );
    }

    public function getChart(): string
    {
        return $this->chart;
    }

    public function getSeries(): string
    {
        return $this->series;
    }

    public function getSeriesLabel(): ?string
    {
        return $this->seriesLabel;
    }

    public function setSeriesLabel(?string $seriesLabel): self
    {
        $this->seriesLabel = $seriesLabel;

        return $this;
    }

    public function getX(): string
    {
        return $this->x;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getY(): ?float
    {
        return $this->y;
    }

    public function setY(?float $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
