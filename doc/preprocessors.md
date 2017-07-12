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

## Implementace

Připravené implementace:

- [AppendPreprocessor](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/AppendPreprocessor.php) (přidá nakonec)
- [BooleanPreprocessor](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/BooleanPreprocessor.php) (zobrazuje Ano/Ne)
- [CurrencyPreprocessor](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/CurrencyPreprocessor.php) (formátuje měnu)
- [DatePreprocessor](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/DatePreprocessor.php) (formátuje čas)
- [DevNullPreprocessor](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/DevNullPreprocessor.php) (pro testování)
- [NumberPreprocessor](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/NumberPreprocessor.php) (formátuje číslo)
- [PrependPreprocessor](https://git.tlapnet.cz/libs/report/blob/master/src/Model/Preprocessor/Impl/PrependPreprocessor.php) (přidá na začátek)

```yaml
preprocessors:
    price:
        - Tlapnet\Report\Preprocessor\Impl\CurrencyPreprocessor('CZK')
    date:
        - Tlapnet\Report\Preprocessor\Impl\DatePreprocessor('Y/m/d')
    paid:
        - Tlapnet\Report\Preprocessor\Impl\BooleanPreprocessor('Ano', 'Ne
    size:
        class: Tlapnet\Report\Preprocessor\Impl\NumberPreprocessor('Ano', 'Ne')
        setup:
          - setDecimalPoint(',')
          - setThousandsPoint('-')
    count:
        - Tlapnet\Report\Preprocessor\Impl\PrependPreprocessor('PREPEND-')
        - Tlapnet\Report\Preprocessor\Impl\AppendPreprocessor('-APPEND')
```
