# Report > Model

## Group

Obsahuje jednotlivé reporty.

| Property   | Typ    | Popisek       |
|------------|--------|---------------|
| `$id`      | string | ID skupiny    |
| `$name`    | string | Název skupiny |
| `$reports` | array  | Pole reportů  |

## Report

Základní stavební kámen. Obsahuje jednotlivé subreporty, které vykreslují 
grafy, tabulky apod.

| Property       | Typ       | Popisek           | 
|----------------|-----------|-------------------|
| `$id`          | string    | ID reportu        |
| `$metadata`    | object    | Metadata          |
| `$subreports`  | array     | Pole reportů      |

## Subreport

Konečná komponenta obsahující data source, renderer a preprocessors.
Stará se o načtení, převedení a vykreslení dat formou delagace.

- Načtení => přes data source 
- Převedení => přes preprocessors 
- Vykreslení => přes renderer 

## Class diagram

![Subreport - class diagram](misc/subreport-classdiagram.png)

## Životní cyklus

![Subreport - lifecycle](misc/subreport-lifecycle.png)

## Services

Knihovna je především tvořena jednotlivými entitami, ale jsou tu i 2 služby pro
pohodlnější praci v presenterech a komponentách.

### ReportManager

Podporuje uplně základní operace se skupinami.

- `addGroup(Group $group)`
- `hasGroup($gid)`
- `getGroup($gid)`
- `getGroups()`
- `addGroupless(Report $report)`
- `getGrouppless()`

Používá se především v `ReportExtension`, kde do ní Nette\DI\Container automaticky
přidává skupiny a reporty.

### ReportService

Obaluje `ReportManager` pro manipulaci s reporty.

- `getResult($rid)`
- `getGroups()`
- `getGroupless()`
