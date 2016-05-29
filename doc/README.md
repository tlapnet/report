# Report

## Instalace

```
$ compose require tlapnet/report
```

## Základní prvky

- Entities
- DataSources
- Preprocessors
- Renderers
- Services

## Classdiagram

![Hierarchy](misc/hierarchy.png)

## Entities

### Group

Obsahuje jednotlivé reporty.

| Property   | Typ    | Popisek       |
|------------|--------|---------------|
| `$id`      | string | ID skupiny    |
| `$name`    | string | Název skupiny |
| `$reports` | array  | Pole reportů  |

### Report

Základní stavební kámen. Obsahuje jednotlivé subreporty, které vykreslují 
grafy, tabulky apod.

| Property       | Typ       | Popisek           | 
|----------------|-----------|-------------------|
| `$id`          | string    | ID reportu        |
| `$metadata`    | object    | Metadata          |
| `$subreports`  | array     | Pole reportů      |

### Subreport

Konečná komponenta obsahující data source, renderer a preprocessors.
Stará se o načtení, převedení a vykreslení dat formou delagace.

- Načtení => přes data source 
- Převedení => přes preprocessors 
- Vykreslení => přes renderer 

### Class diagram

![Subreport - class diagram](misc/subreport-classdiagram.png)

### Životní cyklus

![Subreport - lifecycle](misc/subreport-lifecycle.png)

## DataSources

DataSource implementuje metodu `compile`.

```php
/**
 * @param Parameters $parameters
 * @return Resultable
 */
public function compile(Parameters $parameters);
```

Vrací nám objekt `Result`, který v sobě má veškerá data potřebná k vykreslení.

Připravené implementace:

- ArrayDataSource
- CallbackDataSource
- DevNullSource (pro testování)
- DummySource (pro testování)
- PdoDataSource
- RestSource (@TODO)

Nette bride:

- NetteDatabaseDataSource
- NetteDatabaseWrapperDataSource (obaluje nette database connection)

Dibi bridge:

- DibiDataSource
- DibiWrapperDataSource (obaluje dibi connection)

## Preprocessors

Preprocessor implementuje metodu `preprocess`.

```php
/**
 * @param mixed $data
 * @return mixed
 */
public function preprocess($data);
```

Preprocessor se nasazuje na jednotlivý sloupeček dat/resultu. A aplikuje se
na všechny výstkyty.


Připravené implementace:

- AppendPreprocessor (přidá nakonec)
- CurrencyPreprocessor (formátuje měnu)
- DatePreprocessor (formátuje čas)
- DevNullPreprocessor (pro testování)
- NumberPreprocessor (formátuje číslo)
- PrependPreprocessor (přidá na začátek)

## Renderers

Renderer implementuje metodu `render`.

```php
/**
 * @param Result $report
 * @return mixed
 */
public function render(Result $report);
```

Renderer nám vykreslí data do určité grafické podoby (tabulka, graf, apod.).

Připravené implementace:

- CallbackRenderer
- CsvRenderer
- DevNullRenderer (pro testování)
- DummyRenderer (pro testování)
- JsonRenderer
- TableRenderer

Nette bridge:

- TableRenderer (sloupečky, řazení)
- SimpleTableRenderer (sloupečky)

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

## Nette

### Registrace

```yaml
extensions:
    report: Tlapnet\Report\Bridges\Nette\DI\ReportExtension
```

```yaml
report:
    # konfigurace..
```

### Konfigurace

Ukázková root konfigurace.

```yaml
# Spolecne parametery
parameters:
	report:
		db:
			driver: mysql
			host: db
			database: tlapnet
			user: root
			password: root

report:
	# Jednotlive reporty
	# Tip: idealni je mit 1 report v 1 souboru, prot lepsi prenositelnost a prehlednost
	files:
		- %module.sample.moduleDir%/config/reports/files/report.neon

	# Slozky, kde se hledaji *.neon soubory
	folders:
		- %module.sample.moduleDir%/config/reports

	# Spolecne sluzby
	definitions:
		datasource.nette.db:
			class: Tlapnet\Report\Bridges\Nette\Database\DataSources\NetteDatabaseDataSource(%report.db%)
			setup:
				- setTracyPanel(%debugMode%)

		datasource.pdo.db:
			class: Tlapnet\Report\DataSources\PdoDataSource(%report.db%)

	# Zaregistrovane skupiny
	groups:
		tables: Tables
		charts: Charts

	# Jednotlive reporty
	# Tip: je lepsi mit reporty v jednotlivych souborech / slozkach
	reports:
		<report-name-1>:
		<report-name-2>:
		<report-name-N>:
```

V hlavním konfiguračním souboru je doporučeno registrovat pouze:

- soubory s přislušnými reporty
- rekurzivně sledované složky
- sdílené služby (například pro databáze)
- skupiny
- jednotlivé reporty (jen výjímečně)

```yaml
<report-name-1>:
	groups: [<group-name-1>, <group-name-2>, <group-name-N>]

	metadata:
		menu: Pojmenovani v menu (doporuceny, nepovinny, vezme se automaticky z nazvu reportu)
        title: H1 nadpis reportu (doporuceny, nepovinny)
        description: Dlouhy popisek (nepovinny)
        
    preprocessors: (nepovinne)
        <nazev-sloupecku>:
            - <type>
            # Typ preprocesseru co se aplikuje na dany sloupecek
            # Muze jich byt klidne vice, poradi je dle definice

    subreport:
        # Lze vyuzit pokud report ma jenom jeden graf, tabulku ci podobne
        # Tip: pokud ma report vice jednotlivych subreportu, pouzijte klic subreports
        
        metadata: (nepovinne)
        datasource: Typ datasource (definice jako sluzby v nette)
        renderer: Typ rendereru (definice jako sluzby v nette)
        
	subreports:
		<id-1-subreportu>:
		    metadata: (nepovinne)
		        title: H2 nadpis subreportu (nepovinny)
		        description: Kratky popisek (nepovinny)
		        
			datasource: Typ datasource (definice jako sluzby v nette)
			
			renderer: Typ rendereru (definice jako sluzby v nette)
			
		<id-2-subreportu>:
		<id-3-subreportu>:
		<id-N-subreportu>:

<report-name-2>:
<report-name-N>:
```

Detailnější ukázky jsou v sample modulu u sample projektu.
