# Report

## Instalace

```
compose require tlapnet/report
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

![Hierarchy](misc/hierarchy.pnd)

## Entities

### Group

Obsahuje jednotlivé reporty.

|Property   | Typ       | Popisek           | 
|-------------------------------------------|
|`$id`      | string    | ID skupiny        |
|`$name`    | string    | Název skupiny     |
|`$reports` | array     | Pole reportů      |

### Report

Základní stavební kámen. Obsahuje jednotlivé subreporty, které vykreslují 
grafy, tabulky apod.

|Property       | Typ       | Popisek           | 
|-----------------------------------------------|
|`$id`          | string    | ID reportu        |
|`$metadata`    | object    | Metadata          |
|`$subreports`  | array     | Pole reportů      |

### Subreport

### Class diagram

![Subreport - class diagram](misc/subreport-classdiagram.png)

### Životní cyklus

![Subreport - lifecycle](misc/subreport-lifecycle.pnd)

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

Ukázková konfigurace.

```yaml
report:
	# List of individual reports
	files:
		- %module.sample.moduleDir%/config/reports/files/report.neon

	# List of folders for recursively loading of *.neon
	folders:
		- %module.sample.moduleDir%/config/reports

	# List of shared services for reports
	definitions:
		datasource.nette.db:
			class: Tlapnet\Report\Bridges\Nette\Database\DataSources\NetteDatabaseDataSource(%report.db%)
			setup:
				- setTracyPanel(%debugMode%)

		datasource.pdo.db:
			class: Tlapnet\Report\DataSources\PdoDataSource(%report.db%)

	# List of groups
	groups:
		tables: Tables
		charts: Charts

	# List of report groups
	reports:
		# Use can defined reports here..
```

V hlavním konfiguračním souboru je doporučeno registrovat pouze:

- soubory s přislušnými reporty
- rekurzivně sledované složky
- sdílené služby (například pro databáze)
- skupiny
- jednotlivé reporty (jen výjímečně)

```yaml
report-name:
	groups: [group-name1, group-name2]

	metadata:
		menu: Pojmenovani v menu (doporuceny, nepovinny, vezme se automaticky z nazvu reportu)
        title: H1 nadpis reportu (doporuceny, nepovinny)
        description: Dlouhy popisek (nepovinny)
        
    preprocessors: (nepovinne)
        <nazev-sloupecku>:
            - Typ preprocesseru co se aplikuje na dany sloupecek
            - Muze jich byt klidne vice, poradi je dle definice

    subreport:
        # Lze vyuzit pokud report ma jenom jeden graf, tabulku ci podobne
        # Pokud ma report vice jednotlivych subreportu, pouzijte klic subreports
        
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

```

Detailnější ukázky jsou v sample modulu u sample projektu.
