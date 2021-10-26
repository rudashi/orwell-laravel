Orwell Laravel Package
================

General System Requirements
-------------
- [PHP >7.1.0](http://php.net/)
- [Laravel ~5.6.*](https://github.com/laravel/framework)

Quick Installation
-------------
If necessary, use the composer to download the library

```bash
$ composer require rudashi/orwell-laravel
```

Remember to put repository in composer.json

```json
"repositories": [
    {
        "type": "vcs",
        "url":  "https://github.com/rudashi/orwell-laravel.git"
    }
],
```

Usage
-------------

### SQL

-- Populate it
```sql
UPDATE words SET characters = regexp_split_to_array(word, '') WHERE characters IS NULL;
```

-- Remove Null after populate
```sql
ALTER TABLE words ALTER COLUMN characters SET NOT NULL;
```

-- An index on word length
```sql
CREATE INDEX ix_word_length ON words (char_length(word));
```

-- If needed
```sql
CREATE OR REPLACE FUNCTION sort_chars(text) RETURNS text AS
    $func$
SELECT array_to_string(ARRAY(SELECT unnest(string_to_array($1 COLLATE "C", NULL)) c ORDER BY c), '')
    $func$  LANGUAGE sql IMMUTABLE;
```

-- If needed add points to words
```sql
UPDATE words SET points =
  (
      SELECT SUM(points)
      FROM ( SELECT regexp_split_to_table(words.word,E'(?=.)') the_word) tab 
      LEFT JOIN alphas ON the_word = letter
  )
WHERE points IS NULL
```

### API

Get All words from characters. 
```
GET /api/orwell/{letters}
```


Authors
-------------

* **Borys Żmuda** - [LinkedIn](https://www.linkedin.com/in/boryszmuda/), [Portfolio](https://rudashi.github.io/)
