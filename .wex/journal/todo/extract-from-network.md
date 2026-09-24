# Charts: extract network's chart layer (series points API + yearly charts)

Opened: 2026-09-24
Updated: 2026-09-24
Author: agent:archeology

## Read this first — status of this todo

> **This is a proposal for discussion, not an order to code.** It was written by the 2026-09 network archaeology pass. Read it, then discuss it with the owner: every design choice and recommendation below is to be challenged and validated **before** any code is written. Do not start implementing on your own.
>
> - Context: `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/local/network/.wex/knowledge/readme/archeology/index.md.j2` (entry point, order between packages), then `sources.md.j2` (where the legacy code lives: archive repo, branch checkouts, GitLab issues) and the domain page linked below.
> - Pending owner decisions affecting this work are listed in `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/local/network/.wex/knowledge/readme/archeology/recap.md.j2`, section "Décisions qui t'attendent". Where this todo assumes an answer, treat it as an open question.
> - Safety: `NETWORK/local/network` runs on **production data** (real bookkeeping, real invoices in `var/`, a prod dump in `.wex/mysql/dumps/`) — read its code only, never run anything against it. Anonymize any fixture taken from network (bank exports, FEC, mails contain real names/accounts). Never copy secrets found in its history (Stripe keys, tokens, passwords, private keys).

## Goal

symfony-charts is empty. network had a small but real chart layer: a generic series point DTO served by the API, a Vue chart component fed by an API list, a yearly variant with year navigation, and app charts (monthly revenue per user, invoices errors, accounting stats). Rebuild it with the modern stack (symfony-api normalizers, js-api collections, design-system components), replacing Chartist 0.11 (unmaintained).

Knowledge: `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/local/network/.wex/knowledge/readme/archeology/base-bundle-sweep.md.j2` (row ChartLinePoint) and `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/local/network/.wex/knowledge/readme/archeology/already-extracted-check.md.j2` (front gaps). Issues: `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/gitlab/issues/130.md` (readability: "1k" instead of 1000, legend), `328.md` (monthly revenue chart looked stale; make current month/year explicit), `097.md`/`159.md` (dashboards per role).

## Prerequisites

- `wexample/symfony-api` (ApiEntity/DTO/normalizers, YearQueryOption), js-api collection mixins, `wexample/symfony-design-system` (component conventions, color palette/color-scheme axis for series colours), `wexample/symfony-money` for currency tick formatting.
- Choose a chart library (owner decision; suggestion: Chart.js 4 or ECharts; or pure SVG for line/bar only).

## Sources

- `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/trees/develop-131-fos-user/src/Entity/ChartLinePoint.php` (chart id, x, y int; `createPointsFromCollection(collection, fillPoint callable, chart)`, id = "<chart>-<x>")
- `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/trees/develop-131-fos-user/src/Entity/Traits/Manipulator/ChartLinePointEntityManipulatorTrait.php`
- Producer example: `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/trees/develop-131-fos-user/src/Service/Entity/NotOrm/AccountingMonthEntityService.php` (`getChartLinePointFor(Complete|Incomplete)BillsInMonth`) — app data stays in the app/accounting package.
- Front: `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/trees/develop-131-fos-user/src/Wex/BaseBundle/Resources/js/vue/chart.vue`, `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/trees/develop-131-fos-user/src/Wex/BaseBundle/Resources/js/vue/chart-yearly.vue` (extends list-yearly: prev/next year), `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/trees/develop-131-fos-user/src/Wex/BaseBundle/Resources/css/partials/_charts.scss`
- App charts (examples only): `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/trees/develop-131-fos-user/front/pages/entity/user/vue/chart-user-revenue.vue` (bar, fixed 0–100k axis while loading, "K €" labels), `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/trees/develop-131-fos-user/front/pages/accounting/vue/chart-invoices-errors.vue`, `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/trees/develop-131-fos-user/front/pages/accounting/vue/stats.vue`, `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/trees/develop-131-fos-user/front/partials/blocks/` (chart-revenue block)

## Steps

1. `SeriesPoint` DTO (series, x (string|int|date), y (int|float), label?) + `SeriesBuilder::fromCollection(iterable, callable)` filling missing x keys with zero (network behaviour). Unit tests.
2. `ChartDataDto` (series list, x-axis type, unit/currency) + normalizer; an abstract API controller helper returning a chart for a year/month range (YearQueryOption). Kernel test.
3. Vue component `chart` (line/bar) consuming the DTO, with loading placeholder axis, legend, compact number ticks ("1k", currency via symfony-money). Design-system demo page.
4. `chart-period` wrapper with year navigation (reuse the period-navigation component if created in DS; see already-extracted-check gaps).
5. README + demo.

## Do not

- Do not port Chartist or the options-API `extends` chain (list → list-yearly → chart-yearly).
- Do not put accounting/revenue queries in this package.

## Acceptance

- PHP unit tests for SeriesBuilder; demo page rendering a line and a bar chart from a fixture endpoint.
