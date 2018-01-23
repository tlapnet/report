# Report > Exports

Každý subreport lze vyexportovat do zadaného formátu.

## Konfigurace

Lze nakonfigurovat N exportů a ke každému dodatečně upravovat atributy.

| Atribut  | Default               | Description               |
|----------|-----------------------|---------------------------|
| title    | `name`                | Nápis na tlačítku.        |
| download | `FALSE`               | Vynucení stažení souboru. |
| class    | `btn btn-success`     | Třída na tlačítku.        |
| icon     | `icon-cloud-download` | Ikona na tlačítku.        |

### Použití

```yaml
report:
    exports:
        json1:
            class: Tlapnet\Report\Export\Impl\JsonExporter

        csv1:
            class: Tlapnet\Report\Export\Impl\CsvExporter
        
        json2:
            class: Tlapnet\Report\Export\Impl\JsonExporter
            setup:
                - setOption('title', 'To json')
                - setOption('download', true)
                - setOption('class', 'btn btn-danger')
                - setOption('icon', 'icon-bug')
```
