<?php

declare(strict_types=1);

namespace Wexample\SymfonyCharts\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Wexample\SymfonyCharts\Interface\ChartPointProviderInterface;
use Wexample\SymfonyCharts\Service\ChartPointProviderRegistry;
use Wexample\SymfonyCharts\Tests\Fixtures\SalesChartPointProvider;

class ChartPointProviderRegistryTest extends TestCase
{
    public function testAProviderIsFoundByItsChartAndBuiltOnlyWhenAsked(): void
    {
        $container = new ContainerBuilder();
        // The bundle's own wiring, read as the application reads it.
        (new YamlFileLoader($container, new FileLocator(__DIR__.'/../../../src/Resources/config')))->load('services.yaml');

        // The registry alone, the controllers and normalizers asking for an
        // application around them.
        foreach (array_keys($container->getDefinitions()) as $id) {
            if (ChartPointProviderRegistry::class !== $id && 'service_container' !== $id) {
                $container->removeDefinition($id);
            }
        }

        $container->register(SalesChartPointProvider::class, SalesChartPointProvider::class)
            ->addTag(ChartPointProviderInterface::TAG);
        $container->getDefinition(ChartPointProviderRegistry::class)->setPublic(true);
        $container->compile();

        SalesChartPointProvider::$built = 0;

        /** @var ChartPointProviderRegistry $registry */
        $registry = $container->get(ChartPointProviderRegistry::class);
        $this->assertSame(0, SalesChartPointProvider::$built);

        $this->assertNull($registry->get('unknown'));
        $this->assertInstanceOf(SalesChartPointProvider::class, $registry->get('sales'));
        $this->assertSame(1, SalesChartPointProvider::$built);
    }
}
