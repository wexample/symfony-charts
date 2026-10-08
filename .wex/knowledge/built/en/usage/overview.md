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
