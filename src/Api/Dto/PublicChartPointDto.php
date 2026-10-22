<?php

namespace Wexample\SymfonyCharts\Api\Dto;

use Wexample\SymfonyApi\Api\Dto\AbstractEntityDto;
use Wexample\SymfonyCharts\Entity\ChartPoint;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;

/**
 * A point as it goes on the wire: everything the chart component draws, and
 * nothing of where the value was computed from.
 */
class PublicChartPointDto extends AbstractEntityDto
{
    public string $chart;

    public string $series;

    public ?string $seriesLabel;

    public string $x;

    public int $position;

    public ?float $y;

    public ?string $color;

    /**
     * @param ChartPoint $entity
     */
    public static function fromEntity(AbstractEntity $entity): self
    {
        $dto = parent::fromEntity($entity);

        $dto->chart = $entity->getChart();
        $dto->series = $entity->getSeries();
        $dto->seriesLabel = $entity->getSeriesLabel();
        $dto->x = $entity->getX();
        $dto->position = $entity->getPosition();
        $dto->y = $entity->getY();
        $dto->color = $entity->getColor();

        return $dto;
    }
}
