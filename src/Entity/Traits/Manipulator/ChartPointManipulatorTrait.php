<?php

namespace Wexample\SymfonyCharts\Entity\Traits\Manipulator;

use Wexample\SymfonyCharts\Entity\ChartPoint;
use Wexample\SymfonyHelpers\Entity\Traits\Manipulator\EntityManipulatorTrait;

trait ChartPointManipulatorTrait
{
    use EntityManipulatorTrait;

    public static function getEntityClassName(): string
    {
        return ChartPoint::class;
    }
}
