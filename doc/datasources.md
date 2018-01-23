# Report > DataSources

DataSource implementuje metodu `compile`.

```php
/**
 * @param Parameters $parameters
 * @return Resultable
 */
public function compile(Parameters $parameters);
```

Vrací nám objekt `Result`, který v sobě má veškerá data potřebná k vykreslení.

## Implementace

Připravené implementace:

- [ArrayDataSource](https://git.ispa.cz/libs/report/blob/master/src/DataSources/ArrayDataSource.php)
- [CachedDataSource](https://git.ispa.cz/libs/report/blob/master/src/DataSources/CachedDataSource.php)
- [CallbackDataSource](https://git.ispa.cz/libs/report/blob/master/src/DataSources/CallbackDataSource.php)
- [DevNullDataSource](https://git.ispa.cz/libs/report/blob/master/src/DataSources/DevNullDataSource.php) (pro testování)
- [DummyDataSource](https://git.ispa.cz/libs/report/blob/master/src/DataSources/DummyDataSource.php) (pro testování)
- [PdoDataSource](https://git.ispa.cz/libs/report/blob/master/src/DataSources/PdoDataSource.php)
- [RestDataSource](https://git.ispa.cz/libs/report/blob/master/src/DataSources/RestDataSource.php) (@TODO)

Nette bridde:

- [RandomDataSource](https://git.ispa.cz/libs/report/blob/master/src/Bridges/Nette/DataSources/RandomDataSource.php) (generátor dat)
- [NetteDatabaseDataSource](https://git.ispa.cz/libs/report/blob/master/src/Bridges/Nette/Database/DataSources/NetteDatabaseDataSource.php)
- [NetteDatabaseWrapperDataSource](https://git.ispa.cz/libs/report/blob/master/src/Bridges/Nette/Database/DataSources/NetteDatabaseWrapperDataSource.php) (obaluje nette database connection)
- [MultiNetteDatabaseWrapperDataSource](https://git.ispa.cz/libs/report/blob/master/src/Bridges/Nette/Database/DataSources/MultiNetteDatabaseWrapperDataSource.php) (obaluje nette database connection + obsahuje více sql dotazů)

Dibi bridge:

- [DibiDataSource](https://git.ispa.cz/libs/report/blob/master/src/Bridges/Dibi/DataSources/DibiDataSource.php)
- [DibiWrapperDataSource](https://git.ispa.cz/libs/report/blob/master/src/Bridges/Dibi/DataSources/DibiWrapperDataSource.php) (obaluje dibi connection)
- [MultiDibiWrapperDataSource](https://git.ispa.cz/libs/report/blob/master/src/Bridges/Dibi/DataSources/MultiDibiWrapperDataSource.php) (obaluje nette dibi connection + obsahuje více sql dotazů)

## Database

Speciální případ datasource jsou databáze. 

Disponují speciálními metodami:

- `setSql($sql)`
- `setDefaultSql($sql)`

Default sql dotaz se provede pokud nejsou k dispozici žádné parametry. Buď neexistuje formulář nebo formulář nebyl vyplněn, tzv. výchozí stav.

## Multi-Database

Další specifický případ jsou multi-datasources. Používají se např. pro vertikální tabulky. Jejich hlavní přidaná hodnota je, že akceptují více SQL dotazů.

- `addSql($title, $sql)`

Těchto n-sql dotazů se vykoná při kompilaci jak jsme zvyklí. Výsledkem je `MultiResult`, který akceptuje více `Result` objektů a umí s nimi pracovat jako by to bylo jednoduché pole. 

## Random

RandomDataSource je speciální data source, který nám umožní vygenerovat jakákoli potřebná data k testovacím účelům.

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

DataSource podporuje jednoduché caching systém. 

```yaml
datasource:
    factory: @report.datasource.dibi.db.wrapper
    tags: [report.cache: [key: table1, expiration: +1 day]]
```

Cache se zapne přes tag `report.cache`, který se přidá k datasource definici.

Aktuálně lze definovat `key` a `expiration`.
