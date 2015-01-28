<!-- This is the _Skeleton's_ Readme! To edit the readme bundled with new projects, open README.md.template. -->
# CakePHP Application Skeleton

A skeleton for creating applications with [CakePHP](http://cakephp.org) 3.0. (If you're looking
for a 2.x compatible version you'll want to check out the
[cake-2.x](https://github.com/loadsys/CakePHP-Skeleton/tree/cake-2.x) branch.)

:warning: This is an unstable repository and should be treated as an alpha.

## Usage

It's generally a good idea to run `composer self-update` first. If you have
composer istalled globally already it's as simple as:

```bash
composer create-project --prefer-dist --ignore-platform-reqs loadsys/app [app_name] 3.0.*
```

otherwise you need to
1. Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist --ignore-platform-reqs loadsys/app [app_name] 3.0.*`.


After composer has finished you should be able to run `vagrant up` while in the project root. While Vagrant
is building the VM it's a good time to edit your system's `hosts` file to include the IP from the
`puphpet/config.yaml` file. The finished machine's webserver will respond to any domain that resolves
to this IP address.

Once Vagrant is done, you should be able to visit the path to where you installed the app and see
the setup traffic lights as green.

## Configuration

 *  [x] Sets up environment sepecific config files.
 *  [x] Install composer dependencies.
 *  [x] Creates a README.md
 *  [ ] Sets up a git repository.

### Hoping to Accomplish

 *  [ ] auto test running
 *  [ ] auto code sniffing
 *  [ ] auto builds
 *  [ ] auto deploy to stage
 *  [ ] one-click code rollback

### Contributing

If you would like to contribute to this project open a pull request targeted at the correct branch;
`master` for the 3.x skeleton and `cake-2.x` for the 2.x skeleton.
