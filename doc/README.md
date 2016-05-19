# Report

## Instalace

```
$ compose require tlapnet/report
```

## Základní prvky

**Entities**
- Group
- Report
- Subreport
- Result

**DataSources**
- ArrayDataSource
- CallbackDataSource
- DevNullSource
- DummySource
- PdoDataSource
- RestSource

**Preprocessors**
- AppendPreprocessor
- CurrencyPreprocessor
- DatePreprocessor
- DevNullPreprocessor
- NumberPreprocessor
- PrependPreprocessor

**Renderers**
- CallbackRenderer
- CsvRenderer
- DevNullRenderer
- DummyRenderer
- JsonRenderer
- TableRenderer

**Sergices**
- ReportManager
- ReportService

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
|------------------------------------------------|
| `$id`          | string    | ID reportu        |
| `$metadata`    | object    | Metadata          |
| `$subreports`  | array     | Pole reportů      |

### Subreport

### Class diagram

![Subreport - class diagram](misc/subreport-classdiagram.png)

### Životní cyklus

![Subreport - lifecycle](misc/subreport-lifecycle.png)

## DataSources

## Preprocessors

## Renderers

## Services

### ReportManager

### ReportService

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
