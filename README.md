# symfony-charts

Version: 2.0.0

## What this package holds

The data side of the charts: a chart is a list of `ChartPoint` entities, served by one endpoint. Drawing them is `symfony-charts-ds`; the demo pages are `symfony-charts-demo`.

`ChartPoint` is an entity with no table — see *Every list is a list of entities* in `symfony-api` (`usage/non-persisted-entities`). A point is computed when it is asked for, from whatever the application stores, and travels the road every list of the stack travels: normalizer, dto, generated `ChartPoint.ts` and `ChartPointRepository.ts`, which the chart component reads.

## A point

| Field | |
|---|---|
| `chart` | the chart it belongs to, as its provider names it |
| `series` | a key: any for a line or a bar; `load` and `capacity` for a capacity chart; `actual`, `forecast`, `low`, `high` for a forecast |
| `seriesLabel` | the series as the legend writes it, already translated |
| `x` | the category, already worded — a month in the request's locale, a group |
| `position` | where the category stands on the axis |
| `y` | the value; null is a gap, not a zero |
| `color` | a category of the design system palette: `mint`, `plum`… |

Its id is `Uuid::v5` of the chart, the series and the category: the same point asked twice is the same entity, which the front reconciles in place.

## Serving a chart

Implement `ChartPointProviderInterface` in the application; it is autoconfigured, nothing to declare:

```php
class LoomLoadChartPointProvider implements ChartPointProviderInterface
{
    public static function getChartName(): string
    {
        return 'loom-load';
    }

    public function getPoints(Request $request): iterable
    {
        $groups = $this->groups->names();

        return [
            ...ChartPointHelper::series('loom-load', 'load', $groups, $this->load->byGroup()),
            ...ChartPointHelper::series('loom-load', 'capacity', $groups, $this->capacity->byGroup()),
        ];
    }
}
```

`GET /api/chart-point/list?chart=loom-load` then answers with the standard entity collection (`data.items` of `{type, entity, metadata, relationships}`). An unknown chart name is a 404. The query holds the chart name and nothing else — `symfony-api` refuses an option no attribute declares, so a period or a filter is not passed yet. The provider reads the request's locale to word the categories: `symfony-translations` answers an api call in the language of the page it comes from.

`ChartPointHelper::series()` builds one series over a whole axis: one point per category, in the axis order, `missing` (null by default) where the values hold nothing. `seriesFromList()` takes the values in the order of the categories.

## Front artifacts

`assets/Entity/ChartPoint.ts`, `assets/Repository/ChartPointRepository.ts` and `assets/Common/generated*.ts` are generated, never edited: after a change to the entity, regenerate them from an app as `symfony-api`'s `usage/entity-export-pipeline` says, pointing the paths at this package, and delete the foreign `.yml` the pseudocode command adds before the json export. An app adds `generatedRepositories` and `generatedEntitySchemas` to its api client.

## Table of Contents

- [What this package holds](#what-this-package-holds)
- [A point](#a-point)
- [Serving a chart](#serving-a-chart)
- [Front artifacts](#front-artifacts)
- [Integration in the Suite](#integration-in-the-suite)
- [Dependencies](#dependencies)
- [Versioning & Compatibility Policy](#versioning--compatibility-policy)
- [License](#license)
- [About us](#about-us)
- [Migration Notes](#migration-notes)

The repository does not provide any concrete code that could be documented for now.

## Integration in the Suite

This package is part of the Wexample Suite — a collection of high-quality, modular tools designed to work seamlessly together across multiple languages and environments.

### Related Packages

The suite includes packages for configuration management, file handling, prompts, and more. Each package can be used independently or as part of the integrated suite.

Visit the [Wexample Suite documentation](https://docs.wexample.com) for the complete package ecosystem.

## Dependencies

- php: >=8.5
- wexample/php-pseudocode: >=2.2.0
- wexample/symfony-api: >=12.0.0
- wexample/symfony-helpers: >=15.0.0
- wexample/symfony-pseudocode: >=3.0.0
- symfony/uid: >=6.2

## Versioning & Compatibility Policy

Wexample packages follow **Semantic Versioning** (SemVer):

- **MAJOR**: Breaking changes
- **MINOR**: New features, backward compatible
- **PATCH**: Bug fixes, backward compatible

We maintain backward compatibility within major versions and provide clear migration guides for breaking changes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

Free to use in both personal and commercial projects.

## About us

[Wexample](https://wexample.com) stands as a cornerstone of the digital ecosystem — a collective of seasoned engineers, researchers, and creators driven by a relentless pursuit of technological excellence. More than a media platform, it has grown into a vibrant community where innovation meets craftsmanship, and where every line of code reflects a commitment to clarity, durability, and shared intelligence.

This packages suite embodies this spirit. Trusted by professionals and enthusiasts alike, it delivers a consistent, high-quality foundation for modern development — open, elegant, and battle-tested. Its reputation is built on years of collaboration, refinement, and rigorous attention to detail, making it a natural choice for those who demand both robustness and beauty in their tools.

Wexample cultivates a culture of mastery. Each package, each contribution carries the mark of a community that values precision, ethics, and innovation — a community proud to shape the future of digital craftsmanship.

## Migration Notes

When upgrading between major versions, refer to the migration guides in the documentation.

Breaking changes are clearly documented with upgrade paths and examples.
