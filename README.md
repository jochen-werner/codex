Codex
=====
[![Laravel](https://img.shields.io/badge/Laravel-5.1-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-codex-project/codex-blue.svg?style=flat-square)](https://github.com/codex-project/codex)
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

#### Service Provider
Add the Codex service provider class to your project's `config/app.php` file.

```php
Codex\Codex\CodexServiceProvider::class,
```

#### Publish Assets
If you plan on using the supplied default view files and route, you'll need to publish the package's assets to your project.

```
php artisan vendor:publish
```

Features
--------
- Built for Laravel 5.1
- GitHub-flavored Markdown
- Host documentation for all your **projects** in one location
- Host documentation for each **version** of your projects in one location.
- Easy TOC / navigation system
- SEO freindly URLs
- Supplied theme built on Bootstrap, inspired by Google's Material Design
- Supports multiple storage methods
- Open API so you may integrate Codex natively within your app