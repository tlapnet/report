# Exports

Each subreport could be exported by an exporter.

## Configuration

You could define multiple independent exports.

| Attribute | Default               | Description          |
|-----------|-----------------------|----------------------|
| title     | `name`                | Button title.        |
| download  | `false`               | Force file download. |
| class     | `btn btn-success`     | Button css class.    |
| icon      | `icon-cloud-download` | Button icon.         |

## Available exporters

- JsonExporter
- CsvExporter

## Usage

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
