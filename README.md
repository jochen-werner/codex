Codex
=====
[![Laravel](https://img.shields.io/badge/Laravel-5.0-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-caffeinated/skeleton-blue.svg?style=flat-square)](https://github.com/caffeinated/skeleton)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

**Still an early WIP.**

Codex aims to be a simple package to render Markdown documents for the purposes of hosting (beautiful) documentation with little hassle.

The package follows the FIG standards PSR-1, PSR-2, and PSR-4 to ensure a high level of interoperability between shared PHP code. At the moment the package is not unit tested, but is planned to be covered later down the road.

Quick Installation
------------------
Begin by installing the package through Composer. Add `codex/codex` to your composer.json file:

```
"codex/codex": "~1.0@dev"
```

Then run `composer update` to pull the package in.

Once this operation is complete, simply add the service provider class and facade alias to your project's `config/app.php` file:

#### Service Provider

```php
'Codex\Codex\CodexServiceProvider',
```
