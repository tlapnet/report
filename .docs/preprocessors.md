# Preprocessors

Preprocessor is defined for specific result column and applies on each record.

## Creating own preprocessor

```php
use Tlapnet\Report\Preprocessor\Preprocessor;

class FooPreprocessor extends
{

    /**
     * @param mixed $data
     * @return mixed
     */
    public function preprocess($data);

}
```

## Usage

Define preprocessors for column referenced by its title.

```yaml
preprocessors:
    <columnTitle>:
        - Tlapnet\Report\Preprocessor\Impl\AppendPreprocessor('$')
        - Tlapnet\Report\Preprocessor\Impl\PrependPreprocessor('@')
```

## Available preprocessors

#### [`PrependPreprocessor`](src/Model/Preprocessor/Impl/PrependPreprocessor.php)

Adds string to start.

```yaml
preprocessors:
    count:
        - Tlapnet\Report\Preprocessor\Impl\PrependPreprocessor('@')
```

#### [`AppendPreprocessor`](src/Model/Preprocessor/Impl/AppendPreprocessor.php)

Adds string to end.

```yaml
preprocessors:
    count:
        - Tlapnet\Report\Preprocessor\Impl\AppendPreprocessor('$')
```

#### [`BooleanPreprocessor`](src/Model/Preprocessor/Impl/BooleanPreprocessor.php)

Displays Yes / No.

```yaml
preprocessors:
    paid:
        - Tlapnet\Report\Preprocessor\Impl\BooleanPreprocessor('Yes', 'No')
```

#### [`CurrencyPreprocessor`](src/Model/Preprocessor/Impl/CurrencyPreprocessor.php)

Formats currency.

```yaml
preprocessors:
    price:
        - Tlapnet\Report\Preprocessor\Impl\CurrencyPreprocessor('CZK')
```

#### [`DatePreprocessor`](src/Model/Preprocessor/Impl/DatePreprocessor.php)

Formats datetime.

```yaml
preprocessors:
    date:
        - Tlapnet\Report\Preprocessor\Impl\DatePreprocessor('Y/m/d')
```


#### [`EmailPreprocessor`](src/Bridges/Nette/Preprocessors/EmailPreprocessor.php)

Formats value as email with mailto: link.

```yaml
preprocessors:
    email:
        - Tlapnet\Report\Bridges\Nette\Preprocessors\EmailPreprocessor
```

#### [`MathRatioPreprocessor`](src/Model/Preprocessor/Impl/MathRatioPreprocessor.php)

Shows number in ratio. E.g. 0.3 is shown as 30 / 100

```yaml
preprocessors:
    total:
        - Tlapnet\Report\Preprocessor\Impl\MathRatioPreprocessor(100)
```

#### [`NumberPreprocessor`](src/Model/Preprocessor/Impl/NumberPreprocessor.php)

Formats number.

```yaml
preprocessors:
    size:
        class: Tlapnet\Report\Preprocessor\Impl\NumberPreprocessor
        setup:
          - setDecimalPoint(',')
          - setThousandsPoint('-')
```

### For tests

#### [`DevNullPreprocessor`](src/Model/Preprocessor/Impl/DevNullPreprocessor.php)

Do nothing.
