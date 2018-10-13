# List all PHP classes in a given directory

[![Build Status](https://travis-ci.com/wyrihaximus/php-list-classes-in-directory.svg?branch=master)](https://travis-ci.com/wyrihaximus/php-list-classes-in-directory)
[![Latest Stable Version](https://poser.pugx.org/wyrihaximus/list-classes-in-directory/v/stable.png)](https://packagist.org/packages/wyrihaximus/list-classes-in-directory)
[![Total Downloads](https://poser.pugx.org/wyrihaximus/list-classes-in-directory/downloads.png)](https://packagist.org/packages/wyrihaximus/list-classes-in-directory/stats)
[![Code Coverage](https://scrutinizer-ci.com/g/wyrihaximus/php-list-classes-in-directory/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/wyrihaximus/php-list-classes-in-directory/?branch=master)
[![License](https://poser.pugx.org/wyrihaximus/list-classes-in-directory/license.png)](https://packagist.org/packages/wyrihaximus/list-classes-in-directory)
[![PHP 7 ready](http://php7ready.timesplinter.ch/wyrihaximus/php-list-classes-in-directory/badge.svg)](https://travis-ci.org/wyrihaximus/php-list-classes-in-directory)

# Install

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `^`.

```
composer require wyrihaximus/list-classes-in-directory
```

# Usage

```php
// $classes now contains a list of full qualified class names from __DIR__
$classes = listClassesInDirectory(__DIR__);
```

# License

The MIT License (MIT)

Copyright (c) 2018 Cees-Jan Kiewiet

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.