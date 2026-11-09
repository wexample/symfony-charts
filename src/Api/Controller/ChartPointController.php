<?php

namespace Wexample\SymfonyCharts\Api\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Wexample\SymfonyApi\Api\Attribute\QueryOption\StringQueryOption;
use Wexample\SymfonyApi\Api\Class\ApiResponse;
use Wexample\SymfonyApi\Api\Controller\AbstractApiController;
use Wexample\SymfonyCharts\Api\Normalizer\Entity\ChartPoint\DefaultChartPointNormalizer;
use Wexample\SymfonyCharts\Service\ChartPointProviderRegistry;
use Wexample\SymfonyHelpers\Controller\AbstractController;

/**
 * A chart, as the collection of its points: the `list` of the ChartPoint
 * entity, which the generated `ChartPointRepository.ts` calls without being
 * told where it is. Which chart is the `chart` option; `from` and `to` (ISO
 * dates, `to` excluded) the period a chart following the page's period-nav
 * asks for; `scope` what the chart is about — a project, an account, by its
 * id —, for a chart a page draws of one record among several. The provider
 * reads them from the request, checks the scope as it checks any access,
 * and ignores what it has no use for.
 */
#[Route(path: '_api/chart-point/', name: 'api_chart_point_')]
class ChartPointController extends AbstractApiController
{
    final public const string ROUTE_LIST = 'list';

    final public const string QUERY_OPTION_CHART = 'chart';

    final public const string QUERY_OPTION_FROM = 'from';

    final public const string QUERY_OPTION_TO = 'to';

    final public const string QUERY_OPTION_SCOPE = 'scope';

    #[Route(path: 'list', name: self::ROUTE_LIST, methods: AbstractController::ROUTE_OPTIONS_METHOD_ONLY_GET, options: AbstractController::ROUTE_OPTIONS_ONLY_EXPOSE)]
    #[StringQueryOption(key: self::QUERY_OPTION_CHART, default: '')]
    #[StringQueryOption(key: self::QUERY_OPTION_FROM, default: '')]
    #[StringQueryOption(key: self::QUERY_OPTION_TO, default: '')]
    #[StringQueryOption(key: self::QUERY_OPTION_SCOPE, default: '')]
    public function list(
        Request $request,
        ChartPointProviderRegistry $registry,
        DefaultChartPointNormalizer $normalizer,
    ): ApiResponse {
        $chart = (string) self::getQueryOptionValue($request, self::QUERY_OPTION_CHART, '');
        $provider = $registry->get($chart)
            ?? throw new NotFoundHttpException(sprintf('No chart is named "%s".', $chart));

        $points = $provider->getPoints($request);

        return self::apiResponseCollection(
            $normalizer->normalizeCollection(is_array($points) ? $points : iterator_to_array($points, false))
        );
    }
}
