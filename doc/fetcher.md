# Report > Fetcher

Fetcher je pomocná třída pro načítání dat do formulářů.

## API

- `fetch`
- `fetchSingle($column)`
- `fetchAll($offset, $limit)`
- `fetchPairs($key, $value)`

## Registrace

Fetcher jednoduše zaregistruje jako službu pod **report.definitions**. Je připravena
i implementace pro cache v podobě `CachedFetcherFactory`.

```yaml
report:
  definitions:
	fetcher.cached:
		class: Tlapnet\Report\Fetcher\CachedFetcherFactory(@report.fetcher.dibi)
		autowired: off

	fetcher.dibi:
		class: Tlapnet\Report\Bridges\Dibi\Fetcher\DibiFetcherFactory
		autowired: off
```

## Usage

Použití je jednoduché, jako seznam položek pro select zavoláme `fetcher::fetchPairs`.

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
