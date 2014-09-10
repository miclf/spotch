# spotch

A *quick and (not too) dirty* bookmarklet generator. Use it at you own risks.

All the code is placed in the public domain via a [Creative Commons Zero license](http://creativecommons.org/publicdomain/zero/1.0/).

## Installation

Since this package is not distributed through Packagist, you need to tell Composer where to find it. Add the following to your `composer.json`:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/miclf/spotch"
    }
]
```

Then, require this package in the same way as any other package:

```json
"require": {
    "miclf/spotch": "dev-master"
},
```

## Usage

spotch is usable as a CLI tool.

```bash
$ vendor/bin/spotch
```

You can also use its classes directly.

## Tests

In order to run the tests, you need to install the Composer dev dependencies. Once this is done, you can run phpspec with:

```bash
$ vendor/bin/phpspec run
```
