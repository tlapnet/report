# Fetcher

Fetcher is helper for loading data into forms.

## API

- `fetch(): mixed`
- `fetchSingle(?string $column = null): mixed`
- `fetchAll(?int $offset = null, ?int $limit = null): mixed[]`
- `fetchPairs(?string $key = null, ?string $value = null): mixed[]`

## Configuration

Register fetcher factory under **report.definitions**.

```yaml
report:
  definitions:
    fetcher.cached:
        class: Tlapnet\Report\Fetcher\CachedFetcherFactory(@report.fetcher.dibi)
        autowired: false

    fetcher.dibi:
        class: Tlapnet\Report\Bridges\Dibi\Fetcher\DibiFetcherFactory
        autowired: off
```

## Available fetchers

- CachedFetcher
- NetteDatabaseFetcher
- DibiFetcher

## Usage

Call `fetcher:fetchPairs` to get list of items for form select.

```yaml
example:
    subreport:
        params:
            builder:
                - addSelect({
                    name: country
                    title: Country
                    items: @report.fetcher.dibi::create('SELECT * FROM country')::fetchPairs('id', 'name')
                })

                - addSelect({
                    name: country
                    title: Country cache
                    items: @report.fetcher.cached::set(all1one,+1 day)::create('SELECT * FROM country')::fetchPairs('id', 'name')
                })
```
