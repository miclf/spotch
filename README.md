# spotch

A *quick and (not too) dirty* bookmarklet generator. Use it at your own risks.

All the code is placed in the public domain via a [Creative Commons Zero license](http://creativecommons.org/publicdomain/zero/1.0/).

## Requirements

You need the [Composer package manager](http://getcomposer.org/) to install spotch. **PHP 5.4** or any newer version is enough to run it.

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
}
```

## Usage

spotch is usable as a CLI tool. When installing the package, Composer will conveniently create a symbolic link (an alias) to it from the `vendor/bin` directory. You can then access it from your project root with:

```bash
$ php vendor/bin/spotch
```

The tool provides a `make` command that takes the path to a JavaScript file as its unique argument. It will read the file and create a bookmarklet string from its content.

```bash
$ php vendor/bin/spotch make path/to/my/file.js
```

By default, spotch will output the result in the command line (`stdout`). If you prefer to automatically save it in a file, you can use the `--output` option (or its `-o` shortcut) to provide the path to that file. Its content will be replaced or the file will be created if it does not exist yet.

```bash
$ php vendor/bin/spotch make path/to/my/file.js --output path/to/save/bookmarklet.js
```

### Using the classes

The PHP classes that power spotch can also be used directly in your code. Here they are:

- **`\Miclf\Spotch\Minifier`** is the core of spotch. It provides some small public methods that can be used to work on JavaScript code. You can use it to minify, remove single-line comments, etc.
- **`\Miclf\Spotch\Bookmarkler`** provides a single public method: `make`. It leverages the `Minifier` class and does a few extra steps in order to create a bookmarklet string from JavaScript code. If you intend to generate bookmarklets from your own PHP application, this is the class you will want to use.

These classes do not use any dependency injection mechanism. You can instantiate them even without using an IoC container.

## Tests

In order to run the tests, you need to install the Composer dev dependencies. Once this is done, you can run [phpspec](http://phpspec.net/) with:

```bash
$ vendor/bin/phpspec run
```
