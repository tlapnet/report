# Nette integration

```yaml
extensions:
    report: Tlapnet\Report\Bridges\Nette\DI\ReportExtension
```

## Configuration

It is good to define some common parameters like database connection.

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

## Example configuration

```yaml
report:
    # Individual reports:
    # Tip: It is ideal to have 1 report per file.
    files:
        - %module.sample.moduleDir%/config/reports/files/report.neon

    # Folder which contains reports in .neon format
    folders:
        - %module.sample.moduleDir%/config/reports

    # Common services
    definitions:
        # Nette Database
        datasource.nette.db:
            class: Tlapnet\Report\Bridges\Nette\Database\DataSources\NetteDatabaseDataSource(%report.db%)
            setup:
                - setTracyPanel(%debugMode%)

        # If is Nette\Database\Connection already registered then use NetteDatabaseWrapperDataSource
        datasource.nette.db.wrapper:
            class: Tlapnet\Report\Bridges\Nette\Database\DataSources\NetteDatabaseWrapperDataSource
            setup:
                - setTracyPanel(%debugMode%)

        # Dibi
        datasource.dibi.db:
            class: Tlapnet\Report\Bridges\Dibi\DataSources\DibiDataSource(%report.db%)
            setup:
                - setTracyPanel(%debugMode%)

        # If is DibiConnection already registered then use DibiWrapperDataSource
        datasource.dibi.db.wrapper:
            class: Tlapnet\Report\Bridges\Dibi\DataSources\DibiWrapperDataSource
            setup:
                - setTracyPanel(%debugMode%)

        # PDO driver
        datasource.pdo.db:
            class: Tlapnet\Report\DataSources\PdoDataSource(%report.db%)

    # Registered groups
    groups:
        tables: Tables
        charts: Charts

    # Individual reports
    # Tip: It is ideal to have 1 report per file.
    reports:
        <report-name-1>:
        <report-name-2>:
        <report-name-N>:
```

In main configuration file is recommended to register only:

- files and folders with reports
- shared services (e.g. database)
- groups
- individual reports (exceptionally)

```yaml
report:
    <report-name-1>:
        groups: [<group-name-1>, <group-name-2>, <group-name-N>]

        metadata:
            menu: Title in menu # optional, uses report name if not defined
            title: Report heading # optional
            description: Long description # optional

        subreport:
            # For small parts like one chart, one table, ...
            # Tip: Use key 'subreports' if you have multiple subreports

            metadata: (...)
            params: (...)
            preprocessors: (...)
            datasource: (...)
            renderer: (...)

        subreports:
            <id-1-subreport>:
                metadata: (optional)
                    title: Subreport heading # optional
                    description: Short description # optional

                params: Array of parameters # optional, ParametersBuilder could be used

                preprocessors: #optional
                    <column-name>:
                        - preprocessor-type
                        # Type of preprocessor which is applied on column
                        # Tip: You could define multiple preprocessors

                datasource: @report.datasource.nette.db.wrapper

                renderer: Tlapnet\Report\Renderers\CsvRenderer

            <id-2-subreport>:
            <id-3-subreport>:
            <id-N-subreport>:

    <report-name-2>:
    <report-name-N>:
```

Testing configuration example, with all options:

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
