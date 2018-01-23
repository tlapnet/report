# Report > Preprocessors

Preprocessor implementuje metodu `preprocess`.

```php
/**
 * @param mixed $data
 * @return mixed
 */
public function preprocess($data);
```

Preprocessor se nasazuje na jednotlivý sloupeček dat/resultu. A aplikuje se na všechny výstkyty.

## Použití

Do sekce `preprocessors` se uvede `<key>`, který odpovídá sloupci a pole preprocesorů. Jednotlivé preprocesory 
lze zřetězit za sebe.

```yaml
preprocessors:
    <key>:
        - Tlapnet\Report\Preprocessor\Impl\AppendPreprocessor('$')
        - Tlapnet\Report\Preprocessor\Impl\PrependPreprocessor('@')
```

## Implementace

### [`AppendPreprocessor`](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/AppendPreprocessor.php)

> Přidá na konec.

```yaml
preprocessors:
    count:
        - Tlapnet\Report\Preprocessor\Impl\AppendPreprocessor('$')
```

### [`BooleanPreprocessor`](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/BooleanPreprocessor.php) 

> Zobrazí ano / ne.s

```yaml
preprocessors:
    paid:
        - Tlapnet\Report\Preprocessor\Impl\BooleanPreprocessor('Ano', 'Ne')
```

### [`CurrencyPreprocessor`](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/CurrencyPreprocessor.php)

> Formátuje měnu.

```yaml
preprocessors:
    price:
            - Tlapnet\Report\Preprocessor\Impl\CurrencyPreprocessor('CZK')
```

### [`DatePreprocessor`](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/DatePreprocessor.php)

> Formátuje čas.

```yaml
preprocessors:
    date:
        - Tlapnet\Report\Preprocessor\Impl\DatePreprocessor('Y/m/d')
```

### [`DevNullPreprocessor`](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/DevNullPreprocessor.php)

> Testovací účely.

### [`EmailPreprocessor`](https://git.tlapnet.cz/libs/report/blob/master/src/Bridges/Nette/Preprocessors/EmailPreprocessor.php)

> Naformátuje text jako email s odkazem.

```yaml
preprocessors:
    email:
        - Tlapnet\Report\Bridges\Nette\Preprocessors\EmailPreprocessor
```

### [`MathRatioPreprocessor`](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/MathRatioPreprocessor.php)

> Zobrazuje poměr. Např. 0.3 se zobrazí jako 30 / 100.

```yaml
preprocessors:
    total:
        - Tlapnet\Report\Preprocessor\Impl\MathRatioPreprocessor(100)
```

### [`NumberPreprocessor`](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/NumberPreprocessor.php)

> Formátuje číslo.

```yaml
preprocessors:
    size:
        class: Tlapnet\Report\Preprocessor\Impl\NumberPreprocessor
        setup:
          - setDecimalPoint(',')
          - setThousandsPoint('-')
```

### [`PrependPreprocessor`](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/PrependPreprocessor.php)

> Přidá na začátek.

```yaml
preprocessors:
    count:
        - Tlapnet\Report\Preprocessor\Impl\PrependPreprocessor('@')
```

