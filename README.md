Orwell Laravel Package
================

General System Requirements
-------------
- [PHP >7.1.0](http://php.net/)
- [Laravel ~5.6.*](https://github.com/laravel/framework)

Quick Installation
-------------
If needed use composer to grab the library

```
$ composer require rudashi/orwell-laravel
```

Remember to put repository in composer.json

```
"repositories": [
    {
        "type": "vcs",
        "url":  "https://github.com/rudashi/orwell-laravel.git"
    }
],
```

Usage
-------------

###API

Get All words from characters. 
```
GET /api/orwell/{letters}
```


Authors
-------------

* **Borys Zmuda** - Lead designer - [GoldenLine](http://www.goldenline.pl/borys-zmuda/)