# CakePHP Application Skeleton

<!-- [![Build Status](https://api.travis-ci.org/cakephp/app.png)](https://travis-ci.org/cakephp/app) -->
<!-- [![License](https://poser.pugx.org/cakephp/app/license.svg)](https://packagist.org/packages/cakephp/app) -->

A skeleton for creating applications with [CakePHP](http://cakephp.org) 3.0.

This is an unstable repository and should be treated as an alpha.

## Installation

1. Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist -s dev loadsys/app [app_name]`.

If Composer is installed globally, run
```bash
composer create-project --prefer-dist -s dev loadsys/app [app_name]
```

You should now be able to visit the path to where you installed the app and see
the setup traffic lights.

## Configuration

 *  [x] Sets up a vagrant.php and local.php config files.
 *  [x] Adds logic to bootstrap.php to load the environment (`APP_ENV`) and local configs.
 *  [x] Runs composer install to load dependencies (including the Cake core itself) into the new project.
 *  [ ] Sets up a git repository.
 *  [ ] Creates a README.md with placeholder values replaced.
 *  [ ]

# Loadsys Specifics

### Hoping to Accomplish

 *  [ ] auto test running
 *  [ ] auto code sniffing
 *  [ ] auto builds
 *  [ ] auto deploy to stage
 *  [ ] one-click code rollback
