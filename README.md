# Guzzle extensions for PHPStan

[![Build Status](https://travis-ci.org/azzra/phpstan-guzzle.svg)](https://travis-ci.org/azzra/phpstan-guzzle)
[![Latest Stable Version](https://poser.pugx.org/azzra/phpstan-guzzle/v/stable)](https://packagist.org/packages/azzra/phpstan-guzzle)
[![Coverage Status](https://coveralls.io/repos/github/azzra/phpstan-guzzle/badge.svg?branch=master)](https://coveralls.io/github/azzra/phpstan-guzzle?branch=master)

* [PHPStan](https://github.com/phpstan/phpstan)
* [Guzzle](https://github.com/guzzle/guzzle)

This extension provides following features:

* Adds missing magics method on `GuzzleHttp\Client` with correct return types

## Usage

To use this extension, require it in [Composer](https://getcomposer.org/):

```
composer require --dev azzra/phpstan-guzzle
```

And include `extension.neon` in your project's PHPStan config:

```
includes:
	- vendor/azzra/phpstan-guzzle/extension.neon
```
