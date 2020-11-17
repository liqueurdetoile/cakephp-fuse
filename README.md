[![Latest Stable Version](https://img.shields.io/github/release/liqueurdetoile/cakephp-fuse.svg?style=flat-square)](https://packagist.org/packages/liqueurdetoile/cakephp-fuse
)
[![Build Status](https://travis-ci.com/liqueurdetoile/cakephp-fuse.svg?branch=master)](https://travis-ci.com/liqueurdetoile/cakephp-fuse)
[![Coverage Status](https://coveralls.io/repos/github/liqueurdetoile/cakephp-fuse/badge.svg?branch=master)](https://coveralls.io/github/liqueurdetoile/cakephp-fuse?branch=master)
[![license](https://img.shields.io/github/license/liqueurdetoile/cakephp-fuse.svg?style=flat-square)](https://packagist.org/packages/liqueurdetoile/cakephp-fuse)

# Cakephp-fuse plugin for CakePHP

This plugin is a simple wrapper behavior around [Fuse](https://github.com/loilo/Fuse) to implement fuzzy search within any model. Searches can only be performed on strings.

**This behavior requires at least PHP 7.1 and can only be used with Cakephp 3.x and 4.x branches.**

<!-- TOC depthFrom:1 depthTo:6 withLinks:1 updateOnSave:1 orderedList:0 -->

- [Cakephp-fuse plugin for CakePHP](#cakephp-fuse-plugin-for-cakephp)
	- [Installation](#installation)
	- [Usage](#usage)
		- [Basic usage](#basic-usage)
		- [Persistent configuration](#persistent-configuration)
		- [Nested associations](#nested-associations)
		- [Autokeys detection](#autokeys-detection)
	- [API cheatsheet](#api-cheatsheet)
	- [CHANGELOG](#changelog)

<!-- /TOC -->

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require liqueurdetoile/cakephp-fuse
```

The plugin itself is only a behavior that [can be attached to any model](https://book.cakephp.org/4/en/orm/behaviors.html) in the `initialize` method :

```php
$this->addBehavior('Lqdt/CakephpFuse.Fuse');
```

## Usage

### Basic usage
The behavior provides a `fuse` method on the model to get back a configured query. For convenience a custom finder is also available. The two following calls are totally equivalent :

```php
$query = $this->Items->fuse('test');
$query = $this->Items->find('fuse' ['filter' => 'test']);
```

When providing no additional options or configuration, fuzzy search will be applied to all string fields with default options. [Any options accepted by Fuse](https://github.com/loilo/Fuse#options) are available. For instance, to restrict keys and tweak threshold (assuming there's a `name` field in Items data):

```php
$query = $this->Items->fuse('test', ['keys' => ['name'], 'threshold' => 0.2]);
$query = $this->Items->find('fuse' ['filter' => 'test', 'keys' => ['name'], 'threshold' => 0.2]);
```

### Persistent configuration
You can set up your model to always use a persistent configuration set when using fuse. Is some options are also provided on-the-fly, they will be mixed with persistent ones and override the latter when conflicting.

```php
// In the initialize method of the model
$this
  ->addBehavior('Lqdt/CakephpFuse.Fuse')
  ->setSearchableFields(['name'])
  ->setOptions(['threshold' => 0.2]);

// or
$this
  ->addBehavior('Lqdt/CakephpFuse.Fuse')
  ->setOptions([
    'keys' => ['name'],
    'threshold' => 0.2
  ]);

// or
$this->addBehavior('Lqdt/CakephpFuse.Fuse', [
  'keys' => ['name'],
  'threshold' => 0.2
]);
```

### Nested associations
The search can also be done in nested associations (only `hasOne` or `BelongsTo`) by using a dot separator with **property name** :

```php
// Assuming Items belongsTo Owners, with owner as property and name as string field
$query = $this->Items->fuse('test', ['keys' => ['name', 'owner.name']])->contain(['Owners']);

// Assuming Owners also belongsTo Services, with service as property and name as string field
$query = $this->Items->fuse('test', ['keys' => ['name', 'owner.service.name']])->contain(['Owners', 'Owners.Services']);
```

### Autokeys detection
If no keys are provided in options, the model will consider each `string` field as a searchable key. This also works for any contained model in the query :

```php
// Will search any string fields in Items, Owners and Services
$query = $this->Items->fuse('test')->contain(['Owners', 'Owners.Services']);
```

## API cheatsheet
`fuse(string $finder, array $options = [], \Cake\ORM\Query $query = null) : Query`

Schedule the fuzzy search with `finder` keyword(s) on the results of the query and returns the query. If none is provided, the autokeys and default options will be applied only at runtime

`find('fuse', array $options = [])`

Convenient custom finder that relies on `fuse` method

`getSearchableFields(): array`

Returns the persistent defined keys for fuzzy search or populates them with autofields if none is set

`setSearchableFields(array $fields = []): self`
Sets the persistent defined keys for fuzzy search

`getFuseOptions(): array`

Returns the current persistent options

`setFuseOptions(array $options, bool $replace = false): self`

Sets the persistent options. If keys are conflicting, provided value will override current value. If `replace` is set to true, all options will be replaced

There is some more advanced tools that can be found in behavior code.

## CHANGELOG

- v1.0.0: Initial release
