# Graphiques par période

Opened: 2026-10-09
Author: agent:main

Reste de `done/extract-from-network.md` (network : graphiques navigables par année).

- Des options de requête en plus du nom du graphique (année, période) : symfony-api refuse aujourd'hui tout paramètre non déclaré ; les déclarer par provider.
- Un composant `chart-period` (navigation par année ou mois autour d'un `chart()`), qui attend le composant de navigation par période du design system (`symfony-design-system/.wex/journal/todo/extract-from-network.md`, étape 6).
- Un test kernel de l'endpoint `/api/chart-point/list` (seul le helper est testé).
