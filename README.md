# Orwell Laravel Package

## General System Requirements

- [PHP ^8.1](http://php.net/)
- [Laravel ^11.0](https://github.com/laravel/framework)

## Quick Installation

If necessary, use the composer to download the library

```bash
$ composer require rudashi/orwell-laravel
```

Remember to put repository in composer.json

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url":  "https://github.com/rudashi/orwell-laravel.git"
        }
    ]
}
```

## Usage

### SQL

-- Populate database

```postgresql
UPDATE words SET characters = regexp_split_to_array(word, '') WHERE characters IS NULL;
```

-- Remove Null after populate

```postgresql
ALTER TABLE words ALTER COLUMN characters SET NOT NULL;
```

-- Add points to words

```postgresql
UPDATE words SET points =
  (
      SELECT SUM(alphas.points)
      FROM ( SELECT regexp_split_to_table(words.word,E'(?=.)') the_word) tab 
      LEFT JOIN alphas ON the_word = letter
  )
WHERE points IS NULL
```

-- If missing function `sort_chars()`

```postgresql
CREATE OR REPLACE FUNCTION sort_chars(text) RETURNS text AS
    $func$
SELECT array_to_string(ARRAY(SELECT unnest(string_to_array($1 COLLATE "C", NULL)) c ORDER BY c), '')
    $func$  LANGUAGE sql IMMUTABLE;
```

-- if missing an index on word length

```postgresql
CREATE INDEX ix_word_length ON words (char_length(word));
```

## Authors

* **Borys Żmuda** - Lead designer - [LinkedIn](https://www.linkedin.com/in/boryszmuda/), [Portfolio](https://rudashi.github.io/)
