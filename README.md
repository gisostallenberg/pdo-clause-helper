# PDO Clause Helper

Utility to create placeholder strings to use in `WHERE ... IN ()` clauses.

> ## ⚠️ Avoid in new code
>
> This is not a security problem — generating placeholders is the safe pattern —
> but the helper reinvents a one-liner and has a surprising API: it rewrites the
> `$values` array you pass by reference.
>
> The named-placeholder behaviour is a one-liner with the standard library. It
> builds both the clause string and the bind map, without mutating your input:
>
> ```php
> $params = array_combine(
>     array_map(fn($i) => ":p$i", array_keys($vals = array_values($values))),
>     $vals
> );
> $in = '(' . implode(',', array_keys($params)) . ')';
>
> // $in     === '(:p0,:p1,:p2)'
> // $params === [':p0' => ..., ':p1' => ..., ':p2' => ...]
> $stmt = $pdo->prepare("SELECT ... WHERE id IN $in");
> $stmt->execute($params);
> ```
>
> `array_values()` first reindexes the keys to `0..n-1`, so associative or sparse
> input works too. Guard the empty case before building, since `IN ()` is invalid
> SQL:
>
> ```php
> if ($values === []) { /* no matching rows; skip the query */ }
> ```
>
> For a maintained library that expands array parameters for you, use
> [Doctrine DBAL](https://github.com/doctrine/dbal): `IN (?)` with
> `executeQuery($sql, [$ids], [ArrayParameterType::INTEGER])` (older DBAL exposes
> the `Connection::PARAM_INT_ARRAY` / `PARAM_STR_ARRAY` constants). Any query
> builder (Doctrine ORM DQL `IN (:ids)`, Laravel's `whereIn`) handles it natively.

## Installation
```bash
composer require gisostallenberg/pdo-clause-helper
```

## Usage example
```php
use GisoStallenberg\PDOClauseHelper\ClauseHelper;

$values = ['foo', 'bar', 'baz'];

// Note: $values is rewritten in place to the named bind map.
$placeholders = ClauseHelper::create()->toInClausePlaceHolderString($values);
// $placeholders === '(:parameter0, :parameter1, :parameter2)'
// $values       === [':parameter0' => 'foo', ':parameter1' => 'bar', ':parameter2' => 'baz']

$stmt = $pdo->prepare("SELECT * FROM t WHERE col IN $placeholders");
$stmt->execute($values);
```
