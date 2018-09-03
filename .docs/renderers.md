# Renderers

Render data into specific graphical shape. (Table, Chart, ...)

## Creating own renderer

```php
use Tlapnet\Report\Renderer;

class FooRenderer implements Renderer
{

    /**
     * @return mixed
     */
    public function render(Result $result) {}

}
```

## Available renderers

#### [CallbackRenderer](src/Renderers/CallbackRenderer.php)

Render data through callback.

```yaml
renderer:
    factory: Tlapnet\Report\Renderers\CallbackRenderer(callback)
```

#### [CsvRenderer](src/Renderers/CsvRenderer.php)

Render data as CSV. Row names are used as header.

```yaml
renderer:
    factory: Tlapnet\Report\Renderers\CsvRenderer
```

#### [TableRenderer](src/Renderers/TableRenderer.php)

Render data is simple table. Names of columns are used in header.

```yaml
renderer:
    factory: Tlapnet\Report\Renderers\TableRenderer
```

#### [JsonRenderer](src/Renderers/JsonRenderer.php)

Render as JSON.

```yaml
renderer:
    factory: Tlapnet\Report\Renderers\JsonRenderer
```

### From Nette bridge:

#### [ExtraTableRenderer](src/Bridges/Nette/Renderers/ExtraTable/ExtraTableRenderer.php)

Render data as table. Allows define columns, sorting, links (application and external), css classes and callbacks.

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

#### [SimpleTableRenderer](src/Bridges/Nette/Renderers/SimpleTable/SimpleTableRenderer.php)

Render data as table. Allows define and name columns.

```yaml
renderer:
    factory: Tlapnet\Report\Bridges\Nette\Renderers\SimpleTable\SimpleTableRenderer
    setup:
        - addColumn("version", "Version")
        - addColumn("module", "Module")
```

#### [VerticalTableRenderer](src/Bridges/Nette/Renderers/VerticalTable/VerticalTableRenderer.php)

Render data into vertical key => value table

- Require a `Multi<>DataSource`.
- Useful for data aggregation from multiple sources
- Also supports `preprocessors` through special syntax:
	- `key` + `row number`, e.g. `key1`.

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

### For tests:

#### [DevNullRenderer](src/Renderers/DevNullRenderer.php)

Do nothing.

```yaml
renderer:
    factory: Tlapnet\Report\Renderers\DevNullRenderer
```

#### [DummyRenderer](src/Renderers/DummyRenderer.php)

Outputs raw data.

```yaml
renderer:
    factory: Tlapnet\Report\Renderers\DummyRenderer
```
