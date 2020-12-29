# composer-normalize

[![Integrate](https://github.com/ergebnis/composer-normalize/workflows/Integrate/badge.svg?branch=main)](https://github.com/ergebnis/composer-normalize/actions)
[![Prune](https://github.com/ergebnis/composer-normalize/workflows/Prune/badge.svg?branch=main)](https://github.com/ergebnis/composer-normalize/actions)
[![Release](https://github.com/ergebnis/composer-normalize/workflows/Release/badge.svg?branch=main)](https://github.com/ergebnis/composer-normalize/actions)
[![Renew](https://github.com/ergebnis/composer-normalize/workflows/Renew/badge.svg?branch=main)](https://github.com/ergebnis/composer-normalize/actions)
[![Update](https://github.com/ergebnis/composer-normalize/workflows/Update/badge.svg?branch=main)](https://github.com/ergebnis/composer-normalize/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/composer-normalize/branch/main/graph/badge.svg)](https://codecov.io/gh/ergebnis/composer-normalize)
[![Type Coverage](https://shepherd.dev/github/ergebnis/composer-normalize/coverage.svg)](https://shepherd.dev/github/ergebnis/composer-normalize)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/composer-normalize/v/stable)](https://packagist.org/packages/ergebnis/composer-normalize)
[![Total Downloads](https://poser.pugx.org/ergebnis/composer-normalize/downloads)](https://packagist.org/packages/ergebnis/composer-normalize)

Provides a [`composer`](https://getcomposer.org) plugin for normalizing [`composer.json`](https://getcomposer.org/doc/04-schema.md).

## Motivation

If you have been working with `composer` on more than one project, you might
have noticed that each `composer.json` ends up being structured differently.

I certainly have noticed, and rather than

* ignoring it
* manually structuring `composer.json`
* asking others to structure `composer.json`

I decided to build something that structures `composer.json` in an automated
fashion, but without changing the initial intent.

In my opinion, the advantages of using `ergebnis/composer-normalize` are

* no need to think (or argue) about where to add a new section
* no need to think (or argue) about proper formatting
* no need to worry about keeping items in a consistent order where they can't be kept in order by other means
* can be used in a Continuous Integration environment

:bulb: If you are interested in finding out more before giving it a try, I
have written a blog post about [Normalizing composer.json](https://localheinz.com/blog/2018/01/15/normalizing-composer.json/).

## Installation

### Composer

Run

```
$ composer require --dev ergebnis/composer-normalize
```

to install `ergebnis/composer-normalize` as a composer plugin.

### Phar

Head over to http://github.com/ergebnis/composer-normalize/releases/latest and download the latest `composer-normalize.phar`.

Run

```
$ chmod +x composer-normalize.phar
```

to make the downloaded `composer-normalize.phar` executable.

### Phive

Run

```
$ phive install ergebnis/composer-normalize
```

to install `ergebnis/composer-normalize` with [PHIVE](https://phar.io).

## Usage

### Composer

Run

```
$ composer normalize
```

to normalize `composer.json` in the working directory.

### Phar

Run

```
$ ./composer-normalize.phar
```

to normalize `composer.json` in the working directory.

### Phive

Run

```
$ ./tools/composer-normalize
```

to normalize `composer.json` in the working directory.

### Details

The `NormalizeCommand` provided by the `NormalizePlugin` within this package will

* determine whether a `composer.json` exists
* determine whether a `composer.lock` exists, and if so, whether it is up to date (unless the `--no-check-lock` option is used)
* use [normalizers](https://github.com/ergebnis/composer-normalize#normalizers) to normalize the content of `composer.json`
* format the normalized content (either as sniffed, or as specified using the `--indent-size` and `--indent-style` options)
* write the normalized and formatted content of `composer.json` back to the file
* update the hash in `composer.lock` if it exists and if an update is necessary

### Arguments

* `file`: Path to composer.json file (optional, defaults to `composer.json` in working directory)

### Options

* `--diff`: Show the results of normalizing
* `--dry-run`: Show the results of normalizing, but do not modify any files
* `--indent-size`: Indent size (an integer greater than 0); should be used with the `--indent-style` option
* `--indent-style`: Indent style (one of "space", "tab"); should be used with the `--indent-size` option
* `--no-check-lock`: Do not check if lock file is up to date
* `--no-update-lock`: Do not update lock file if it exists

As an alternative to specifying the `--indent-size` and `--indent-style` options, you can also use composer [extra](https://getcomposer.org/doc/04-schema.md#extra) to configure these options in `composer.json`:

```json
{
  "extra": {
    "composer-normalize": {
      "indent-size": 2,
      "indent-style": "space",
    }
  }
}
```

:bulb: The configuration provided in composer extra always overrides the configuration provided via command line options.

### Continuous Integration

If you want to run this in continuous integration services, use the `--dry-run` option.

```
$ composer normalize --dry-run
```

In case `composer.json` is not normalized (or `composer.lock` is not up-to-date), the command will
fail with an exit code of `1` and show a diff.

## Normalizers

The `ComposerJsonNormalizer` composes normalizers provided by [`ergebnis/json-normalizer`](https://github.com/ergebnis/json-normalizer):

* [`Ergebnis\Json\Normalizer\ChainNormalizer`](https://github.com/ergebnis/json-normalizer#chainnormalizer)
* [`Ergebnis\Json\Normalizer\SchemaNormalizer`](https://github.com/ergebnis/json-normalizer#schemanormalizer)

as well as the following normalizers provided by this package:

* [`Ergebnis\Composer\Json\Normalizer\BinNormalizer`](https://github.com/ergebnis/composer-normalize/blob/main/README.md#binnormalizer)
* [`Ergebnis\Composer\Json\Normalizer\ConfigHashNormalizer`](https://github.com/ergebnis/composer-normalize/blob/main/README.md#confighashnormalizer)
* [`Ergebnis\Composer\Json\Normalizer\PackageHashNormalizer`](https://github.com/ergebnis/composer-normalize/blob/main/README.md#packagehashnormalizer)
* [`Ergebnis\Composer\Json\Normalizer\VersionConstraintNormalizer`](https://github.com/ergebnis/composer-normalize/blob/main/README.md#versionconstraintnormalizer)

### `BinNormalizer`

If `composer.json` contains an array of scripts in the `bin` section, the `BinNormalizer` will sort the elements of the `bin` section by value in ascending order.

:bulb: Find out more about the `bin` section at https://getcomposer.org/doc/04-schema.md#bin.

### `ConfigHashNormalizer`

If `composer.json` contains any configuration in the

* `config`
* `extra`
* `scripts-descriptions`

sections, the `ConfigHashNormalizer` will sort the content of these sections by key in ascending order.

:bulb: Find out more about the `config` section at https://getcomposer.org/doc/06-config.md.

### `PackageHashNormalizer`

If `composer.json` contains any configuration in the

* `conflict`
* `provide`
* `replace`
* `require`
* `require-dev`
* `suggest`

sections, the `PackageHashNormalizer` will sort the content of these sections.

:bulb: This transfers the behaviour from using the `--sort-packages` or `sort-packages` configuration flag to other sections. Find out more about the `--sort-packages` flag and configuration at https://getcomposer.org/doc/06-config.md#sort-packages and https://getcomposer.org/doc/03-cli.md#require.

### `VersionConstraintNormalizer`

If `composer.json` contains version constraints in the

* `conflict`
* `provide`
* `replace`
* `require`
* `require-dev`

sections, the `VersionConstraintNormalizer` will ensure that

* all constraints are trimmed
* *and* constraints are separated by a single space (` `) or a comma (`,`)
* *or* constraints are separated by double-pipe with a single space before and after (` || `)
* *range* constraints are separated by a single space (` `)

:bulb: Find out more about version constraints at https://getcomposer.org/doc/articles/versions.md.

## Examples

### `laravel/laravel`

Running

```
$ composer normalize
```

against https://github.com/laravel/laravel/blob/v5.6.12/composer.json yields the following diff:

```diff
diff --git a/composer.json b/composer.json
index 65bf8b4f..507ab39c 100644
--- a/composer.json
+++ b/composer.json
@@ -1,9 +1,12 @@
 {
     "name": "laravel/laravel",
+    "type": "project",
     "description": "The Laravel Framework.",
-    "keywords": ["framework", "laravel"],
+    "keywords": [
+        "framework",
+        "laravel"
+    ],
     "license": "MIT",
-    "type": "project",
     "require": {
         "php": "^7.1.3",
         "fideloper/proxy": "^4.0",
@@ -17,43 +20,42 @@
         "nunomaduro/collision": "^2.0",
         "phpunit/phpunit": "^7.0"
     },
+    "config": {
+        "optimize-autoloader": true,
+        "preferred-install": "dist",
+        "sort-packages": true
+    },
+    "extra": {
+        "laravel": {
+            "dont-discover": []
+        }
+    },
     "autoload": {
+        "psr-4": {
+            "App\\": "app/"
+        },
         "classmap": [
             "database/seeds",
             "database/factories"
-        ],
-        "psr-4": {
-            "App\\": "app/"
-        }
+        ]
     },
     "autoload-dev": {
         "psr-4": {
             "Tests\\": "tests/"
         }
     },
-    "extra": {
-        "laravel": {
-            "dont-discover": [
-            ]
-        }
-    },
+    "minimum-stability": "dev",
+    "prefer-stable": true,
     "scripts": {
+        "post-autoload-dump": [
+            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
+            "@php artisan package:discover"
+        ],
         "post-root-package-install": [
             "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
         ],
         "post-create-project-cmd": [
             "@php artisan key:generate"
-        ],
-        "post-autoload-dump": [
-            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
-            "@php artisan package:discover"
         ]
-    },
-    "config": {
-        "preferred-install": "dist",
-        "sort-packages": true,
-        "optimize-autoloader": true
-    },
-    "minimum-stability": "dev",
-    "prefer-stable": true
+    }
 }
```

### `symfony/symfony`

Running

```
$ composer normalize
```

against https://github.com/symfony/symfony/blob/v4.1.7/composer.json yields the following diff:

```diff
diff --git a/composer.json b/composer.json
index f861cbca31..b36000853a 100644
--- a/composer.json
+++ b/composer.json
@@ -2,7 +2,9 @@
     "name": "symfony/symfony",
     "type": "library",
     "description": "The Symfony PHP framework",
-    "keywords": ["framework"],
+    "keywords": [
+        "framework"
+    ],
     "homepage": "https://symfony.com",
     "license": "MIT",
     "authors": [
@@ -20,7 +22,6 @@
         "ext-xml": "*",
         "doctrine/common": "~2.4",
         "fig/link-util": "^1.0",
-        "twig/twig": "^1.35|^2.4.4",
         "psr/cache": "~1.0",
         "psr/container": "^1.0",
         "psr/link": "^1.0",
@@ -29,7 +30,8 @@
         "symfony/polyfill-ctype": "~1.8",
         "symfony/polyfill-intl-icu": "~1.0",
         "symfony/polyfill-mbstring": "~1.0",
-        "symfony/polyfill-php72": "~1.5"
+        "symfony/polyfill-php72": "~1.5",
+        "twig/twig": "^1.35 || ^2.4.4"
     },
     "replace": {
         "symfony/asset": "self.version",
@@ -38,9 +40,9 @@
         "symfony/config": "self.version",
         "symfony/console": "self.version",
         "symfony/css-selector": "self.version",
-        "symfony/dependency-injection": "self.version",
         "symfony/debug": "self.version",
         "symfony/debug-bundle": "self.version",
+        "symfony/dependency-injection": "self.version",
         "symfony/doctrine-bridge": "self.version",
         "symfony/dom-crawler": "self.version",
         "symfony/dotenv": "self.version",
@@ -65,11 +67,11 @@
         "symfony/proxy-manager-bridge": "self.version",
         "symfony/routing": "self.version",
         "symfony/security": "self.version",
+        "symfony/security-bundle": "self.version",
         "symfony/security-core": "self.version",
         "symfony/security-csrf": "self.version",
         "symfony/security-guard": "self.version",
         "symfony/security-http": "self.version",
-        "symfony/security-bundle": "self.version",
         "symfony/serializer": "self.version",
         "symfony/stopwatch": "self.version",
         "symfony/templating": "self.version",
@@ -84,32 +86,37 @@
         "symfony/workflow": "self.version",
         "symfony/yaml": "self.version"
     },
+    "conflict": {
+        "phpdocumentor/reflection-docblock": "<3.0 || >=3.2.0,<3.2.2",
+        "phpdocumentor/type-resolver": "<0.3.0",
+        "phpunit/phpunit": "<5.4.3"
+    },
+    "provide": {
+        "psr/cache-implementation": "1.0",
+        "psr/container-implementation": "1.0",
+        "psr/log-implementation": "1.0",
+        "psr/simple-cache-implementation": "1.0"
+    },
     "require-dev": {
         "cache/integration-tests": "dev-master",
         "doctrine/annotations": "~1.0",
         "doctrine/cache": "~1.6",
         "doctrine/data-fixtures": "1.0.*",
         "doctrine/dbal": "~2.4",
-        "doctrine/orm": "~2.4,>=2.4.5",
         "doctrine/doctrine-bundle": "~1.4",
+        "doctrine/orm": "~2.4,>=2.4.5",
+        "egulias/email-validator": "~1.2,>=1.2.8 || ~2.0",
         "monolog/monolog": "~1.11",
-        "ocramius/proxy-manager": "~0.4|~1.0|~2.0",
+        "ocramius/proxy-manager": "~0.4 || ~1.0 || ~2.0",
+        "phpdocumentor/reflection-docblock": "^3.0 || ^4.0",
         "predis/predis": "~1.0",
-        "egulias/email-validator": "~1.2,>=1.2.8|~2.0",
-        "symfony/phpunit-bridge": "~3.4|~4.0",
-        "symfony/security-acl": "~2.8|~3.0",
-        "phpdocumentor/reflection-docblock": "^3.0|^4.0"
+        "symfony/phpunit-bridge": "~3.4 || ~4.0",
+        "symfony/security-acl": "~2.8 || ~3.0"
     },
-    "conflict": {
-        "phpdocumentor/reflection-docblock": "<3.0||>=3.2.0,<3.2.2",
-        "phpdocumentor/type-resolver": "<0.3.0",
-        "phpunit/phpunit": "<5.4.3"
-    },
-    "provide": {
-        "psr/cache-implementation": "1.0",
-        "psr/container-implementation": "1.0",
-        "psr/log-implementation": "1.0",
-        "psr/simple-cache-implementation": "1.0"
+    "extra": {
+        "branch-alias": {
+            "dev-master": "4.1-dev"
+        }
     },
     "autoload": {
         "psr-4": {
@@ -128,12 +135,9 @@
         ]
     },
     "autoload-dev": {
-        "files": [ "src/Symfony/Component/VarDumper/Resources/functions/dump.php" ]
+        "files": [
+            "src/Symfony/Component/VarDumper/Resources/functions/dump.php"
+        ]
     },
-    "minimum-stability": "dev",
-    "extra": {
-        "branch-alias": {
-            "dev-master": "4.1-dev"
-        }
-    }
+    "minimum-stability": "dev"
 }
```

### `zendframework/zend-expressive`

Running

```
$ composer normalize
```

against https://github.com/zendframework/zend-expressive/blob/3.2.1/composer.json yields the following diff:

```diff
diff --git a/composer.json b/composer.json
index 478ab18a..773be7fa 100644
--- a/composer.json
+++ b/composer.json
@@ -1,7 +1,6 @@
 {
     "name": "zendframework/zend-expressive",
     "description": "PSR-15 Middleware Microframework",
-    "license": "BSD-3-Clause",
     "keywords": [
         "http",
         "middleware",
@@ -14,14 +13,7 @@
         "zendframework",
         "zend-expressive"
     ],
-    "support": {
-        "docs": "https://docs.zendframework.com/zend-expressive/",
-        "issues": "https://github.com/zendframework/zend-expressive/issues",
-        "source": "https://github.com/zendframework/zend-expressive",
-        "rss": "https://github.com/zendframework/zend-expressive/releases.atom",
-        "slack": "https://zendframework-slack.herokuapp.com",
-        "forum": "https://discourse.zendframework.com/c/questions/expressive"
-    },
+    "license": "BSD-3-Clause",
     "require": {
         "php": "^7.1",
         "fig/http-message-util": "^1.1.2",
         "http",
         "middleware",
@@ -14,14 +13,7 @@
         "zendframework",
         "zend-expressive"
     ],
-    "support": {
-        "docs": "https://docs.zendframework.com/zend-expressive/",
-        "issues": "https://github.com/zendframework/zend-expressive/issues",
-        "source": "https://github.com/zendframework/zend-expressive",
-        "rss": "https://github.com/zendframework/zend-expressive/releases.atom",
-        "slack": "https://zendframework-slack.herokuapp.com",
-        "forum": "https://discourse.zendframework.com/c/questions/expressive"
-    },
+    "license": "BSD-3-Clause",
     "require": {
         "php": "^7.1",
         "fig/http-message-util": "^1.1.2",
@@ -33,6 +25,10 @@
         "zendframework/zend-httphandlerrunner": "^1.0.1",
         "zendframework/zend-stratigility": "^3.0"
     },
+    "conflict": {
+        "container-interop/container-interop": "<1.2.0",
+        "zendframework/zend-diactoros": "<1.7.1"
+    },
     "require-dev": {
         "filp/whoops": "^1.1.10 || ^2.1.13",
         "malukenho/docheader": "^0.1.6",
@@ -47,10 +43,6 @@
         "zendframework/zend-expressive-zendrouter": "^3.0",
         "zendframework/zend-servicemanager": "^2.7.8 || ^3.3"
     },
-    "conflict": {
-        "container-interop/container-interop": "<1.2.0",
-        "zendframework/zend-diactoros": "<1.7.1"
-    },
     "suggest": {
         "filp/whoops": "^2.1 to use the Whoops error handler",
         "psr/http-message-implementation": "Please install a psr/http-message-implementation to consume Expressive; e.g., zendframework/zend-diactoros",
@@ -60,19 +52,6 @@
         "zendframework/zend-pimple-config": "^1.0 to use Pimple for dependency injection container",
         "zendframework/zend-servicemanager": "^3.3 to use zend-servicemanager for dependency injection"
     },
-    "autoload": {
-        "files": [
-            "src/constants.php"
-        ],
-        "psr-4": {
-            "Zend\\Expressive\\": "src/"
-        }
-    },
-    "autoload-dev": {
-        "psr-4": {
-            "ZendTest\\Expressive\\": "test/"
-        }
-    },
     "config": {
         "sort-packages": true
     },
@@ -85,6 +64,19 @@
             "config-provider": "Zend\\Expressive\\ConfigProvider"
         }
     },
+    "autoload": {
+        "psr-4": {
+            "Zend\\Expressive\\": "src/"
+        },
+        "files": [
+            "src/constants.php"
+        ]
+    },
+    "autoload-dev": {
+        "psr-4": {
+            "ZendTest\\Expressive\\": "test/"
+        }
+    },
     "bin": [
         "bin/expressive-tooling"
     ],
@@ -96,9 +88,17 @@
         ],
         "cs-check": "phpcs",
         "cs-fix": "phpcbf",
+        "license-check": "docheader check src/ test/",
         "phpstan": "phpstan analyze -l max -c phpstan.neon ./src",
         "test": "phpunit --colors=always",
-        "test-coverage": "phpunit --colors=always --coverage-clover clover.xml",
-        "license-check": "docheader check src/ test/"
+        "test-coverage": "phpunit --colors=always --coverage-clover clover.xml"
+    },
+    "support": {
+        "issues": "https://github.com/zendframework/zend-expressive/issues",
+        "forum": "https://discourse.zendframework.com/c/questions/expressive",
+        "source": "https://github.com/zendframework/zend-expressive",
+        "docs": "https://docs.zendframework.com/zend-expressive/",
+        "rss": "https://github.com/zendframework/zend-expressive/releases.atom",
+        "slack": "https://zendframework-slack.herokuapp.com"
     }
 }
```

## Changelog

Please have a look at [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](https://github.com/ergebnis/.github/blob/main/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.

Please have a look at [`LICENSE.md`](LICENSE.md).

## GitHub Action

`ergebnis/composer-normalize` is also available as a [GitHub Action](https://github.com/features/actions) on the [GitHub Marketplace](https://github.com/marketplace), see [`composer-normalize-action`](https://github.com/marketplace/actions/composer-normalize-action) as well as the corresponding repository [`ergebnis/composer-normalize-action`](https://github.com/ergebnis/composer-normalize-action).

## Services

`ergebnis/composer-normalize` is currently in use by [FlintCI](https://flintci.io), see https://flintci.io/docs#composernormalize.

## Credits

The algorithm for sorting packages in the [`PackageHashNormalizer`](https://github.com/ergebnis/composer-json-normalizer/blob/master/src/PackageHashNormalizer.php) has been adopted from [`Composer\Json\JsonManipulator::sortPackages()`](https://github.com/composer/composer/blob/1.6.2/src/Composer/Json/JsonManipulator.php#L110-L146) (originally licensed under MIT by [Nils Adermann](https://github.com/naderman) and [Jordi Boggiano](https://github.com/seldaek)), which I initially contributed to `composer/composer` with [`composer/composer#3549`](https://github.com/composer/composer/pull/3549) and [`composer/composer#3872`](https://github.com/composer/composer/pull/3872).

## Curious what I am building?

:mailbox_with_mail: [Subscribe to my list](https://localheinz.com/projects/), and I will occasionally send you an email to let you know what I am working on.
