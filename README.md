# Kebab 🌮

Kebab is a collection of safety wrappers and testing utilities around a few functions of the PHP standard library. It's so useful that we wanted to use it everywhere, including our personal projects. So we couldn't keep it for ourselves.

[![Build Status](https://travis-ci.org/mentionapp/kebab.svg?branch=master)](https://travis-ci.org/mentionapp/kebab)
[![Latest Version](https://poser.pugx.org/mention/kebab/v/stable?_)](https://packagist.org/packages/mention/kebab)
[![MIT License](https://poser.pugx.org/mention/kebab/license?_)](https://choosealicense.com/licenses/mit/)
[![PHPStan Enabled](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

## Install

```
composer require mention/kebab
```

## Goals

The goals of this package are to:

 - Provide wrappers that are safe by default (by throwing exceptions on error)
 - Provide wrappers with better APIs
 - Make static analyzers happy
 - Make testing easier

Some functions in the PHP standard library have a notoriously bad API. Take for example `json_decode()`: If returns `null` if there was an error, or if the JSON string was the `NULL` literal. The package provides sane wrappers that automatically check for errors and throw exceptions on failure. The package also provides a wrapper for less broken functions like `file_get_contents()`, because throwing exceptions on errors is useful.

The wrappers do not only check for errors, they also try to improve the API. For instance, `json_decode()` is declined in two variants: `JsonUtils::decodeArray()` and `JsonUtils::decodeObject()`.

All wrappers have static, single return types, to make static analyzers happy. For example, there is `Clock::microtimeFloat()` and `Clock::microtimeString()` instead of a single function returning one of two possible types.

Finally, the package provides some tools to make testing easier, such as the `Clock` class that allows to fake the system time during tests.

## Overview

### Clock

Clock wraps `time()`, `microtime()`, `sleep()`, `usleep()` in a way that allows these functions to return a fake time during tests (and the system time otherwise).

Example:

``` php
<?php

Clock::enableMocking(946681200); // 946681200 can be any arbitrary timestamp

Clock::time(); // int(946681200)
Clock::microtimeFloat(); // float(946681200.0)

Clock::usleep(5500000); // returns immediately (no actual sleep)
Clock::microtimeFloat(); // float(946681205.5) : clock has advanced by 5500000 micro seconds

Clock::disableMocking();
```

Before calling `enableMocking()`, `Clock` methods return the true system time, and sleep functions actually pause the program, as expected. After calling `enableMocking()`, a fake time is returned instead, and sleep functions advance the fake time without actually pausing the program.

This is heavily inspired by Symfony's `ClockMock` class.

### Date\DateUtils

`DateUtils` provides a few date creation methods. The class uses `Clock` to get the system time, so its results can be controlled and predicted in tests.

``` php
<?php

DateUtils::now(); // Same as new \DateTimeImmutable();

DateUtils::nowMutable(); // Same as new \DateTime();

DateUtils::nowTz($timezone); // Same as new \DateTimeImmutable('now', $timezone);

DateUtils::fromString($dateString); // Same as new \DateTimeImmutable($dateString);

DateUtils::fromString($dateString, $format); // Same as \DateTimeImmutable::createFromFormat($format, $dateString);

DateUtils::fromStringMutable($dateString); // Same as new \DateTime($dateString);

DateUtils::fromStringMutable($dateString, $format); // Same as \DateTime::createFromFormat($format, $dateString);

DateUtils::fromStringTz($dateString, $timezone); // Same as new \DateTimeImmutable($dateString, $timezone);

DateUtils::fromStringTz($dateString, $timezone, $format); // Same as \DateTimeImmutable::createFromFormat($format, $dateString, $timezone);

DateUtils::fromTimestamp($timestamp); // Same as \DateTimeImmutable::createFromFormat("|U", (string) $timestamp);

DateUtils::fromTimestamp($timestamp, $micro); // Same as \DateTimeImmutable::createFromFormat("U u", "$timestamp $micro");
```

### File\FileUtils

This class provides a few file functions that throw an exception in case of failure.

``` php
<?php

FileUtils::read($file); // Reads file $file, throws exception on failure

FileUtils::open($file, $mode); // Opens file $file, throws exception on failure
```

### Json\JsonUtils

This class provides a few JSON functions that throw an exception in case of failure, with a slightly improved interface.

``` php
<?php

JsonUtils::encode($value); // json_encode, with exceptions on failure

JsonUtils::encodePretty($value); // returns pretty-printed JSON, throw exceptions on failure

JsonUtils::prettify($json); // prettifies a JSON string

JsonUtils::decodeObject($json); // decodes a JSON string, use stdClass to represent JSON objects (same as json_decode($value, false))

JsonUtils::decodeArray($json); // decodes a JSON string, use arrays to represent JSON objects (same as json_decode($value, true))
```

## Authors

The [Mention](https://mention.com) team and contributors
