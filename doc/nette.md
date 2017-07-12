# Report > Nette

## Registrace

```yaml
extensions:
	report: Tlapnet\Report\Bridges\Nette\DI\ReportExtension
```

```yaml
report:
	# konfigurace...
```

## Konfigurace

Je dobré definovat nějaké společné parametry jako připojení do databáze.

```yaml
parameters:
	report:
		db:
			driver: mysql
			host: db
			database: tlapnet
			user: root
			password: root
```

Ukázková root konfigurace.

```yaml
report:
	# Jednotlive reporty
	# Tip: idealni je mit 1 report v 1 souboru, proto lepsi prenositelnost a prehlednost
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
				
		# Pokud v aplikaci mate uz zaregistrovany Nette\Database\Connection, staci pouzit tento wrapper
		datasource.nette.db.wrapper:
			class: Tlapnet\Report\Bridges\Nette\Database\DataSources\NetteDatabaseWrapperDataSource
			setup:
				- setTracyPanel(%debugMode%)

		datasource.dibi.db:
			class: Tlapnet\Report\Bridges\Dibi\DataSources\DibiDataSource(%report.db%)
			setup:
				- setTracyPanel(%debugMode%)

		# Pokud v aplikaci mate uz zaregistrovany DibiConnection, staci pouzit tento wrapper
		datasource.dibi.db.wrapper:
			class: Tlapnet\Report\Bridges\Dibi\DataSources\DibiWrapperDataSource
			setup:
				- setTracyPanel(%debugMode%)

		# Ciste reseni pres PDO
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
report:
	<report-name-1>:
		groups: [<group-name-1>, <group-name-2>, <group-name-N>]
	
		metadata:
			menu: Pojmenovani v menu (doporuceny, nepovinny, vezme se automaticky z nazvu reportu)
			title: H1 nadpis reportu (doporuceny, nepovinny)
			description: Dlouhy popisek (nepovinny)

		subreport:
			# Lze vyuzit pokud report ma jenom jeden graf, tabulku ci podobne
			# Tip: pokud ma report vice jednotlivych subreportu, pouzijte klic subreports

			metadata: (...)
			params: (...)
			preprocessors: (...)
			datasource: (...)
			renderer: (...)

		subreports:
			<id-1-subreportu>:
				metadata: (nepovinne)
					title: H2 nadpis subreportu (nepovinny)
					description: Kratky popisek (nepovinny)
	
				params: Pole parametru (lze vyuzit napriklad ParametersBuilder) (nepovinne)
					
				preprocessors: (nepovinne)
					<nazev-sloupecku>:
						- type
						# Typ preprocesseru co se aplikuje na dany sloupecek
						# Muze jich byt klidne vice, poradi je dle definice

				datasource: Typ datasource (definice jako sluzby v nette)

				renderer: Typ rendereru (definice jako sluzby v nette)

			<id-2-subreportu>:
			<id-3-subreportu>:
			<id-N-subreportu>:

	<report-name-2>:
	<report-name-N>:
```

Detailnější ukázky jsou v sample modulu u sample projektu.

Hlavní konfigurační soubor [report.neon](https://git.tlapnet.cz/modules/sample-module/blob/master/config/report.neon).
Jednotlivé [reporty na ukázku](https://git.tlapnet.cz/modules/sample-module/tree/master/config/reports/).

Testovací ukázková konfigurace, se všemi možnostmi.

```yaml
all1one:
	groups: [custom]

	metadata:
		menu: ALL 1 ONE
		title: All configuration at one place
		description: This is all in one report!

	subreports:
		1:
			metadata:
				title: Special title
				description: Custom description just for this subreport

			params:
				builder:
					- addSelect({
						name: dump
						title: Dump input
						options: {
							placeholder: Do not fill me...
						}
					})

					- addSelect({
						name: username
						title: Maintainer
						useKeys: on
						items: @report.fetcher.dibi::create('SELECT * FROM `login_user`')::fetchPairs('id', 'name')
					})

			datasource:
				factory: @report.datasource.nette.db
				setup:
					- setDefaultSql('SELECT * FROM articles')
					- setSql('SELECT * FROM articles WHERE author = {username}')

			renderer:
				factory: Tlapnet\Report\Bridges\Chart\Renderers\Donut\DonutChartRenderer
				setup:
					- addSegment("title", "username")
					- addSegment("value", "tasks")
```
