# Report > Renderers

Renderer implementuje metodu `render`.

```php
/**
 * @param Result $report
 * @return mixed
 */
public function render(Result $report);
```

Renderer nám vykreslí data do určité grafické podoby (tabulka, graf, apod.).

## Implementace

Připravené implementace:

- [CallbackRenderer](https://git.ispa.cz/libs/report/blob/master/src/Renderers/CallbackRenderer.php)
- [CsvRenderer](https://git.ispa.cz/libs/report/blob/master/src/Renderers/CsvRenderer.php)
- [DevNullRenderer](https://git.ispa.cz/libs/report/blob/master/src/Renderers/DevNullRenderer.php) (pro testování)
- [DummyRenderer](https://git.ispa.cz/libs/report/blob/master/src/Renderers/DummyRenderer.php) (pro testování)
- [JsonRenderer](https://git.ispa.cz/libs/report/blob/master/src/Renderers/JsonRenderer.php) (pro testování)
- [TableRenderer](https://git.ispa.cz/libs/report/blob/master/src/Renderers/TableRenderer.php)

Nette bridge:

- [ExtraTableRenderer](https://git.ispa.cz/libs/report/blob/master/src/Bridges/Nette/Renderers/ExtraTable/ExtraTableRenderer.php) (obsahuje sloupečky, řazení)
- [SimpleTableRenderer](https://git.ispa.cz/libs/report/blob/master/src/Bridges/Nette/Renderers/SimpleTable/SimpleTableRenderer.php) (obsahuje sloupečky)
- [VerticalTableRenderer](https://git.ispa.cz/libs/report/blob/master/src/Bridges/Nette/Renderers/VerticalTable/VerticalTableRenderer.php) (vertikální, key => value)

### `CallbackRenderer`

> Při vykreslení zavolá callback.

```yaml
renderer:
    factory: Tlapnet\Report\Renderers\CallbackRenderer(callback)
```

### `CsvRenderer`

> Vykreslí data jako CSV. Názvy sloupců použije jako header.

```yaml
renderer:
    factory: Tlapnet\Report\Renderers\CsvRenderer
```

### `DevNullRenderer`

> Neudělá nic.

```yaml
renderer:
    factory: Tlapnet\Report\Renderers\DevNullRenderer
```

### `DummyRenderer`

> Vypíše data.

```yaml
renderer:
    factory: Tlapnet\Report\Renderers\DummyRenderer
```

### `JsonRenderer`

> Vykreslí data jako JSON.

```yaml
renderer:
    factory: Tlapnet\Report\Renderers\JsonRenderer
```

### `TableRenderer`

> Vykreslí data jako prostou tabulku. Názvy sloupců použije jako header.

```yaml
renderer:
    factory: Tlapnet\Report\Renderers\TableRenderer
```

### `SimpleTableRenderer`

> Vykreslí data jako tabulku. Lze definovat a pojmenovat sloupce.

Nejjednodušší forma tabulky.

```yaml
renderer:
    factory: Tlapnet\Report\Bridges\Nette\Renderers\SimpleTable\SimpleTableRenderer
    setup:
        - addColumn("version", "Version")
        - addColumn("module", "Module")
```

### `ExtraTableRenderer`

> Vykreslí data jako tabulku. Lze definovat a pojmenovat sloupce, řazení, odkazy apod.

ExtraTable je speciální druh tabulky, který umí řadit podle sloupce, odkazovat na presenter i mimo aplikaci, přidávat třídy a callbacky.

```yaml
renderer:
    factory: Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\ExtraTableRenderer
    setup:
        # Sorting (default value is true, so you can skip it)
        - setSortable(true)

        # Columns
        - "?->addColumn('price')->title('Price')->align('right')"(@self)
        - "?->addColumn('date', 'Date')->type('date')"(@self)
        - "?->addColumn('datetime')->type('datetime')->title('Time')"(@self)
        - "?->addColumn('count')->title('Count')->sortable(FALSE)"(@self)
```

> `@self` je syntactic suger, který je potřeba.


```yaml
renderer:
    factory: Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\ExtraTableRenderer
    setup:
        - "?->addColumn('foo')->title('Foo')->url('http://tlapnet.cz')"(@self)
        - "?->addColumn('bar1')->title('Bar1')->link('Report:List:default')"(@self)
        - "?->addColumn('bar2')->title('Bar2')->link('Report:List:default', ['args1' => '#foo'])"(@self)
        - "?->addAction('baz1')->title('Baz1')->label('ACTION')->link('Report:List:default', ['args1' => '#foo'])"(@self)
        - "?->addBlank('blank')->callback([?,?])"(@self, @report.renderer.callback, process )
```

> `@self` je syntactic suger, který je potřeba.

### `VerticalTableRenderer`

> Vykreslí data jako key => value tabulku.

Vertikální, též zvaná `key` => `value`, tabulka potřebuje speciální zdroj dat `Multi<>DataSource`.

Tato tabulka je vhodná pro agregaci dat a výpis například ruzných statistik. Vyžaduje definici více SQL
dotazů. 

Existuje i speciální zápis pro `preprocessors`, který je potřebovat definovat jako `key` + `čislo řádku`, např. `key1`.

```yaml
datasource:
    factory: Tlapnet\Report\Bridges\Dibi\DataSources\MultiDibiWrapperDataSource
    setup:
        - addRow('Migration ID #1', "SELECT id FROM migration LIMIT 1")
        - addRow('Migration ID #2', "SELECT id FROM migration LIMIT 1 OFFSET 1")
        - addRow('Migration ID #3', "SELECT id FROM migration LIMIT 1 OFFSET 2")

preprocessors:
    # Global preprocessors (for each row)
    key:
        - Tlapnet\Report\Preprocessor\Impl\AppendPreprocessor(' GLOBAL')
    value:
        - Tlapnet\Report\Preprocessor\Impl\AppendPreprocessor('10')

    # For 1st row
    key0:
        - Tlapnet\Report\Preprocessor\Impl\AppendPreprocessor(' SPECIFIC-KEY')
    value0:
        - Tlapnet\Report\Preprocessor\Impl\CurrencyPreprocessor('CZK')

    # For 2nd row
    value1:
        - Tlapnet\Report\Preprocessor\Impl\CurrencyPreprocessor('E')

renderer:
    factory: Tlapnet\Report\Bridges\Nette\Renderers\VerticalTable\VerticalTableRenderer

    # It is not necessary to setup VerticalTableRenderer
    setup:
        # <th> labels
        - setColumns("Key", "Label")
```
