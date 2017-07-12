# Report > Exports

Každý subreport lze vyexportovat do zadaného formátu.

## Konfigurace

Lze konfigurovat N exportů, kde každému můžeme dodatečně dodefinovat atributy.

Atributy:

- title
- download
- class
- icon

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
