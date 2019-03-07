# php-utils

php-utils is a collection of type-safe wrappers and testing utilities around a few functions of the PHP standard library. It's so useful that we wanted to use it everywhere, including our personal projects. So, here is that.

## Documentation

### Clock

Clock wraps `time()`, `microtime()`, `sleep()`, `usleep()` in a way that allows these functions to return a fake time during tests (and the real time otherwise).

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

This class provides a few date creation methods that use `Clock` internally, so that they can be mocked.

``` php
<?php

DateUtils::now(); // Same as new \DateTimeImmutable();

DateUtils::nowMutable(); // Same as new \DateTime();

DateUtils::fromString($dateString); // Same as new \DateTimteImmutable($dateString);

DateUtils::fromString($dateString, $format); // Same as \DateTimeImmutable::createFromFormat($format, $dateString);

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
