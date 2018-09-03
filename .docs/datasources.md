# DataSources

Returns `Result`, which contains all data needed for render.

## Creating own data source

```php
use Tlapnet\Report\DataSources\DataSource;

class FooDataSource implements DataSource
{

    public function compile(Parameters $parameters): Resultable {}

}
```

## Available data sources

- [ArrayDataSource](src/DataSources/ArrayDataSource.php)
- [CachedDataSource](src/DataSources/CachedDataSource.php)
- [CallbackDataSource](src/DataSources/CallbackDataSource.php)
- [PdoDataSource](src/DataSources/PdoDataSource.php)

### Nette Database

- [NetteDatabaseDataSource](src/Bridges/Nette/Database/DataSources/NetteDatabaseDataSource.php)
- [NetteDatabaseWrapperDataSource](src/Bridges/Nette/Database/DataSources/NetteDatabaseWrapperDataSource.php) (wraps nette database connection)
- [MultiNetteDatabaseWrapperDataSource](src/Bridges/Nette/Database/DataSources/MultiNetteDatabaseWrapperDataSource.php) (wraps nette database connection + contains more sql queries)

### Dibi

- [DibiDataSource](src/Bridges/Dibi/DataSources/DibiDataSource.php)
- [DibiWrapperDataSource](src/Bridges/Dibi/DataSources/DibiWrapperDataSource.php) (wraps dibi connection)
- [MultiDibiWrapperDataSource](src/Bridges/Dibi/DataSources/MultiDibiWrapperDataSource.php) (wraps dibi connection + contains more sql queries)

### Testing

- [RandomDataSource](src/Bridges/Nette/DataSources/RandomDataSource.php) (require nette/utils)
- [DevNullDataSource](src/DataSources/DevNullDataSource.php)
- [DummyDataSource](src/DataSources/DummyDataSource.php)

### Database

Database data sources (nette and dibi) contains special methods:

- `setSql($sql)`
- `setDefaultSql($sql)`

Default sql query is used if no parameters are available. (If form is not defined or filled)

#### Multi-Database

Database multi-datasources are used e.g. for vertical tables.
They support multiple database queries.

- `addSql($title, $sql)`

Returns `MultiResult` which contains `Result`s from individual queries.

#### Random

Generate random data for tests.

```yaml
datasource:
    factory: Tlapnet\Report\Bridges\Nette\DataSource\RandomDataSource
    setup:
        - addRange(price, 1, 100000)
        - addDate(date)
        - addDateTime(datetime)
        - addRange(count, 1, 100000)
        - setRows(40)
```

## Cache

Data sources support simple caching

```yaml
datasource:
    factory: @report.datasource.dibi.db.wrapper
    tags: [report.cache: [key: table1, expiration: +1 day]]
```

Supports `key` and `expiration`.
