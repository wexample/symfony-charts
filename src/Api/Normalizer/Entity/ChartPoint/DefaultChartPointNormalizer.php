<?php

namespace Wexample\SymfonyCharts\Api\Normalizer\Entity\ChartPoint;

use ArrayObject;
use Wexample\SymfonyCharts\Api\Dto\PublicChartPointDto;
use Wexample\SymfonyCharts\Entity\ChartPoint;
use Wexample\SymfonyCharts\Entity\Traits\Manipulator\ChartPointManipulatorTrait;
use Wexample\SymfonyHelpers\Entity\AbstractEntity;
use Wexample\SymfonyHelpers\Interface\NormalizableDataInterface;
use Wexample\SymfonyHelpers\Normalizer\AbstractEntityNormalizer;

class DefaultChartPointNormalizer extends AbstractEntityNormalizer
{
    use ChartPointManipulatorTrait;

    public function normalizeEntity(
        ChartPoint|AbstractEntity $entity,
        ?string $format = null,
        array $context = []
    ): array|string|int|float|bool|ArrayObject|NormalizableDataInterface|null {
        return PublicChartPointDto::fromEntity($entity);
    }
}
